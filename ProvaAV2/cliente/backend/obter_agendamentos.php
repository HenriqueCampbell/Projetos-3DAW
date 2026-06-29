<?php
session_set_cookie_params(['path' => '/', 'samesite' => 'Lax']);
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Não autenticado']);
    exit;
}

require_once 'conexao.php';
$usuarioId = $_SESSION['usuario_id'];

try {
    // Busca os agendamentos ordenados pelos mais recentes
    $stmt = $pdo->prepare("
        SELECT id, data_hora, valor_total_centavos, forma_pagamento, status_reserva 
        FROM agendamentos 
        WHERE usuario_id = :uid 
        ORDER BY data_hora DESC
    ");
    $stmt->execute([':uid' => $usuarioId]);
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Para cada agendamento, vamos buscar os itens (serviços) vinculados
    foreach ($agendamentos as &$agendamento) {
        $stmtItens = $pdo->prepare("SELECT servico_id FROM agendamento_itens WHERE agendamento_id = :aid");
        $stmtItens->execute([':aid' => $agendamento['id']]);
        $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
        
        $agendamento['itens'] = $itens;
    }

    echo json_encode(['sucesso' => true, 'agendamentos' => $agendamentos]);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>