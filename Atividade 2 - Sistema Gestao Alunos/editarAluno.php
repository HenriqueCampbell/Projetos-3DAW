<?php
$msg = "";
$nome = "";
$email = "";
$matricula = "";

// Carregando os dados do aluno para o formulário via GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    $arqAluno = fopen("alunos.txt", "r") or die("Erro ao abrir arquivo");
    
    while (!feof($arqAluno)) {
        $linha = fgets($arqAluno);
        if (trim($linha) != "")
        {
            $colunaDados = explode(";", trim($linha));
            // Procurando a linha do aluno com a matrícula correspondente
            if ($colunaDados[0] == $matricula) 
            {
                $nome = $colunaDados[1];
                $email = $colunaDados[2];
                break; 
            }
        }
    }
    fclose($arqAluno);
}

// Percorre o arquivo para criar uma cópia atualizada: se a matrícula bater, usa os novos dados; caso contrário, mantém os dados antigos.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matricula = $_POST["matricula"];
    $novoNome = $_POST["nome"];
    $novoEmail = $_POST["email"];
    
    $linhasNovas = ""; // Variável para guardar o conteúdo atualizado do arquivo
    $arqAluno = fopen("alunos.txt", "r") or die("Erro ao abrir arquivo");
    
    while (!feof($arqAluno)) {
        $linha = fgets($arqAluno);
        if (trim($linha) != "") 
        {
            $colunaDados = explode(";", trim($linha));

            if ($colunaDados[0] == $matricula)
            {
                $linhasNovas .= $matricula . ";" . $novoNome . ";" . $novoEmail . "\n";
            } 
            else 
            {
                $linhasNovas .= $linha;
            }
        }
    }
    fclose($arqAluno);

    // Sobrescreve o arquivo com o conteúdo atualizado
    $arqEscrita = fopen("alunos.txt", "w") or die("Erro ao salvar arquivo");
    fwrite($arqEscrita, $linhasNovas);
    fclose($arqEscrita);
    

    header("Location: ex04_listarTodosAlunos.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<body>
    <h1>Editar Aluno</h1>
    <form action="editarAluno.php" method="POST">
        Matrícula: 
        <input type="text" name="matricula" value="<?php echo $matricula; ?>" 
               readonly style="background-color: #dddddd;"> 
        <br><br>
        
        Nome: 
        <input type="text" name="nome" value="<?php echo $nome; ?>">
        <br><br>
        
        E-mail: 
        <input type="text" name="email" value="<?php echo $email; ?>">
        <br><br>
        
        <input type="submit" value="Salvar Alterações">
    </form>
    <br>
    <a href="ex04_listarTodosAlunos.php">Voltar para a lista</a>
</body>
</html>