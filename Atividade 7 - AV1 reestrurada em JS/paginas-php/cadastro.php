<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
        // Recebe o JSON do JavaScript
        $jsonBruto = file_get_contents('php://input');
        $novoUsuario = json_decode($jsonBruto, true);
        
        $caminhoJson = "../banco-json/usuarios.json";
        $usuarios = []; // Array vazio para garantir que a variável existe mesmo que o arquivo .json ainda não exista ou esteja vazio

        // Verifica se o arquivo .json existe e, se existir, lê seu conteúdo e decodifica para um array associativo
        if (file_exists($caminhoJson)) {
            $conteudoArquivo = file_get_contents($caminhoJson);
            $usuarios = json_decode($conteudoArquivo, true) ?? [];
        }

        $usuarios[] = $novoUsuario;

        file_put_contents($caminhoJson, json_encode($usuarios, JSON_PRETTY_PRINT));
        
        echo json_encode(["status" => "sucesso", "mensagem" => "Cadastro concluído com sucesso."]);
        exit; 
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
</head>
<body>

    <header>
        <h1>Novo Cadastro de Usuário</h1>
        <p>Preencha os campos abaixo para registrar um novo perfil.</p>
    </header>

    <main>
        <form action="cadastro.php" method="POST">
            <fieldset>
                <legend>Dados Pessoais</legend>

                <p>
                    <label for="id_nome">Nome:</label><br>
                    <input type="text" id="id_nome" name="nome" placeholder="Ex: João Silva" required>
                </p>

                <p>
                    <label for="id_email">E-mail:</label><br>
                    <input type="email" id="id_email" name="email" placeholder="nome@exemplo.com" required>
                </p>

                <p>
                    <label for="id_tel">Telefone:</label><br>
                    <input type="tel" id="id_tel" name="telefone" placeholder="(21) 99999-9999">
                </p>

                <button type="submit">Finalizar Cadastro</button>
                <button type="reset">Limpar Campos</button>
            </fieldset>
        </form>
    </main>

    <nav>
        <p>
            <a href="../index.html">⬅ Voltar para o Menu Principal</a>
        </p>
    </nav>

    <script src="../requisicoes-js/cadastro.js"></script>
</body>
</html>