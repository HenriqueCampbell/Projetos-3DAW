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

// O html que você fez lá em baixo entra em ação agora!!

// Após a confirmação, vai procurar a linha com os dados do Aluno a ser excluido e reescrever o arq sem ele.

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
                $linhasNovas .= "";
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
    <h1>Exclusão de Aluno</h1>
    <form action="editarAluno.php" method="POST">
        Matrícula: 
        <input type="text" name="matricula" value="<?php echo $matricula; ?>" 
               readonly style="background-color: #dddddd;"> 
        <br>
        
        Nome: 
        <input type="text" name="nome" value="<?php echo $nome; ?>"
               readonly style="background-color: #dddddd;"> 
        <br>
        
        E-mail: 
        <input type="text" name="email" value="<?php echo $email; ?>"
               readonly style="background-color: #dddddd;"> 
        <br>
        
        <h3> Você tem certeza que deseja excluir este aluno? </h3>
        <input type="submit" value="Excluir Permanentemente">
    </form>
    <br>
    <a href="ex04_listarTodosAlunos.php">Voltar para a lista</a>
</body>
</html>