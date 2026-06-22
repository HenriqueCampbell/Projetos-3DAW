<?php
// O navegador vai entender que o que ele vai receber é um JSON (não é HTML, nem texto puro)
header('Content-Type: application/json');

// Requer o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    try {
        $nome = trim($_POST['nome']);
        $sobrenome = trim($_POST['sobrenome']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']); 
        $telefone = trim($_POST['telefone']);
        $senha = $_POST['senha'];

        // ==========================================
        // VALIDAÇÃO DE EXISTÊNCIA
        // ==========================================
        
        // Uso do prepare() para evitar SQL Injection
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email OR username = :username");
        $stmtCheck->bindParam(':email', $email);
        $stmtCheck->bindParam(':username', $username);
        $stmtCheck->execute();

        // Se rowCount() for maior que zero, significa que achou alguém com esse email ou username
        if ($stmtCheck->rowCount() > 0) {
            
            // Enviamos um JSON de erro para o frontend, dizendo que o email ou username já existe
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'O E-mail ou Nome de Usuário escolhido já está em uso.'
            ]);
            exit; // Para a execução do PHP aqui.
        }

        // ==========================================
        // CRIPTOGRAFIA E INSERÇÃO
        // ==========================================

        // Criptografando a senha antes de salvar no banco de dados
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Preparando a query de inserção com "etiquetas" (placeholders) para evitar SQL Injection
        $stmtInsert = $pdo->prepare("
            INSERT INTO usuarios (nome, sobrenome, email, username, telefone, senha) 
            VALUES (:nome, :sobrenome, :email, :username, :telefone, :senha)
        ");

        $stmtInsert->bindParam(':nome', $nome);
        $stmtInsert->bindParam(':sobrenome', $sobrenome);
        $stmtInsert->bindParam(':email', $email);
        $stmtInsert->bindParam(':username', $username);
        $stmtInsert->bindParam(':telefone', $telefone);
        $stmtInsert->bindParam(':senha', $senhaHash);
        $stmtInsert->execute();

        // Ao chegar aqui, significa que o cadastro foi bem-sucedido. Enviamos um JSON de sucesso para o frontend.
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Usuário cadastrado com sucesso!'
        ]);

    } catch (PDOException $e) {
        // Se houver algum erro no banco de dados, enviamos um JSON de erro para o frontend
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro no banco de dados: ' . $e->getMessage()
        ]);
    }

} else {
    // Se alguém tentar acessar esse arquivo digitando a URL no navegador (método GET)
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Acesso negado. Utilize o formulário.'
    ]);
}
?>