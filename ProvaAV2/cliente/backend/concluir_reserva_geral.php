<?php
session_set_cookie_params(['path' => '/', 'samesite' => 'Lax']);
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada.']);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
if (!$dados || !isset($dados['carrinho']) || !isset($dados['data']) || !isset($dados['hora']) || !isset($dados['metodo'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados incompletos.']);
    exit;
}

require_once 'conexao.php';
$usuarioId = $_SESSION['usuario_id'];
$dataHora = $dados['data'] . ' ' . $dados['hora'] . ':00';

try {
    $pdo->beginTransaction();

    // Calcula o total em centavos baseado nos dados recebidos
    $totalCentavos = 0;
    foreach ($dados['carrinho'] as $item) {
        $totalCentavos += $item['preco'];
    }

    // Insere na tabela mestra 'agendamentos'
    $stmt = $pdo->prepare("INSERT INTO agendamentos (usuario_id, data_hora, valor_total_centavos, forma_pagamento, status_reserva) VALUES (:uid, :dh, :val, :met, 'concluido')");
    $stmt->execute([
        ':uid' => $usuarioId,
        ':dh' => $dataHora,
        ':val' => $totalCentavos,
        ':met' => $dados['metodo']
    ]);
    
    $agendamentoId = $pdo->lastInsertId();

    // Insere cada item individual vinculado ao agendamento mestre
    $stmtItem = $pdo->prepare("INSERT INTO agendamento_itens (agendamento_id, servico_id, preco_pago_centavos) VALUES (:aid, :sid, :preco)");
    foreach ($dados['carrinho'] as $item) {
        $stmtItem->execute([
            ':aid' => $agendamentoId,
            ':sid' => $item['id'],
            ':preco' => $item['preco']
        ]);
    }

    $pdo->commit();
    echo json_encode(['sucesso' => true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>