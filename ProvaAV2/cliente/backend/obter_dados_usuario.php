<?php
session_set_cookie_params([
    'path' => '/',
    'samesite' => 'Lax'
]);

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['erro' => 'Não autorizado']);
    exit;
}

require_once 'conexao.php';
$usuarioId = $_SESSION['usuario_id'];

try {
    // Busca os dados reais do Usuário
    $stmtUser = $pdo->prepare("SELECT nome, creditos FROM usuarios WHERE id = :id");
    $stmtUser->execute([':id' => $usuarioId]);
    $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(['erro' => 'Usuário inexistente']);
        exit;
    }

    // Conta agendamentos concluídos baseando-se na tabela real 'agendamentos'
    $stmtTrilha = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE usuario_id = :id AND LOWER(status) = 'concluido'");
    $stmtTrilha->execute([':id' => $usuarioId]);
    $reservasConcluidas = (int)$stmtTrilha->fetchColumn();

    // Pega próximos agendamentos ativos fazendo JOIN com os itens do agendamento
    $stmtReservas = $pdo->prepare("
        SELECT 
            DATE(a.data_hora) AS data, 
            TIME_FORMAT(TIME(a.data_hora), '%H:%i') AS horario,
            ai.servico_id AS servico_nome, 
            ai.profissional_id AS profissional_nome
        FROM agendamentos a
        JOIN agendamento_itens ai ON a.id = ai.agendamento_id
        WHERE a.usuario_id = :id AND LOWER(a.status) = 'agendado'
        ORDER BY a.data_hora ASC
    ");
    $stmtReservas->execute([':id' => $usuarioId]);
    $reservasAtivas = $stmtReservas->fetchAll(PDO::FETCH_ASSOC);

    // Retorna a estrutura correta para o JavaScript mapear
    echo json_encode([
        'nome' => $usuario['nome'],
        'creditos' => (int)$usuario['creditos'],
        'reservas_concluidas' => $reservasConcluidas,
        'reservas_ativas' => $reservasAtivas
    ]);

} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
?>