<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
        $jsonBruto = file_get_contents('php://input');
        $novaPergunta = json_decode($jsonBruto, true);
        
        $caminhoJson = "../banco-json/perguntasSub.json";
        $perguntas = [];

        if (file_exists($caminhoJson)) {
            $conteudoArquivo = file_get_contents($caminhoJson);
            $perguntas = json_decode($conteudoArquivo, true) ?? [];
        }

        $perguntas[] = $novaPergunta;

        file_put_contents($caminhoJson, json_encode($perguntas, JSON_PRETTY_PRINT));
        
        echo json_encode(["status" => "sucesso", "mensagem" => "Pergunta Criada com Sucesso"]);
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta Subjetiva</title>
</head>
<body>

    <header>
        <h1>Criar Nova Pergunta Subjetiva</h1>
    </header>

    <main>
        <form action="criarPerguntasSub.php" method="POST">
            <fieldset>
                <legend>Dados da Pergunta</legend>

                <p>
                    <label for="id_field">ID da Pergunta:</label><br>
                    <input type="text" id="id_field" name="id" placeholder="01 até 99" required>
                </p>

                <p>
                    <label for="desc_field">Enunciado da Pergunta:</label><br>
                    <textarea id="desc_field" name="pergunta" rows="4" cols="50" placeholder="Digite aqui o enunciado da questão" required></textarea>
                </p>

                <p>
                    <label for="resp_field">Modelo de Resposta:</label><br>
                    <textarea id="resp_field" name="resposta" rows="3" cols="50" placeholder="Insira o modelo aqui"></textarea>
                </p>
            </fieldset>

            <br>
            <button type="submit">Salvar Pergunta</button>
            <button type="reset">Limpar Tudo</button>
        </form>
    </main>

    <hr>

    <nav>
        <p>
            <a href="../index.html">⬅ Retornar à página inicial</a>
        </p>
    </nav>

    <script src="../requisicoes-js/criarPerguntasSub.js"></script>

</body>
</html>