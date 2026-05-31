<?php
    if (isset($_GET['api']) && $_GET['api'] == 'true') {
        
        $caminhoJson = "../banco-json/perguntasObj.json";
        
        if (file_exists($caminhoJson)) {
            echo file_get_contents($caminhoJson);
        } else {
            // Se não tiver arquivo (nenhuma cadastrada ainda), devolve uma lista vazia
            echo json_encode([]); 
        }
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Perguntas Objetivas</title>
</head>
<body>

    <header>
        <h1>Perguntas Objetivas Cadastradas</h1>
        <hr>
    </header>

    <main>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr bgcolor="#eeeeee">
                    <th>ID</th>
                    <th>Pergunta</th>
                    <th>Correta</th>
                    <th>Incorreta 1</th>
                    <th>Incorreta 2</th>
                    <th>Incorreta 3</th>
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

    <script src="../requisicoes-js/listarPerguntasObj.js"></script>

</body>
</html>