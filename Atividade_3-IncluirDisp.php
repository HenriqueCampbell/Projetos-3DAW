<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST["nome"];
        $sigla = $_POST["sigla"];
        $peso = $_POST["peso"];

        if(!file_exists("disciplinas.txt")) {
            $arqNovo = fopen("disciplinas.txt", "w") or die("Erro ao criar o arquivo.");
            $linha = "nome;sigla;peso";
            fwrite($arqNovo, $linha);

            fclose($arqNovo);
        }

        $arqNovo = fopen("disciplinas.txt", "a") or die("Erro ao criar o arquivo.");
        $linha = $nome . ";" . $sigla . ";" . $peso .;

        fwrite($arqNovo, $linha);
        fclose($arqNovo);

        echo "Disciplina Incluída com Sucesso!"
        
    }
>

<!DOCTYPE html>
<html>
<head>
</head>

    <body>
        <h1> Incluir Nova Disciplina </h1>
            <form action = "Atividade_3-IncluirDisp.php" method = "POST">
                Nome: <input type="text" name="nome">
                <br><br>
                Sigla: <input type="text" name="sigla">
                <br><br>
                Peso: <input type="text" name="peso">
                <br><br>
                <input type="submit" value="Criar">

            </form>
    </body>
</html>