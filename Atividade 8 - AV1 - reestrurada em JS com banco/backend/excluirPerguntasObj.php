<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$banco = "sistema_perguntas";
$usuarioDB = "root";
$senhaDB = "";

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

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $jsonBruto = file_get_contents('php://input');
        $dados = json_decode($jsonBruto, true);

        $sql = "DELETE FROM perguntas_objetivas WHERE id_pergunta = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $dados['id']]);

        echo json_encode(["status" => "sucesso", "mensagem" => "Pergunta excluída com sucesso!"]);
        exit;
    }

} catch(PDOException $e) {
    echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
    exit;
}
?>