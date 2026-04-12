<?php

    //Esse if tem que englobar o código "todo" pois ele só deve rodar se tiver recebendo algum dado da página de formulário.
    if ($_SERVER['REQUEST METHOD'] == "POST") {//Lembre que tem que chamar o $_SERVER que é uma ARRAY global e 'REQUEST METHOD' vai ser tipo um char e o "POST" tipo uma string... 
        $nome = $_POST["nome"]; //Ajuda a lembrar que $_SERVER e $_POST são ambas arrays globais então isso ajuda a lembrar da sintaxe.
        $dado1 = $_POST["dado1"];
        $dado2 = $_POST["dado2"];

        //Agora tem que ver se o arquivo que queremos escrever já existe ou não pois se não existe temos que criar ele e sua estrutura básica dentro do if.

        if (!file_exists("arquivo.txt")) {
            $arqNovo = fopen("arquivo.txt", "w") or die ("Erro ao criar o arquivo"); //fopen sempre atribuindo a uma variavel que vai sr usada para a manipulação do arquivo.
            $linha = ("nome;dado1;dado2");
            fwrite($arqNovo, $linha) // Nossos fwrites serão todos assim, antes colocando a string numa variável para depois colocalas no documento com fwrite.
            fclose($arqNovo);
        }

        // Agora tem que ter a parte de apendar os dados novos que vamos inserir via formulário.

        $arqNovo = fopen("arquivo.txt", "a") or die ("Erro na leitura do arquivo");
        $linha = $nome . ";" . $dado1 . ";" . $dado2 . \n; // Criando a linha sempre com o . que é tipo o + do java. Não esquece do \n no final.
        fwrite($arqNovo, $linha);
        fclose($arqNovo);
        echo "Inclusão Concluída com Sucesso." //Echo sem parenteses por algum motivo de php.

    }
?>

<!DOCTYPE html> //Lembra do DOCTYPE maiusculo e com "!" na frente e html minusculo
<html> //Não esquece dessa tag pelo amor de deus.
    <head>  // head não precisa ter nada, só abrir e fechar.
    </head>
    <body>
        <h1> Incluir Algo </h1> //Tamanho maior de título em html. Vai de h1 até h6.
        <form action = "incluirProva.php" method = "POST"> //Começar o form seguido de action = "nome-da-pagina" method = "POST">
                //Aqui pra fazer as caixinhas do forms, lembre que 1. É só jogar o título da caixinha pra fora. 2. Cada caixinha precisa de uma tag input com type e name (que vai ser lido pelo php) dentro dela.
            Nome: <input type ="text" name="nome"> 
            <br>
            Dado1: <input type ="text" name="dado1">
            <br>
            Dado2: <input type = "text" name="dado2">
            <br>
            //Esse último aqui do botão enviar é tipo Submit e o texto dele tu coloca no campo value.
            <input type = "submit" value="Enviar">
        </form>
    </body>
</html>