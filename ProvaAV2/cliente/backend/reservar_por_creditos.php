<?php
session_set_cookie_params([
    'path' => '/',
    'samesite' => 'Lax'
]);
session_start();
header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Usuário não autenticado. Por favor, faça login novamente.']);
    exit;
}

// Coleta os dados enviados pelo Fetch do JavaScript
$dadosEntrada = json_decode(file_get_contents('php://input'), true);

if (!$dadosEntrada || !isset($dadosEntrada['carrinho']) || !isset($dadosEntrada['data']) || !isset($dadosEntrada['hora'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados de agendamento incompletos.']);
    exit;
}

require_once 'conexao.php';
$usuarioId = $_SESSION['usuario_id'];
$dataReserva = $dadosEntrada['data'];
$horaReserva = $dadosEntrada['hora'];
$carrinho = $dadosEntrada['carrinho'];

try {
    // Recarrega o arquivo servicos.json para recalcular o custo real e blindar o sistema
    $caminhoJson = __DIR__ . '/../frontend/dados/servicos.json';
    if (!file_exists($caminhoJson)) {
        throw new Exception("Arquivo de serviços não encontrado.");
    }
    $conteudoJson = json_decode(file_get_contents($caminhoJson), true);
    
    // Mapeia os serviços para facilitar a busca por ID
    $mapaServicos = [];
    foreach ($conteudoJson as $categoria) {
        foreach ($categoria['itens'] as $item) {
            $mapaServicos[$item['id']] = $item;
        }
    }

    // Calcula o custo real em centavos e créditos
    $totalCentavosCalculado = 0;
    foreach ($carrinho as $itemCarrinho) {
        $idItem = $itemCarrinho['id'];
        if (!isset($mapaServicos[$idItem])) {
            throw new Exception("Serviço inválido identificado no carrinho.");
        }
        $totalCentavosCalculado += $mapaServicos[$idItem]['preco_centavos'];
    }
    $totalCreditosNecessarios = ($totalCentavosCalculado / 100) * 20;

    // Inicia a Transação no Banco de Dados
    $pdo->beginTransaction();

    // Verifica e valida o saldo atual de créditos do usuário direto no Banco
    $stmtUser = $pdo->prepare("SELECT creditos FROM usuarios WHERE id = :id FOR UPDATE");
    $stmtUser->execute([':id' => $usuarioId]);
    $usuarioBanco = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$usuarioBanco || (int)$usuarioBanco['creditos'] < $totalCreditosNecessarios) {
        throw new Exception("Saldo de créditos insuficiente para realizar esse agendamento.");
    }

    // Deduz os créditos do saldo da conta do usuário
    $stmtDesconto = $pdo->prepare("UPDATE usuarios SET creditos = creditos - :custo WHERE id = :id");
    $stmtDesconto->execute([
        ':custo' => $totalCreditosNecessarios,
        ':id' => $usuarioId
    ]);

    // Insere o registro mestre na tabela 'agendamentos'
    // Combinando data e hora no campo DATETIME
    $dataHoraCompleta = $dataReserva . ' ' . $horaReserva . ':00';
    
    $stmtAgendamento = $pdo->prepare("
        INSERT INTO agendamentos (usuario_id, data_hora, valor_total_centavos, forma_pagamento, status_reserva) 
        VALUES (:usuario_id, :data_hora, :valor, 'creditos', 'concluido')
    ");
    $stmtAgendamento->execute([
        ':usuario_id' => $usuarioId,
        ':data_hora'  => $dataHoraCompleta,
        ':valor'      => $totalCentavosCalculado
    ]);
    
    // Pega o ID gerado para esse agendamento
    $agendamentoId = $pdo->lastInsertId();

    // Insere cada item individual na tabela de itens de agendamento
    $stmtItem = $pdo->prepare("
        INSERT INTO agendamento_itens (agendamento_id, servico_id, preco_pago_centavos) 
        VALUES (:agendamento_id, :servico_id, :preco)
    ");

    foreach ($carrinho as $itemCarrinho) {
        $idItem = $itemCarrinho['id'];
        $stmtItem->execute([
            ':agendamento_id' => $agendamentoId,
            ':servico_id'     => $idItem,
            ':preco'          => $mapaServicos[$idItem]['preco_centavos']
        ]);
    }

    // Se tudo deu certo, salva definitivamente as alterações no banco!
    $pdo->commit();
    echo json_encode(['sucesso' => true]);

} catch (Exception $e) {
    // Se qualquer coisa falhar, desfaz tudo
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>