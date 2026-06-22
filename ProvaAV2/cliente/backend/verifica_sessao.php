<?php

session_start();
header('Content-Type: application/json');

// Se o crachá de ID existir, o usuário está logado.
if (isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'logado' => true,
    ]);
} else {
    echo json_encode([
        'logado' => false
    ]);
}
?>