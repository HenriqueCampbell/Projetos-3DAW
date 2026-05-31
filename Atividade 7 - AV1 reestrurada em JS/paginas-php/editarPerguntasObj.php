<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $jsonBruto = file_get_contents('php://input');
        $dadosEditados = json_decode($jsonBruto, true);
        
        $caminhoJson = "../banco-json/perguntasObj.json";
        
        if (file_exists($caminhoJson)) {
            $perguntas = json_decode(file_get_contents($caminhoJson), true);
            
            // Procura a pergunta pelo ID no array e atualiza
            foreach ($perguntas as $indice => $p) {
                if ($p['id'] == $dadosEditados['id']) {
                    $perguntas[$indice] = $dadosEditados; // Substitui pela versão nova
                    break;
                }
            }
            
            file_put_contents($caminhoJson, json_encode($perguntas, JSON_PRETTY_PRINT));
            echo json_encode(["status" => "sucesso", "mensagem" => "Alteração salva com sucesso!"]);
        }
        exit; // Para a execução do fetch.
    }

    $id = "";
    $pergunta = "";
    $r1 = "";
    $r2 = "";
    $r3 = "";
    $r4 = "";

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
        $id_buscado = $_GET['id'];
        $caminhoJson = "../banco-json/perguntasObj.json";

        if (file_exists($caminhoJson)) {
            $perguntas = json_decode(file_get_contents($caminhoJson), true);
            
            // Procura a pergunta para preencher as variáveis do HTML
            foreach ($perguntas as $p) {
                if ($p['id'] == $id_buscado) {
                    $id = $p['id'];
                    $pergunta = $p['pergunta'];
                    $r1 = $p['r1'];
                    $r2 = $p['r2'];
                    $r3 = $p['r3'];
                    $r4 = $p['r4'];
                    break;
                }
            }
        }
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
        <form id="formEditarObj">
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
                    <label for="id_r2">Alternativa Incorreta 1:</label><br>
                    <input type="text" id="id_r2" name="r2" value="<?php echo $r2; ?>" size="40">
                </p>

                <p>
                    <label for="id_r3">Alternativa Incorreta 2:</label><br>
                    <input type="text" id="id_r3" name="r3" value="<?php echo $r3; ?>" size="40">
                </p>

                <p>
                    <label for="id_r4">Alternativa Incorreta 3:</label><br>
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

    <script src="../requisicoes-js/editarPerguntasObj.js"></script>

</body>
</html>