<?php
    $id = "";
    $pergunta = "";
    $resposta = "";

    if (_$SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $arqPerguntasSub = fopen("perguntasSub.txt", "r") or die ("Erro ao abrir o arquivo");

        while (!feof($arqPerguntasSub)) {
            $linha = fgets($arqPerguntasSub);
            if (trim($linha) != "") {
                $coluna = explode(";", trim($linha));
                if ($coluna[0] == $id) {
                    $pergunta = $coluna[1];
                    $resposta = $coluna[2];
                    break;
                }
            }
        }

        fclose($arqPerguntasSub)
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
        $linhasNovas = "";

        $arqPerguntasSub = fopen("perguntasSub.txt", "r") or die ("Erro na leitura do arquivo");
        while (!feof($arqPerguntasSub)) {
            $linha = fgets($arqPerguntasSub);
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
    }

    fclose($arqPerguntasSub);
    $arqEscrita = fopen("perguntasSub.txt", "w");
    fwrite($arqEscrita, $linhasNovas);
    fclose($arqEscrita);
    
    header("Location: listarPerguntasSub.php")
    exit();

?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>Excluir Pergunta Subjetiva </h1>
        <form action="editarPerguntaSub.php" method="POST">
            ID: 
            <input type="text" name="id" value="<?php echo $id; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>
            
            Pergunta: 
            <input type="text" name="pergunta" value="<?php echo $pergunta; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>
            
            Modelo de Resposta: 
            <input type="text" name="resposta" value="<?php echo $resposta; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>
            
            <input type="submit" value="Excluir Pergunta">
            <p> Atenção essa ação não poderá ser desfeita! </p>
        </form>
        <br>
        <a href="listarPerguntasSub.php">Voltar para a lista</a>
    </body>
</html>
