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
        $id = $_POST['id'];
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
    <title>Confirmar Exclusão</title>
</head>
<body>

    <header>
        <h1 style="color: red;">Excluir Pergunta Subjetiva</h1>
        <p>Verifique os dados abaixo antes de confirmar a remoção definitiva.</p>
    </header>

    <main>
        <form action="excluirPerguntasSub.php" method="POST">
            <fieldset style="background-color: #f9f9f9; border: 1px solid #ccc;">
                <legend>Dados da Questão</legend>

                <p>
                    <label>ID:</label><br>
                    <input type="text" name="id" value="<?php echo $id; ?>" 
                           readonly style="background-color: #dddddd; cursor: not-allowed;">
                </p>

                <p>
                    <label>Enunciado da Pergunta:</label><br>
                    <textarea rows="4" cols="60" readonly 
                              style="background-color: #dddddd; cursor: not-allowed; resize: none;"><?php echo $pergunta; ?></textarea>
                </p>

                <p>
                    <label>Modelo de Resposta:</label><br>
                    <textarea rows="3" cols="60" readonly 
                              style="background-color: #dddddd; cursor: not-allowed; resize: none;"><?php echo $resposta; ?></textarea>
                </p>
            </fieldset>

            <div style="background-color: #ffe6e6; padding: 15px; border-left: 5px solid red; margin: 20px 0;">
                <strong>⚠️ ATENÇÃO:</strong> Esta ação não poderá ser desfeita!
            </div>

            <button type="submit" style="background-color: #ff4d4d; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Excluir Pergunta
            </button>
        </form>
    </main>

    <hr>

    <nav>
        <p>
            <a href="listarPerguntasSub.php">⬅ Cancelar e voltar para a lista</a>
        </p>
    </nav>

</body>
</html>