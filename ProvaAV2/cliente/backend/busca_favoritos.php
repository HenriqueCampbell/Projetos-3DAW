<?php

session_start();
header('Content-Type: application/json');

// Se não tiver logado, retorna uma lista vazia direto
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => true, 'favoritos' => []]);
    exit;
}

require_once 'conexao.php';
$usuarioId = $_SESSION['usuario_id'];

try {
    // Busca pela coluna do ID do funcionário
    $stmt = $pdo->prepare("SELECT funcionario_id FROM favoritos WHERE usuario_id = :u_id");
    $stmt->execute([':u_id' => $usuarioId]);
    
    // PDO::FETCH_COLUMN transforma o resultado em um array simples, ex: [1, 3, 4]
    $favoritos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'sucesso' => true,
        'favoritos' => $favoritos
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao carregar favoritos: ' . $e->getMessage()
    ]);
}
?>