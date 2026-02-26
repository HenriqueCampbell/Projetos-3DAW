<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $op = $_POST["operacao"];
        $a = $_POST["a"];
        $b = $_POST["b"];

        switch ($op) {
            case 'soma':
                $resultado = $a + $b;
                break;
            case 'subtracao':
                $resultado = $a - $b;
                break;
            case 'multiplicacao':
                $resultado = $a * $b;
                break;
            case 'divisao':
                if ($b != 0) {
                    $resultado = $a / $b ;
                } else {
                    $resultado = 'Erro: Divisão por zero!';
                }
                break;
            default:
                $resultado = 'Erro na seleção da operação!';
                break;
        }
}
      //  echo 'Resultado: ' . $soma;
    //  http://localhost/daw/soma.php?a=2&b=3
?>
<!DOCTYPE html>
<html>
<body>
<h1><?php echo 'Minha Calculadora!';?></h1>

<form method='POST' action='calculadora.php'>
    a:<input type=text name='a'><br>
    b:<input type=text name='b'>
    <input type=submit value='Somar'>
    <br><br>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo 'Resultado: ' . $soma; 
}
?>
    
</body>
</html>