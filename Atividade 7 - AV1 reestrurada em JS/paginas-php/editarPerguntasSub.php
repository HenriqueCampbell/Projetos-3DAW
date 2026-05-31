<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $jsonBruto = file_get_contents('php://input');
        $dadosEditados = json_decode($jsonBruto, true);
        
        $caminhoJson = "../banco-json/perguntasSub.json";
        
        if (file_exists($caminhoJson)) {
            $perguntas = json_decode(file_get_contents($caminhoJson), true);
            
            // Procura a pergunta pelo ID e substitui
            foreach ($perguntas as $indice => $p) {
                if ($p['id'] == $dadosEditados['id']) {
                    $perguntas[$indice] = $dadosEditados;
                    break;
                }
            }

            file_put_contents($caminhoJson, json_encode($perguntas, JSON_PRETTY_PRINT));
            echo json_encode(["status" => "sucesso", "mensagem" => "Alteração salva com sucesso!"]);
        }
        exit;
    }

    $id = "";
    $pergunta = "";
    $resposta = "";

    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
        $id_buscado = $_GET['id'];
        $caminhoJson = "../banco-json/perguntasSub.json";

        if (file_exists($caminhoJson)) {
            $perguntas = json_decode(file_get_contents($caminhoJson), true);
            
            foreach ($perguntas as $p) {
                if ($p['id'] == $id_buscado) {
                    $id = $p['id'];
                    $pergunta = $p['pergunta'];
                    $resposta = $p['resposta'];
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
    <title>Editar Pergunta Subjetiva</title>
</head>
<body>

    <header>
        <h1>Editar Pergunta Subjetiva</h1>
        <p>Altere os campos necessários.</p>
    </header>

    <main>
        <form id="formEditarSub">
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

    <script src="../requisicoes-js/editarPerguntasSub.js"></script>

</body>
</html>