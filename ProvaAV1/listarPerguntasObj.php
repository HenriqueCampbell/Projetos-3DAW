<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1> Perguntas Objetivas Cadastradas </h1>
        <br>
        <table>
            <tr><th>ID</th><th>Pergunta</th><th>Alternativa Correta</th><th>Alternativa Incorreta 1</th><th>Alternativa Incorreta 2</th><th>Alternativa Incorreta 3</th><th>Edição</th><th>Exclusão</th></tr>
            <?php
                $arqPerguntasObj = fopen("perguntasObj.txt", "r") or die("Erro na leitura do arquivo"); 
                $primeiraLinha = true; 

                while(!feof($arqPerguntasObj)) { 
                    $linha = fgets($arqPerguntasObj);

                    if ($primeiraLinha) {
                        $primeiraLinha = false;
                        continue; 
                    }

                    if (trim($linha) != "") { 
                        $coluna = explode(";", $linha); 
                        echo "<tr><td>" . $coluna[0] . "</td><td>" . $coluna[1] . "</td><td>" . $coluna[2] . "</td><td>".   $coluna[3] . "</td><td>" . $coluna[4] . "</td><td>" . $coluna[5] . 
                        "</td><td>  <a href='editarPerguntasObj.php?id=" . $coluna[0] . "'>Editar</a></td>" .
                        "<td>  <a href='excluirPerguntasObj.php?id=" . $coluna[0] . "'>Excluir</a></td></tr>";
                    }

                }

                fclose($arqPerguntasObj);
                echo "Leitura realizada com sucesso.";
            ?>
        </table>
        <p>
         Clique <a href="index.php">aqui</a> para ir para a página de inicial. 
        </p>

    </body>
</html>


