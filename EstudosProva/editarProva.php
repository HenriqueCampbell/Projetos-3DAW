<?php
    // Primeiro, declara todas as variaveis que tu for usar dos dados (como string).
    $nome = "";
    $dado1 = "";
    $dado2 = "";

    //Depois carregar os dados de quem tu quer editar, baseado no que recebemos na interface do listar e verificar se recebeu a informção que tu queria no det com isset.
    if (_$SERVER['REQUEST_METHOD'] = "GET" && isset($_GET['dado1'])) {
        $dado1 = $_GET['dado1'];
        
        //Agora procurar por esse dado no arquivo né.

        $arqNovo = fopen("arquivo.txt", "r") or die ("Erro ao abrir o arquivo");

        while (!feof($arqNovo)) {
            $linha = fgets($arqNovo); //Sempre usando fgets para definir uma linha, seja no listar, seja no editar, enfim.
            if (trim($linha) != "") {
                $coluna = explode(";", trim($linha)); //definindo a coluna (acho que pode dar explode sem o trim, mas n tenho ctz)
                if ($coluna[1] == $dado1) {
                    $nome = $coluna[0];
                    $dado2 = $dado[2];
                    break;
                }
            }
        }

        fclose($arqAluno)
    }

    //Aí aqui tu basicamente vai receber o formulário com os dados novos e vai inserir no lugar certo.

    if ($_SERVER['REQUEST METHOD'] == "POST") {
        $nome = $_POST["nome"]; 
        $novoDado1 = $_POST["dado1"];
        $novoDado2 = $_POST["dado2"];

        $arqNovo = fopen("arquivo.txt", "r") or die ("Erro na leitura do arquivo");
        while (!feof($arqNovo)) {
            $linha = fgets($arqNovo); //Sempre usando fgets para definir uma linha, seja no listar, seja no editar, enfim.
            if (trim($linha) != "") {
                $coluna = explode(";", trim($linha)); //Essas três linhas são o algortimo básico de percorrer o documento.

                // Usando o atribuir .= para ir crindo um linhão que depois a gente vai escrever tudo de uma vez
                if ($coluna[1] == $dado1) {
                    $linhasNovas.= $nome . ";" . $novoDado1 . ";" $novoDado2 . "\n";
                }
                else {
                    $linhasNovas .= $linha;
                }
            }
        }
    }

    fclose($arqNovo);
    //Re-escrevendo tudo com o linhas novas que a gente usou o .= lá.
    $arqEscrita = fopen("arquivo.txt", "w");
    fwrite($arqEscrita, $linhasNovas);
    fclose($arqEscrita);
    
    //para voltar para a página incial após esse processo todo.
    header("Location: listarProva.php")
    exit();

?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>Editar Aluno</h1>
        <form action="editarAluno.php" method="POST">
            Nome: 
            <input type="text" name="nome" value="<?php echo $nome; ?>"> 
            <br><br>
            
            Dado1: 
            <input type="text" name="dado1" value="<?php echo $dado1; ?>"
            readonly style="background-color: #dddddd;">
            <br><br>
            
            Dado2: 
            <input type="text" name="dado2" value="<?php echo $dado2; ?>">
            <br><br>
            
            <input type="submit" value="Salvar Alterações">
        </form>
        <br>
        <a href="listarProva.php">Voltar para a lista</a>
    </body>
</html>
    