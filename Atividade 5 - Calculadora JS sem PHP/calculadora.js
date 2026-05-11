window.onload = function() {
    
    document.getElementById("soma").onclick = function() { Calcular('soma'); };
    document.getElementById("subtracao").onclick = function() { Calcular('subtracao'); };
    document.getElementById("multiplicacao").onclick = function() { Calcular('multiplicacao'); };
    document.getElementById("divisao").onclick = function() { Calcular('divisao'); };
    document.getElementById("potenciacao").onclick = function() { Calcular('potenciacao'); };
    document.getElementById("radiciacao").onclick = function() { Calcular('radiciacao'); };

};

function Calcular(op) {
    let a = parseFloat(document.getElementById("a").value);
    let b = parseFloat(document.getElementById("b").value);
    let resultado;

    switch (op) {
        case 'soma':
            resultado = a + b;
            break;
        case 'subtracao':
            resultado = a - b;
            break;
        case 'multiplicacao':
            resultado = a * b;
            break;
        case 'divisao':
            if (b !== 0) {
                resultado = a / b;
            } else {
                resultado = 'Erro: Divisão por zero!';
            }
            break;
        case 'potenciacao':
            resultado = Math.pow(a, b);
            break;
        case 'radiciacao':
            if (b !== 0) {
                resultado = Math.pow(a, 1 / b);
            } else {
                resultado = 'Erro: Índice da raiz não pode ser zero!';
            }
            break;
        default:
            resultado = 'Erro na seleção da operação!';
            break;
    }

    document.getElementById("msg").innerHTML = "Resultado: " + resultado;
}