<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$banco = "sistema_perguntas";
$usuarioDB = "root";
$senhaDB = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuarioDB, $senhaDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM perguntas_subjetivas";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($perguntas);

} catch(PDOException $e) {
    echo json_encode([]);
}
?>