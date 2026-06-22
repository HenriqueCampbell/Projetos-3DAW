<?php

$host = 'localhost';
$dbname = 'clinica-estetica'; // Nome do banco de dados criado no MySQL.
$usuario = 'root'; // Usuário padrão do XAMPP/WAMP
$senha = ''; // Senha padrão do XAMPP/WAMP (vazia)

try {
    // Criando a conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha);
    
    // Configurando para o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>