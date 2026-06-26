<?php

session_start();
header('Content-Type: application/json');

// Usuário precisa estar logado para favoritar
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Você precisa estar logado para favoritar.']);
    exit;
}

require_once 'conexao.php';

// Pega os dados que o JavaScript vai mandar
$dados = json_decode(file_get_contents("php://input"), true);
$idFuncionario = $dados['id_funcionario'] ?? null;
$usuarioId = $_SESSION['usuario_id'];

if (!$idFuncionario) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Funcionário não identificado.']);
    exit;
}

try {
    // Verifica se o usuário já favoritou esse funcionário
    $stmtBusca = $pdo->prepare("SELECT id FROM favoritos WHERE usuario_id = :u_id AND funcionario_id = :f_id");
    $stmtBusca->execute([':u_id' => $usuarioId, ':f_id' => $idFuncionario]);
    $existe = $stmtBusca->fetch();

    if ($existe) {
        // Se achou, o usuário quer DESFAVORITAR (Apagar a estrela)
        $stmtDel = $pdo->prepare("DELETE FROM favoritos WHERE usuario_id = :u_id AND funcionario_id = :f_id");
        $stmtDel->execute([':u_id' => $usuarioId, ':f_id' => $idFuncionario]);
        
        echo json_encode(['sucesso' => true, 'acao' => 'removido']);
    } else {
        // Se não achou, o usuário quer FAVORITAR (Acender a estrela)
        $stmtIns = $pdo->prepare("INSERT INTO favoritos (usuario_id, funcionario_id) VALUES (:u_id, :f_id)");
        $stmtIns->execute([':u_id' => $usuarioId, ':f_id' => $idFuncionario]);
        
        echo json_encode(['sucesso' => true, 'acao' => 'adicionado']);
    }

} catch (PDOException $e) {
    // Erro no servidor, retorna mensagem de erro
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>