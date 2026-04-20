<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_POST["id"];
        $pergunta = $_POST["pergunta"];
        $r1= $_POST["r1"];
        $r2= $_POST["r2"];
        $r3= $_POST["r3"];
        $r4= $_POST["r4"];

        if (!file_exists("perguntasObj.txt")) {
            $arqPerguntasObj = fopen("perguntasObj.txt", "w") or die ("Erro ao criar o arquivo");
            $linha = ("id;pergunta;r1;r2;r3;r4\n");
            fwrite($arqPerguntasObj, $linha);
            fclose($arqPerguntasObj);
        }

        $arqPerguntasObj = fopen("perguntasObj.txt", "a") or die ("Erro na leitura do arquivo");
        $linha = $id . ";" . $pergunta . ";" . $r1 . ";" . $r2 . ";" . $r3 . ";" . $r4 . "\n";
        fwrite($arqPerguntasObj, $linha);
        fclose($arqPerguntasObj);
        echo "Pergunta Criada com Sucesso";
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
        <a href="index.php">⬅ Voltar para o Menu Principal</a>
    </nav>

</body>
</html>
