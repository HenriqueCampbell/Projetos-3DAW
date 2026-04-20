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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Pergunta Subjetiva</title>
</head>
<body>

    <header>
        <h1>Editar Pergunta Subjetiva</h1>
        <p>Altere os campos necessários.</p>
    </header>

    <main>
        <form action="editarPerguntasSub.php" method="POST">
            <fieldset>
                <legend>Identificação</legend>
                <p>
                    <label for="id_edit">ID da Questão (Não editável):</label><br>
                    <input type="text" id="id_edit" name="id" value="<?php echo $id; ?>" 
                           readonly style="background-color: #dddddd; cursor: not-allowed;">
                </p>
            </fieldset>

            <br>

            <fieldset>
                <legend>Conteúdo da Pergunta</legend>
                <p>
                    <label for="pergunta_edit">Enunciado:</label><br>
                    <textarea id="pergunta_edit" name="pergunta" rows="5" cols="60" style="resize: none;" required><?php echo $pergunta; ?></textarea>
                </p>

                <p>
                    <label for="resposta_edit">Modelo de Resposta:</label><br>
                    <textarea id="resposta_edit" name="resposta" rows="4" cols="60" style="resize: none;"><?php echo $resposta; ?></textarea>
                </p>
            </fieldset>

            <br>
            <button type="submit">💾 Salvar Alterações</button>
        </form>
    </main>

    <hr>

    <nav>
        <a href="listarPerguntasSub.php">⬅ Voltar para a lista</a>
    </nav>

</body>
</html>