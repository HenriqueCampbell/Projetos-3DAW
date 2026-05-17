<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matricula = $_POST["matricula"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];

    // Validação de segurança extra no servidor caso alguém desative o JS
    if (empty($matricula) || empty($nome) || empty($email)) {
        die("Erro: Dados inválidos detectados no servidor.");
    }

    // A página de listar está em outro repositório.
    $caminhoArquivo = "../Atividade 2 - Sistema Gestao Alunos/alunos.txt";
    if (!file_exists($caminhoArquivo)) {
        $arqAluno = fopen($caminhoArquivo, "w") or die("erro ao criar arquivo");
        $linha = "matricula;nome;email\n";
        fwrite($arqAluno, $linha);
        fclose($arqAluno);
    }

    $arqAluno = fopen($caminhoArquivo, "a") or die("erro ao abrir arquivo");
    $linha = $matricula . ";" . $nome . ";" . $email . "\n";
    fwrite($arqAluno, $linha);
    fclose($arqAluno);

    echo "Deu tudo certo!!";

} else {
    // Se tentarem acessar o arquivo .php direto pela URL, redireciona pro formulário. Aumenta a segurança do código
    header("Location: incluirAluno.html");
    exit();
}
?>