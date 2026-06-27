<?php

$conexao = require __DIR__ . '/../config/database.php';

function listarProdutos()
{
    global $conexao;

    $sql = "SELECT * FROM produtos";

    $resultado = $conexao->query($sql);

    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function buscarProduto($id)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "SELECT * FROM produtos WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function criarProduto($nome, $descricao, $preco, $imagem)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "INSERT INTO produtos
        (nome, descricao, preco, imagem)
        VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssds",
        $nome,
        $descricao,
        $preco,
        $imagem
    );

    return $stmt->execute();
}

function atualizarProduto(
    $id,
    $nome,
    $descricao,
    $preco,
    $imagem
)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "UPDATE produtos
        SET nome=?,
            descricao=?,
            preco=?,
            imagem=?
        WHERE id=?"
    );

    $stmt->bind_param(
        "ssdsi",
        $nome,
        $descricao,
        $preco,
        $imagem,
        $id
    );

    return $stmt->execute();
}

function excluirProduto($id)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "DELETE FROM produtos WHERE id=?"
    );

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}