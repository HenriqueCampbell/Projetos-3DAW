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
<html>
    <head>
    </head>
    <body>
        <h1> Criar Nova Pergunta </h1>
        <form action = "criarPerguntasSub.php" method = "POST">
            id: <input type ="text" name="id"> 
            <br>
            Pergunta Subjetiva: <input type ="text" name="pergunta">
            <br>
            Modelo de Resposta: <input type = "text" name="resposta">
            <br>
            <input type = "submit" value="Enviar">
        </form>
        <p>
            <a href="index.php">Retornar a página inicial</a>
        </p>
    </body>
</html>
