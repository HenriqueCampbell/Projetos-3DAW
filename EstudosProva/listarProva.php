<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1> Listando / Leitura dos Dados </h1>
        <br>
        <!-- Até aqui é o formato de html básico. Abaixo, será criado uma tabela para a leitura dos dados do documento. -->
        <table> <!-- O tr é uma "row" e o th é as colunas. Eles tem o corportamento similar a uma tag h1 --> 
            <tr><th>Nome</th><th>Dado 1</th><th>Dado 2</th><th>Edição</th><th>Exclusão</th></tr>
            <!--Aí agora chegaria a parte em que você leria o arquivo e adiona as linhas na tabela né? Ou seja, abrir o PHP aqui no meio mesmo. -->

            <?php
                $arqNovo = fopen("arquivo.txt", "r") or die("Erro na leitura do arquivo"); //primeiramente abrir o arquivo obviamente antes de inciar a leitura em si.
                $primeiraLinha = true; // Caso você não queira exibir a linha de cabelhaço.

                while(!feof($arqNovo)) { //Sintaxe do loop de "enquanto NÃO for o final do arquivo..faz isso aqui:"

                    $linha = fgets($arqNovo); //Primeiro vai guardar em $linha o que é definido como linha usando o fgets.

                    if (primeiraLinha = true) {
                        $primeiraLinha = false
                        continue; //Vai direto para a próxima iteração ou seja, linha, sem ler essa linha inicial.
                    }

                    if (trim($linha) != "") { //Esse if é só pra ter certeza que não vai ler uma linha só de espaços.
                        
                        $coluna = explode(";", $linha); //Isso aqui vai delimitar os espaços (colunas) de cada linha em um array em que o índice 0 nesse caso vai ser nome, 1 vai ser dado 1 e 2 vai ser dado 2.

                        //Agora no próximo echo é só copiar (trocando tableheader <th> por tabledata <td>) o que tu fez na tabela lá em cima e concatenar usando . com os dados da coluna em cada posição certinho. Lembra de colocar entre aspas as tags html que for usar aqui.
                        echo "<tr><td>" . $coluna[0] . "</td><td>" . $coluna[1] . "</dh><td>" . $coluna[2] . 

                        //E aqui são as colunas das "ações" que vão redirecionar para outras páginas.
                        "</td><td> . <a href='editarProva.php?dado1=" . $coluna[1] . "'>Editar</a></td>" .
                        "<td> . <a href='excluirProva.php'?dado1=" . $coluna[1] . "'>Excluir</a></td></tr>"

                    }

                }

                fclose($arqNovo);
                echo "Leitura realizada com sucesso."
            ?>
        </table>
    </body>
</html>



