<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Listar Alunos</h1>
<h3>
    <form action="ex04_listarTodosAlunos.php" method="POST">
    Filtrar por Matrícula: <input type="text" name="busca_matricula">
    <input type="submit" value="Filtrar">
    <a href="ex04_listarTodosAlunos.php">Limpar Filtro</a>
    </form>
</h3>

<table>
    <tr><th>Matricula</th><th>Nome</th><th>Email</th></tr>
<?php

    $busca = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["busca_matricula"])) // Verifica se alguma busca ou filtro foi enviado.
    {
        $busca = $_POST["busca_matricula"];
    }

   $arqAluno = fopen("alunos.txt","r") or die("Erro ao abrir o arquivo");
 
   while(!feof($arqAluno)) {
        $linha = fgets($arqAluno);
        if (trim($linha) != "") 
        {
            $colunaDados = explode(";", $linha);

            if ($busca == "" || $colunaDados[0] == $busca)
            { // Verifica se a busca estiver vazia ou se a matrícula está igual à busca, e exibe:
                echo "<tr><td>" . $colunaDados[0] . "</td>" .
                     "<td>" . $colunaDados[1] . "</td>" .
                     "<td>" . $colunaDados[2] . "</td></tr>";
            }
        }
    }
 
   fclose($arqAluno);
    $msg = "Operação realizada com sucesso.";
?>
</table>
<p><?php echo $msg ?></p>
<br>
</body>
</html>