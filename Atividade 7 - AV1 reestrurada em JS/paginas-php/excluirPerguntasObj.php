<?php

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $jsonBruto = file_get_contents('php://input');
        $dados = json_decode($jsonBruto, true);
        $idExcluir = $dados['id'];

        $caminhoJson = "../banco-json/perguntasObj.json";

        if (file_exists($caminhoJson)) {
            $perguntas = json_decode(file_get_contents($caminhoJson), true);

            // Filtra o array, mantendo apenas as perguntas onde o ID é DIFERENTE do ID a ser excluído
            $perguntasFiltradas = array_filter($perguntas, function($p) use ($idExcluir) {
                return $p['id'] != $idExcluir;
            });

            // Reorganiza a numeração do array para evitar índices quebrados.
            $perguntasFiltradas = array_values($perguntasFiltradas);

            // Sobrescreve o arquivo com a nova lista (sem a pergunta excluída)
            file_put_contents($caminhoJson, json_encode($perguntasFiltradas, JSON_PRETTY_PRINT));
            
            echo json_encode(["status" => "sucesso", "mensagem" => "Pergunta excluída com sucesso!"]);
        }
        exit;
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

            // Procura a pergunta para preencher a tela de aviso
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
    <title>Confirmar Exclusão</title>
</head>
<body>

    <header>
        <h1 style="color: red;">Excluir Pergunta Objetiva</h1>
        <p>Verifique os dados abaixo antes de confirmar a remoção definitiva.</p>
    </header>

    <main>
        <form id="formExcluirObj">
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

    <script src="../requisicoes-js/excluirPerguntasObj.js"></script>

</body>
</html>