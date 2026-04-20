<?php
    $id = "";
    $pergunta = "";
    $resposta = "";

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
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

        fclose($arqPerguntasSub);
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $novaPergunta = $_POST["pergunta"]; 
        $novaResposta = $_POST["resposta"];
        $id = $_POST["id"];
        $linhasNovas = "";

        $arqPerguntasSub = fopen("perguntasSub.txt", "r") or die ("Erro na leitura do arquivo");
        while (!feof($arqPerguntasSub)) {
            $linha = fgets($arqPerguntasSub);
            if (trim($linha) != "") {
                $coluna = explode(";", trim($linha));

                if ($coluna[0] == $id) {
                    $linhasNovas.= $id . ";" . $novaPergunta . ";" . $novaResposta . "\n";
                }
                else {
                    $linhasNovas .= $linha;
                }
            }
        }

        fclose($arqPerguntasSub);
        $arqEscrita = fopen("perguntasSub.txt", "w");
        fwrite($arqEscrita, $linhasNovas);
        fclose($arqEscrita);
    
        header("Location: listarPerguntasSub.php");
        exit();
    }

?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>Editar Pergunta Subjetiva </h1>
        <form action="editarPerguntasSub.php" method="POST">
            ID: 
            <input type="text" name="id" value="<?php echo $id; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>
            
            Pergunta: 
            <input type="text" name="pergunta" value="<?php echo $pergunta; ?>">
            <br><br>
            
            Modelo de Resposta: 
            <input type="text" name="resposta" value="<?php echo $resposta; ?>">
            <br><br>
            
            <input type="submit" value="Salvar Alterações">
        </form>
        <br>
        <a href="listarPerguntasSub.php">Voltar para a lista</a>
    </body>
</html>
