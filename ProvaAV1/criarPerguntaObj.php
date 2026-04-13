<?php
    if ($_SERVER['REQUEST METHOD'] == "POST") {
        $id = $_POST["id"];
        $pergunta = $_POST["pergunta"];
        $r1= $_POST["r1"];
        $r2= $_POST["r2"];
        $r3= $_POST["r3"];
        $r4= $_POST["r4"];

        if (!file_exists("perguntasObj.txt")) {
            $arqPerguntasObj = fopen("perguntasObj.txt", "w") or die ("Erro ao criar o arquivo");
            $linha = ("id;pergunta;r1;r2;r3;r4");
            fwrite($arqPerguntasObj, $linha)
            fclose($arqPerguntasObj);
        }

        $arqPerguntasObj = fopen("perguntasObj.txt", "a") or die ("Erro na leitura do arquivo");
        $linha = $id . ";" . $pergunta . ";" . $r1 . ";" . $r2 . ";" . $r3 . ";" . $r4 "\n";
        fwrite($arqPerguntasObj, $linha);
        fclose($arqPerguntasObj);
        echo "Pergunta Criada com Sucesso"
    }
?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1> Criar Nova Pergunta Obejtiva</h1>
        <form action = "criarPerguntaObj.php" method = "POST">
            id: <input type ="text" name="id"> 
            <br>
            Pergunta Objetiva: <input type ="text" name="pergunta">
            <br>
            Alternativa Correta: <input type = "text" name="r1">
            <br>
            Alternativa Incorreta 1: <input type = "text" name="r2">
            <br>
            Alternativa Incorreta 2: <input type = "text" name="r3">
            <br>
            Alternativa Incorreta 3: <input type = "text" name="r4">
            <br>
            <input type = "submit" value="Enviar">
        </form>
        <p>
            <a href="index.php">Retornar a página inicial</a>
        </p>
    </body>
</html>
