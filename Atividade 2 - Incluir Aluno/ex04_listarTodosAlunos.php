<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Listar Alunos</h1>

<table>
    <tr><th>Matricula</th><th>Nome</th><th>Email</th></tr>
<?php
   $arqAluno = fopen("alunos.txt","r") or die("erro ao abrir arquivo");
 
   while(!feof($arqAluno)) {
        $linha = fgets($arqAluno);
        $colunaDados = explode(";", $linha);
 
 // <tr><td><?php echo $colunaDados[0] </td>
        echo "<tr><td>" . $colunaDados[0] . "</td>" .
            "<td>" . $colunaDados[1] . "</td>" .
            "<td>" . $colunaDados[2] . "</td></tr>";
    }
 
   fclose($arqAluno);
    $msg = "Deu tudo certo!!!";
?>
</table>
<p><?php echo $msg ?></p>
<br>
</body>
</html>