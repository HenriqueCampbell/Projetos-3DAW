<?php
// Libera o acesso para o Frontend se comunicar com esta API (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    // Recebe e decodifica o payload JSON enviado pelo fetch
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
        $sql = "INSERT INTO perguntas_objetivas (id_pergunta, enunciado, correta, incorreta1, incorreta2, incorreta3) 
                VALUES (:id, :pergunta, :r1, :r2, :r3, :r4)";
                
        $stmt = $pdo->prepare($sql);
        
        // Executa a query substituindo os parâmetros pelas variáveis reais
        $stmt->execute([
            ':id' => $novaPergunta['id'],
            ':pergunta' => $novaPergunta['pergunta'],
            ':r1' => $novaPergunta['r1'],
            ':r2' => $novaPergunta['r2'],
            ':r3' => $novaPergunta['r3'],
            ':r4' => $novaPergunta['r4']
        ]);

        // Retorna sucesso para o Frontend
        echo json_encode(["status" => "sucesso", "mensagem" => "Pergunta objetiva criada com sucesso no banco de dados!"]);

    } catch(PDOException $e) {
        // Verifica se o erro foi causado por tentar inserir um ID já existente (Primary Key duplicada)
        if ($e->getCode() == 23000) {
            echo json_encode(["status" => "erro", "mensagem" => "O ID dessa pergunta já está cadastrado!"]);
        } else {
            // Retorna qualquer outro erro genérico do banco
            echo json_encode(["status" => "erro", "mensagem" => "Erro ao salvar no banco: " . $e->getMessage()]);
        }
    }
    
    exit; 
}
?>