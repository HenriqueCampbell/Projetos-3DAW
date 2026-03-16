<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Listar Disciplinas</h1>

<table>
    <tr><th>nome</th><th>sigla</th><th>carga</th></tr>
<?php
   $arqDisc = fopen("disciplinas.txt","r") or die("Erro ao abrir o arquivo.");
 
   while(!feof($arqDisc)) {
        $linha = fgets($arqDisc);

        if (trim($linha) != "") {
            $colunaDados = explode(";", $linha);
            // <tr><td><?php echo $colunaDados[0] </td>
            echo "<tr><td>" . $colunaDados[0] . "</td>" .
                "<td>" . $colunaDados[1] . "</td>" .
                "<td>" . $colunaDados[2] . "</td></tr>";
        }
    }
 
   fclose($arqDisc);
    $msg = "Operação realizada com sucesso.";
?>
</table>
<p><?php echo $msg ?></p>
<br>
</body>
</html>