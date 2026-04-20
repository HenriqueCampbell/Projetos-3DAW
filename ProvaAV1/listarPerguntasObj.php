<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Perguntas Objetivas</title>
</head>
<body>

    <header>
        <h1>Perguntas Objetivas Cadastradas</h1>
        <hr>
    </header>

    <main>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr bgcolor="#eeeeee">
                    <th>ID</th>
                    <th>Pergunta</th>
                    <th>Correta</th>
                    <th>Incorreta 1</th>
                    <th>Incorreta 2</th>
                    <th>Incorreta 3</th>
                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
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
                            echo "<tr>";
                            echo "<td>" . $coluna[0] . "</td>";
                            echo "<td>" . $coluna[1] . "</td>";
                            echo "<td>" . $coluna[2] . "</td>";
                            echo "<td>" . $coluna[3] . "</td>";
                            echo "<td>" . $coluna[4] . "</td>";
                            echo "<td>" . $coluna[5] . "</td>";
                            echo "<td><a href='editarPerguntasObj.php?id=" . $coluna[0] . "'>📝 Editar</a></td>";
                            echo "<td><a href='excluirPerguntasObj.php?id=" . $coluna[0] . "'>❌ Excluir</a></td>";
                            echo "</tr>";
                        }
                    }
                    fclose($arqPerguntasObj);
                ?>
            </tbody>
        </table>
        <p><strong>Status:</strong> Leitura realizada com sucesso.</p>
    </main>

    <hr>
    
    <nav>
        <p>
            <a href="index.php">⬅ Voltar para o Menu Principal</a>
        </p>
    </nav>

</body>
</html>

