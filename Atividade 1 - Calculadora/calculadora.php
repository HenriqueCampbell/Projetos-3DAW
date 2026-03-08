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
            case 'potenciacao':
                $resultado = pow($a, $b);
                break;
            case 'radiciacao':
                $resultado = pow($a, 1 / $b)
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
<h1> Minha Calculadora! </h1>

<form method='POST' action='calculadora.php'>
    a:<input type=text name='a'><br>
    b:<input type=text name='b'><br>
    <!-- <input type=submit name='operacao' value='soma'> -->

    <button type="submit" name="operacao" value="soma">Somar (a+b) </button>
    <button type="submit" name="operacao" value="subtracao">Subtrair (a-b)</button> <br>
    <button type="submit" name="operacao" value="multiplicacao">Multiplicar (a×b)</button>
    <button type="submit" name="operacao" value="divisao">Dividir (a÷b)</button> <br>
    <button type="submit" name="operacao" value="potenciacao">Potência (aᵇ)</button>
    <button type="submit" name="operacao" value="radiciacao"> Raiz (b√a)</button>

</form>

    <br><br>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo 'Resultado: ' . $resultado; 
}
?>
    
</body>
</html>