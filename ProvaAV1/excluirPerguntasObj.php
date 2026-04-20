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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Exclusão</title>
</head>
<body>

    <header>
        <h1 style="color: red;">Excluir Pergunta Objetiva</h1>
        <p>Verifique os dados abaixo antes de confirmar a remoção definitiva.</p>
    </header>

    <main>
        <form action="excluirPerguntasObj.php" method="POST">
            <fieldset style="background-color: #f9f9f9; border: 1px solid #ccc;">
                <legend>Dados da Pergunta</legend>

                <p>
                    <label>ID:</label><br>
                    <input type="text" name="id" value="<?php echo $id; ?>" readonly style="background-color: #dddddd; cursor: not-allowed;">
                </p>

                <p>
                    <label>Enunciado:</label><br>
                    <textarea rows="3" cols="50" readonly style="background-color: #dddddd; cursor: not-allowed; resize: none;"><?php echo $pergunta; ?></textarea>
                    <input type="hidden" name="pergunta" value="<?php echo $pergunta; ?>">
                </p>

                <p>
                    <label>Alternativas (Correta / Incorretas):</label><br>
                    <input type="text" name="r1" value="<?php echo $r1; ?>" readonly style="background-color: #dddddd; margin-bottom: 5px;"><br>
                    <input type="text" name="r2" value="<?php echo $r2; ?>" readonly style="background-color: #dddddd; margin-bottom: 5px;"><br>
                    <input type="text" name="r3" value="<?php echo $r3; ?>" readonly style="background-color: #dddddd; margin-bottom: 5px;"><br>
                    <input type="text" name="r4" value="<?php echo $r4; ?>" readonly style="background-color: #dddddd;">
                </p>
            </fieldset>

            <div style="background-color: #ffe6e6; padding: 10px; border-left: 5px solid red; margin: 20px 0;">
                <strong>⚠️ ATENÇÃO:</strong> Esta ação não poderá ser desfeita!
            </div>

            <button type="submit" style="background-color: #ff4d4d; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Excluir Pergunta
            </button>
        </form>
    </main>

    <hr>

    <nav>
        <a href="listarPerguntasObj.php">⬅ Cancelar e Voltar para a lista</a>
    </nav>

</body>
</html>