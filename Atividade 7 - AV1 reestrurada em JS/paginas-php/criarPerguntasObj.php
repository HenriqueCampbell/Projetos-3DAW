<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
        // Recebe o JSON do JavaScript
        $jsonBruto = file_get_contents('php://input');
        $novaPergunta = json_decode($jsonBruto, true);
        
        // Define o caminho do banco JSON
        $caminhoJson = "../banco-json/perguntasObj.json";
        $perguntas = [];

        // Lê o arquivo se ele já existir
        if (file_exists($caminhoJson)) {
            $conteudoArquivo = file_get_contents($caminhoJson);
            $perguntas = json_decode($conteudoArquivo, true) ?? [];
        }

        // Adiciona a nova pergunta na lista
        $perguntas[] = $novaPergunta;

        // Salva de volta no arquivo
        file_put_contents($caminhoJson, json_encode($perguntas, JSON_PRETTY_PRINT));
        
        echo json_encode(["status" => "sucesso", "mensagem" => "Pergunta Criada com Sucesso"]);
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta Objetiva</title>
</head>
<body>

    <header>
        <h1>Criar Nova Pergunta Objetiva</h1>
    </header>

    <main>
        <form action="criarPerguntasObj.php" method="POST">
            <fieldset>
                <legend>Dados da Pergunta</legend>

                <p>
                    <label for="id_pergunta">ID da Pergunta:</label><br>
                    <input type="text" id="id_pergunta" name="id" placeholder="01 até 99" required>
                </p>

                <p>
                    <label for="txt_pergunta">Enunciado da Pergunta:</label><br>
                    <textarea id="txt_pergunta" name="pergunta" rows="3" cols="40" placeholder="Digite aqui o enunciado da questão" required></textarea>
                </p>
            </fieldset>

            <br>

            <fieldset>
                <legend>Alternativas</legend>

                <p>
                    <label for="id_r1"><strong>Alternativa Correta:</strong></label><br>
                    <input type="text" id="id_r1" name="r1" placeholder="Resposta que pontua" required size="40">
                </p>

                <p>
                    <label for="id_r2">Alternativa Incorreta 1:</label><br>
                    <input type="text" id="id_r2" name="r2" size="40">
                </p>

                <p>
                    <label for="id_r3">Alternativa Incorreta 2:</label><br>
                    <input type="text" id="id_r3" name="r3" size="40">
                </p>

                <p>
                    <label for="id_r4">Alternativa Incorreta 3:</label><br>
                    <input type="text" id="id_r4" name="r4" size="40">
                </p>
            </fieldset>

            <br>
            <button type="submit">Salvar Pergunta</button>
            <button type="reset">Limpar Tudo</button>
        </form>
    </main>

    <hr>

    <nav>
        <a href="../index.html">⬅ Voltar para o Menu Principal</a>
    </nav>

    <script src="../requisicoes-js/criarPerguntasObj.js"></script>

</body>
</html>