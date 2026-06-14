<?php
// Libera o acesso para o Frontend se comunicar com esta API (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    // Recebe e transforma o JSON em array do PHP
    $jsonBruto = file_get_contents('php://input');
    $novaPergunta = json_decode($jsonBruto, true);
    
    // Dados de acesso ao MySQL local
    $host = "localhost";
    $banco = "sistema_perguntas"; 
    $usuarioDB = "root";
    $senhaDB = ""; 

    try {
        // Inicia a conexão com o banco de dados via PDO
        $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuarioDB, $senhaDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepara a query SQL
        $sql = "INSERT INTO perguntas_subjetivas (id_pergunta, enunciado, resposta_modelo) 
                VALUES (:id, :pergunta, :resposta)";
                
        $stmt = $pdo->prepare($sql);
        
        // Executa injetando as variáveis
        $stmt->execute([
            ':id' => $novaPergunta['id'],
            ':pergunta' => $novaPergunta['pergunta'],
            ':resposta' => $novaPergunta['resposta']
        ]);

        echo json_encode(["status" => "sucesso", "mensagem" => "Pergunta subjetiva criada com sucesso no banco de dados!"]);

    } catch(PDOException $e) {
        // Trata erro de ID duplicado na chave primária
        if ($e->getCode() == 23000) {
            echo json_encode(["status" => "erro", "mensagem" => "O ID dessa pergunta já está cadastrado!"]);
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Erro ao salvar no banco: " . $e->getMessage()]);
        }
    }
    
    exit; 
}
?>