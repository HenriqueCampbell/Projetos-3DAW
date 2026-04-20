<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Perguntas Subjetivas</title>
</head>
<body>

    <header>
        <h1>Perguntas Subjetivas Cadastradas</h1>
        <hr>
    </header>

    <main>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr bgcolor="#eeeeee">
                    <th>ID</th>
                    <th>Pergunta</th>
                    <th>Modelo de Resposta</th>
                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $arqPerguntasSub = fopen("perguntasSub.txt", "r") or die("Erro na leitura do arquivo"); 
                    $primeiraLinha = true; 

                    while(!feof($arqPerguntasSub)) { 
                        $linha = fgets($arqPerguntasSub);

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
                            echo "<td><a href='editarPerguntasSub.php?id=" . $coluna[0] . "'>📝 Editar</a></td>";
                            echo "<td><a href='excluirPerguntasSub.php?id=" . $coluna[0] . "'>❌ Excluir</a></td>";
                            echo "</tr>";
                        }
                    }
                    fclose($arqPerguntasSub);
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