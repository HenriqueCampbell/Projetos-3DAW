<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$banco = "sistema_perguntas";
$usuarioDB = "root";
$senhaDB = "";

// Conexão com o banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuarioDB, $senhaDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
        $sql = "SELECT * FROM perguntas_objetivas WHERE id_pergunta = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_GET['id']]);
        $pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pergunta) {
            echo json_encode(["status" => "sucesso", "pergunta" => $pergunta]);
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Pergunta não encontrada"]);
        }
        exit;
    }

    // Lógica para atualizar a pergunta objetiva usando POST para receber os dados editados pelo frontend
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $jsonBruto = file_get_contents('php://input');
        $dadosEditados = json_decode($jsonBruto, true);

        $sql = "UPDATE perguntas_objetivas 
                SET enunciado = :pergunta, correta = :r1, incorreta1 = :r2, incorreta2 = :r3, incorreta3 = :r4 
                WHERE id_pergunta = :id";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':id' => $dadosEditados['id'],
            ':pergunta' => $dadosEditados['pergunta'],
            ':r1' => $dadosEditados['r1'],
            ':r2' => $dadosEditados['r2'],
            ':r3' => $dadosEditados['r3'],
            ':r4' => $dadosEditados['r4']
        ]);

        echo json_encode(["status" => "sucesso", "mensagem" => "Alteração salva com sucesso!"]);
        exit;
    }

    // Se a requisição não for GET ou POST, ou se faltar o ID para GET, retornamos um erro
} catch(PDOException $e) {
    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
    exit;
}
?>