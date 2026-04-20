<?php
    $id = "";
    $pergunta = "";
    $r1 = "";
    $r2 = "";
    $r3 = "";
    $r4 = "";

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $arqPerguntasObj = fopen("perguntasObj.txt", "r") or die ("Erro ao abrir o arquivo");

        while (!feof($arqPerguntasObj)) {
            $linha = fgets($arqPerguntasObj);
            if (trim($linha) != "") {
                $coluna = explode(";", trim($linha));
                if ($coluna[0] == $id) {
                    $pergunta = $coluna[1];
                    $r1 = $coluna[2];
                    $r2 = $coluna[3];
                    $r3 = $coluna[4];
                    $r4 = $coluna[5];
                    break;
                }
            }
        }

        fclose($arqPerguntasObj);
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_POST['id'];
        $linhasNovas = "";

        $arqPerguntasObj = fopen("perguntasObj.txt", "r") or die ("Erro na leitura do arquivo");
        while (!feof($arqPerguntasObj)) {
            $linha = fgets($arqPerguntasObj);
            if (trim($linha) != "") {
                $coluna = explode(";", trim($linha));

                if ($coluna[0] == $id) {
                    continue;
                }
                else {
                    $linhasNovas .= $linha;
                }
            }
        }

        fclose($arqPerguntasObj);
        $arqEscrita = fopen("perguntasObj.txt", "w");
        fwrite($arqEscrita, $linhasNovas);
        fclose($arqEscrita);
    
        header("Location: listarPerguntasObj.php");
        exit();
    }

?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>Excluir Pergunta Objetiva</h1>
        <form action="excluirPerguntasObj.php" method="POST">
            ID: 
            <input type="text" name="id" value="<?php echo $id; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>
            
            Pergunta: 
            <input type="text" name="pergunta" value="<?php echo $pergunta; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>
            
            Alternativa Correta: 
            <input type="text" name="r1" value="<?php echo $r1; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>

            Alternativa Incorreta 1: 
            <input type="text" name="r2" value="<?php echo $r2; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>

            Alternativa Incorreta 2: 
            <input type="text" name="r3" value="<?php echo $r3; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>

            Alternativa Incorreta 3: 
            <input type="text" name="r4" value="<?php echo $r4; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>
            
            <input type="submit" value="Excluir Pergunta">
            <p> Atenção essa ação não poderá ser desfeita! </p>
        </form>
        <br>
        <a href="listarPerguntasObj.php">Voltar para a lista</a>
    </body>
</html>
