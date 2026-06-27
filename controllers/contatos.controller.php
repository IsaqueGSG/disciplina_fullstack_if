<?php

$conexao = require __DIR__ . '/../config/database.php';

function salvarContato($nome, $email, $mensagem) {
    global $conexao;

    $stmt = $conexao->prepare("
        INSERT INTO contatos (nome, email, mensagem) 
        VALUES (?, ?, ?)
    ");
    
    $stmt->bind_param("sss", $nome, $email, $mensagem);
    
    return $stmt->execute();
}

// Lista todas as mensagens para o painel do Admin
function listarContatos() {
    global $conexao;

    $sql = "SELECT * FROM contatos ORDER BY criado_em DESC";
    $resultado = $conexao->query($sql);

    return $resultado->fetch_all(MYSQLI_ASSOC);
}