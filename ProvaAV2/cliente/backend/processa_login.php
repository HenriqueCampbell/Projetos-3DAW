<?php


session_set_cookie_params([
    'path' => '/',
    'samesite' => 'Lax'
]);

session_start();

header('Content-Type: application/json');
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    try {
        // Colocando numa várival "loginUsuario" pois pode ser email ou username.
        $loginUsuario = trim($_POST['login_usuario']);
        $senhaDigitada = $_POST['senha'];

        // ==========================================
        // BUSCA NO BANCO DE DADOS
        // ==========================================

        $stmtBusca = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = :login OR username = :login");
        $stmtBusca->bindParam(':login', $loginUsuario);
        $stmtBusca->execute();
        $usuarioBanco = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        // ==========================================
        // VERIFICAÇÃO DE SENHA E RETORNO
        // ==========================================

        if ($usuarioBanco) {
            if (password_verify($senhaDigitada, $usuarioBanco['senha'])) {
                
                // Caso a senha esteja correta, guardamos o ID e o nome do usuário na sessão
                $_SESSION['usuario_id'] = $usuarioBanco['id'];
                $_SESSION['usuario_nome'] = $usuarioBanco['nome'];

                // Retorna um JSON de sucesso para o frontend (login.js)
                echo json_encode([
                    'sucesso' => true,
                    'mensagem' => 'Login efetuado com sucesso!'
                ]);
                exit; // Para a execução
            }
        }

        // Se chegou aqui, ou não achou o usuário/email ou a senha estava errada. Retorna um JSON de erro para o frontend (login.js).
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Usuário/E-mail ou senha incorretos.'
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro no servidor: ' . $e->getMessage()
        ]);
    }

} else {
    // Caso o método de requisição não seja POST, retornamos um JSON de erro. (Tentativa de acesso direto ao arquivo PHP)
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Acesso negado.'
    ]);
}
?>