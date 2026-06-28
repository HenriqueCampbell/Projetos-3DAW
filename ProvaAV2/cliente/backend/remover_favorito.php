<?php
session_set_cookie_params([
    'path' => '/',
    'samesite' => 'Lax'
]);
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || !isset($_POST['funcionario_id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Requisição inválida']);
    exit;
}

require_once 'conexao.php';
$usuarioId = $_SESSION['usuario_id'];
$funcionarioId = $_POST['funcionario_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM favoritos WHERE usuario_id = :usuario_id AND funcionario_id = :funcionario_id");
    $stmt->execute([
        ':usuario_id' => $usuarioId,
        ':funcionario_id' => $funcionarioId
    ]);

    echo json_encode(['sucesso' => true]);
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
?>