<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Listar Disciplinas</h1>

<table>
    <tr><th>nome</th><th>sigla</th><th>carga</th></tr>
<?php
   $arqDisc = fopen("disciplinas.txt","r") or die("erro ao abrir arquivo");
 
   while(!feof($arqDisc)) {
        $linha = fgets($arqDisc);
        $colunaDados = explode(";", $linha);
 
 // <tr><td><?php echo $colunaDados[0] </td>
        echo "<tr><td>" . $colunaDados[0] . "</td>" .
            "<td>" . $colunaDados[1] . "</td>" .
            "<td>" . $colunaDados[2] . "</td></tr>";
    }
 
   fclose($arqDisc);
    $msg = "Deu tudo certo!!!";
?>
</table>
<p><?php echo $msg ?></p>
<br>
</body>
</html>