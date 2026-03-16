<?php
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $matricula = $_POST["matricula"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    echo "matricula: " . $matricula . " nome: " . $nome . " email: " . $email;
   if (!file_exists("alunos.txt")) {
       $arqAluno = fopen("alunos.txt","w") or die("erro ao criar arquivo");
       $linha = "matricula;nome;email\n";
       fwrite($arqAluno,$linha);
       fclose($arqAluno);
   }
   $arqAluno = fopen("alunos.txt","a") or die("erro ao criar arquivo");
//   $arqAluno = fopen("alunos.txt","w") or die("erro ao criar arquivo");
 //   $linha = "matricula;nome;email\n";
    $linha = $matricula . ";" . $nome . ";" . $email . "\n";
    fwrite($arqAluno,$linha);
    fclose($arqAluno);
    $msg = "Deu tudo certo!!!";
}

?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Criar Novo Aluno</h1>
<form action="incluirAluno.php" method="POST">
    Matrícula: <input type="text" name="matricula">
    <br><br>
    Nome: <input type="text" name="nome">
    <br><br>
    E-mail: <input type="text" name="email">
    <br><br>
    <input type="submit" value="Criar Novo Aluno">
</form>
<p><?php echo $msg ?></p>
<br>
</body>
</html>