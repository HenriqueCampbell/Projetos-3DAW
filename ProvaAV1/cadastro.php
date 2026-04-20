<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];
    

        if (!file_exists("usuarios.txt")) {
            $arqUsuarios = fopen("usuarios.txt", "w") or die ("Erro ao criar o arquivo");
            $linha = ("nome;email;telefone\n");
            fwrite($arqUsuarios, $linha);
            fclose($arqUsuarios);
        }

        $arqUsuarios = fopen("usuarios.txt", "a") or die ("Erro na leitura do arquivo");
        $linha = $nome . ";" . $email . ";" . $telefone . "\n";
        fwrite($arqUsuarios, $linha);
        fclose($arqUsuarios);
        echo "Cadastro concluído com sucesso.";
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
            <a href="index.php">⬅ Voltar para o Menu Principal</a>
        </p>
    </nav>

</body>
</html>
    
