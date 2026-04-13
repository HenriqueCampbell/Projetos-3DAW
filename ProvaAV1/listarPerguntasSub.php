<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1> Perguntas Subjetivas Cadastradas </h1>
        <br>
        <table>
            <tr><th>ID</th><th>Pergunta</th><th>Modelo de Resposta</th><th>Edição</th><th>Exclusão</th></tr>
            <?php
                $arqPerguntasSub = fopen("perguntasSub.txt", "r") or die("Erro na leitura do arquivo"); 
                $primeiraLinha = true; 

                while(!feof($arqPerguntasSub)) { 
                    $linha = fgets($arqPerguntasSub);

                    if ($primeiraLinha = true) {
                        $primeiraLinha = false
                        continue; 
                    }

                    if (trim($linha) != "") { 
                        $coluna = explode(";", $linha); 
                        echo "<tr><td>" . $coluna[0] . "</td><td>" . $coluna[1] . "</dh><td>" . $coluna[2] . 
                        "</td><td> . <a href='editarPerguntaSub.php?id=" . $coluna[0] . "'>Editar</a></td>" .
                        "<td> . <a href='excluirPerguntaSub.php'?id=" . $coluna[0] . "'>Excluir</a></td></tr>";
                    }

                }

                fclose($arqPerguntasSub);
                echo "Leitura realizada com sucesso."
            ?>
        </table>
        <p>
         Clique <a href="index.php">aqui</a> para ir para a página de inicial. 
        </p>

    </body>
</html>


