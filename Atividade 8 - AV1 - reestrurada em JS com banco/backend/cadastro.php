<?php
// Cabeçalhos de CORS configurados para aceitar a comunicação do seu frontend
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    // Captura o JSON enviado pelo JavaScript
    $jsonBruto = file_get_contents('php://input');
    $novoUsuario = json_decode($jsonBruto, true);
    
    // Credenciais padrões do banco no XAMPP
    $host = "localhost";
    $banco = "sistema_perguntas"; 
    $usuarioDB = "root";
    $senhaDB = ""; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuarioDB, $senhaDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query SQL pronta para salvar na tabela de usuários
        $sql = "INSERT INTO usuarios (nome, email, telefone) VALUES (:nome, :email, :telefone)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':nome' => $novoUsuario['nome'],
            ':email' => $novoUsuario['email'],
            ':telefone' => $novoUsuario['telefone']
        ]);

        echo json_encode(["status" => "sucesso", "mensagem" => "Cadastro concluído com sucesso no banco SQL!"]);

    } catch(PDOException $e) {
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao salvar no banco: " . $e->getMessage()]);
    }
    
    exit; 
}
?>