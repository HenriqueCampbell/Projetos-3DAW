<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $jsonBruto = file_get_contents('php://input');
        $dados = json_decode($jsonBruto, true);
        $idExcluir = $dados['id'];

        $caminhoJson = "../banco-json/perguntasSub.json";

        if (file_exists($caminhoJson)) {
            $perguntas = json_decode(file_get_contents($caminhoJson), true);

            $perguntasFiltradas = array_filter($perguntas, function($p) use ($idExcluir) {
                return $p['id'] != $idExcluir;
            });

            // Reorganiza a numeração do array
            $perguntasFiltradas = array_values($perguntasFiltradas);

            // Salva o JSON atualizado
            file_put_contents($caminhoJson, json_encode($perguntasFiltradas, JSON_PRETTY_PRINT));
            
            echo json_encode(["status" => "sucesso", "mensagem" => "Pergunta excluída com sucesso!"]);
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
    <title>Confirmar Exclusão</title>
</head>
<body>

    <header>
        <h1 style="color: red;">Excluir Pergunta Subjetiva</h1>
        <p>Verifique os dados abaixo antes de confirmar a remoção definitiva.</p>
    </header>

    <main>
        <form id="formExcluirSub">
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

    <script src="../requisicoes-js/excluirPerguntasSub.js"></script>

</body>
</html>