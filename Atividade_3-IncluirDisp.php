<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST["nome"];
        $sigla = $_POST["sigla"];
        $peso = $_POST["peso"];

        if(!file_exists("disciplinas.txt")) {
            $arqNovo = fopen("disciplinas.txt", "w") or die("Erro ao criar o arquivo.");
            $linha = "nome;sigla;peso";
            fwrite($arqNovo, $linha);

            fclose($arqNovo);
        }

        $arqNovo = fopen("disciplinas.txt", "a") or die("Erro ao criar o arquivo.");
        $linha = $nome . ";" . $sigla . ";" . $peso .;

        fwrite($arqNovo, $linha);
        fclose($arqNovo);

        echo "Disciplina Incluída com Sucesso!"
        
    }
>

<!DOCTYPE html>
<html>
<head>
</head>

<body>
<h1> Incluir Nova Disciplina </h1>
<form action= Atividade 3 - IncluirDisp.php

