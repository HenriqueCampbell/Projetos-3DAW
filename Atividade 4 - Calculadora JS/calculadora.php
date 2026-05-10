<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $op = $_GET["operacao"];
        $a = $_GET["a"];
        $b = $_GET["b"];

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
                $resultado = pow($a, 1 / $b);
                break;
            default:
                $resultado = 'Erro na seleção da operação!';
                break;
        }
        echo $resultado;
}
?>