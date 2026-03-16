<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Listar Alunos</h1>
<h3>
    <!-- Formulário de busca -->
    <form action="ex04_listarTodosAlunos.php" method="POST">
    Filtrar por Matrícula: <input type="text" name="busca_matricula">
    <input type="submit" value="Filtrar">
    <a href="ex04_listarTodosAlunos.php">Limpar Filtro</a>
    </form>
</h3>

<table>
    <tr><th>Matricula</th><th>Nome</th><th>Email</th><th>Ação</th></tr>
<?php

    // Verifica se o formulário de busca foi submetido e captura o valor da matrícula.
    $busca = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["busca_matricula"])) 
    {
        $busca = $_POST["busca_matricula"];
    }

   $arqAluno = fopen("alunos.txt","r") or die("Erro ao abrir o arquivo");

    $primeiraLinha = true;

   while(!feof($arqAluno)) {
    
        $linha = fgets($arqAluno);

        if ($primeiraLinha) {
        $primeiraLinha = false;
        continue;
        }

        if (trim($linha) != "") 
        {
            $colunaDados = explode(";", $linha);

            if ($busca == "" || $colunaDados[0] == $busca)
            { 
                echo "<tr><td>" . $colunaDados[0] . "</td>" .
                     "<td>" . $colunaDados[1] . "</td>" .
                     "<td>" . $colunaDados[2] . "</td>" .
                     // Gera o link para editar o aluno, passando a matrícula como parâmetro.
                     "<td><a href='editarAluno.php?matricula=" . $colunaDados[0] . "'>Editar</a></td></tr>";
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