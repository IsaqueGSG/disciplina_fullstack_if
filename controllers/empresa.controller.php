<?php

$conexao = require __DIR__ . '/../config/database.php';

function buscarEmpresa()
{
    global $conexao;

    $sql = "SELECT * FROM empresa LIMIT 1";

    $resultado = $conexao->query($sql);

    return $resultado->fetch_assoc();
}

function atualizarEmpresa(
    $nome,
    $descricao,
    $telefone,
    $email,
    $endereco,
    $cidade,
    $estado,
    $mapa_iframe,
    $instagram,
    $facebook,
    $youtube,
    $whatsapp
)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "UPDATE empresa
        SET nome=?,
            descricao=?,
            telefone=?,
            email=?,
            endereco=?,
            cidade=?,
            estado=?,
            mapa_iframe=?,
            instagram=?,
            facebook=?,
            youtube=?,
            whatsapp=?
        WHERE id=1"
    );

    $stmt->bind_param(
        "ssssssssssss",
        $nome,
        $descricao,
        $telefone,
        $email,
        $endereco,
        $cidade,
        $estado,
        $mapa_iframe,
        $instagram,
        $facebook,
        $youtube,
        $whatsapp
    );

    return $stmt->execute();
}