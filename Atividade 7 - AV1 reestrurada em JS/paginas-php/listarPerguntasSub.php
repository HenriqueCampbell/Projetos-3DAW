<?php
    // Verifica se o JavaScript está pedindo os dados com ?api=true
    if (isset($_GET['api']) && $_GET['api'] == 'true') {
        
        $caminhoJson = "../banco-json/perguntasSub.json";
        
        // Se o arquivo existir, lê e devolve o JSON direto
        if (file_exists($caminhoJson)) {
            echo file_get_contents($caminhoJson);
        } else {
            // Se não existir, devolve um array vazio
            echo json_encode([]); 
        }
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Perguntas Subjetivas</title>
</head>
<body>

    <header>
        <h1>Perguntas Subjetivas Cadastradas</h1>
        <hr>
    </header>

    <main>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr bgcolor="#eeeeee">
                    <th>ID</th>
                    <th>Pergunta</th>
                    <th>Modelo de Resposta</th>
                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody id="corpo-tabela">
            </tbody>
        </table>
        <p id="status-msg"><strong>Status:</strong> Carregando dados...</p>
    </main>

    <hr>

    <nav>
        <p>
            <a href="../index.html">⬅ Voltar para o Menu Principal</a>
        </p>
    </nav>

    <script src="../requisicoes-js/listarPerguntasSub.js"></script>

</body>
</html>