<?php
session_start();
header('Content-Type: application/json');

require_once 'conexao.php';

// Se o usuário não estiver logado, retorna um array vazio
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

try {
    $stmt = $pdo->prepare("SELECT funcionario_id FROM favoritos WHERE usuario_id = :u_id");
    $stmt->execute([':u_id' => $usuarioId]);
    
    $favoritos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode($favoritos);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>