<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_POST["id"];
        $pergunta = $_POST["pergunta"];
        $resposta = $_POST["resposta"];

        if (!file_exists("perguntasSub.txt")) {
            $arqPerguntasSub = fopen("perguntasSub.txt", "w") or die ("Erro ao criar o arquivo");
            $linha = ("id;pergunta;resposta\n");
            fwrite($arqPerguntasSub, $linha);
            fclose($arqPerguntasSub);
        }

        $arqPerguntasSub = fopen("perguntasSub.txt", "a") or die ("Erro na leitura do arquivo");
        $linha = $id . ";" . $pergunta . ";" . $resposta . "\n";
        fwrite($arqPerguntasSub, $linha);
        fclose($arqPerguntasSub);
        echo "Pergunta Criada com Sucesso";
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
            <a href="index.php">⬅ Retornar à página inicial</a>
        </p>
    </nav>

</body>
</html>
