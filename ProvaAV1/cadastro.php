<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];
    

        if (!file_exists("usuarios.txt")) {
            $arqUsuarios = fopen("usuarios.txt", "w") or die ("Erro ao criar o arquivo");
            $linha = ("nome;email;telefone");
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
<html>
    <head>
    </head>
    <body>
        <h1> Novo Cadastro de Usuário </h1>
        <form action = "cadastro.php" method = "POST">
            Nome: <input type ="text" name="nome"> 
            <br>
            E-mail: <input type ="text" name="email">
            <br>
            Telefone: <input type = "text" name="telefone">
            <br>
            <input type = "submit" value="Enviar">
        </form>
        <p>
            <a href="index.php">Retornar a página inicial</a>
        </p>
    </body>
</html>
    
