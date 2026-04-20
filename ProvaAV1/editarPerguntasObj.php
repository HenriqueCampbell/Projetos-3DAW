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
        $novaPergunta = $_POST["pergunta"]; 
        $novaR1 = $_POST["r1"];
        $novaR2 = $_POST["r2"];
        $novaR3 = $_POST["r3"];
        $novaR4 = $_POST["r4"];
        $id = $_POST["id"];
        $linhasNovas = "";

        $arqPerguntasObj = fopen("perguntasObj.txt", "r") or die ("Erro na leitura do arquivo");
        while (!feof($arqPerguntasObj)) {
            $linha = fgets($arqPerguntasObj);
            if (trim($linha) != "") {
                $coluna = explode(";", trim($linha));

                if ($coluna[0] == $id) {
                    $linhasNovas.= $id . ";" . $novaPergunta . ";" . $novaR1 . ";" . $novaR2 . ";" . $novaR3 . ";" . $novaR4 . "\n";
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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Pergunta Objetiva</title>
</head>
<body>

    <header>
        <h1>Editar Pergunta Objetiva</h1>
        <p>Altere os campos necessários.</p>
    </header>

    <main>
        <form action="editarPerguntasObj.php" method="POST">
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
                    <textarea id="pergunta_edit" name="pergunta" rows="3" cols="50" style="resize: none;" required><?php echo $pergunta; ?></textarea>
                </p>
            </fieldset>

            <br>

            <fieldset>
                <legend>Alternativas</legend>
                <p>
                    <label for="r1_edit"><strong>Alternativa Correta:</strong></label><br>
                    <input type="text" id="r1_edit" name="r1" value="<?php echo $r1; ?>" size="40" required>
                </p>

                <p>
                    <label for="r2_edit">Alternativa Incorreta 1:</label><br>
                    <input type="text" id="id_r2" name="r2" value="<?php echo $r2; ?>" size="40">
                </p>

                <p>
                    <label for="r3_edit">Alternativa Incorreta 2:</label><br>
                    <input type="text" id="id_r3" name="r3" value="<?php echo $r3; ?>" size="40">
                </p>

                <p>
                    <label for="r4_edit">Alternativa Incorreta 3:</label><br>
                    <input type="text" id="id_r4" name="r4" value="<?php echo $r4; ?>" size="40">
                </p>
            </fieldset>

            <br>
            <button type="submit">💾 Salvar Alterações</button>

        </form>
    </main>

    <hr>

    <nav>
        <a href="listarPerguntasObj.php">⬅ Voltar para a lista</a>
    </nav>

</body>
</html>