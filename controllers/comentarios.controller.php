<?php

$conexao = require __DIR__ . '/../config/database.php';

function listarComentarios()
{
    global $conexao;

    $sql = "
        SELECT
            comentarios.*,
            usuarios.nome AS usuario_nome,
            usuarios.foto,
            produtos.nome AS produto_nome
        FROM comentarios
        INNER JOIN usuarios
            ON comentarios.usuario_id = usuarios.id
        INNER JOIN produtos
            ON comentarios.produto_id = produtos.id
        ORDER BY comentarios.criado_em DESC
    ";

    $resultado = $conexao->query($sql);

    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function listarComentariosProduto($produtoId)
{
    global $conexao;

    $stmt = $conexao->prepare("
        SELECT
            comentarios.*,
            usuarios.nome AS usuario_nome,
            usuarios.foto,
            produtos.nome AS produto_nome
        FROM comentarios
        INNER JOIN usuarios
            ON comentarios.usuario_id = usuarios.id
        INNER JOIN produtos
            ON comentarios.produto_id = produtos.id
        WHERE comentarios.produto_id = ?
        ORDER BY comentarios.criado_em DESC
    ");

    $stmt->bind_param("i", $produtoId);

    $stmt->execute();

    return $stmt
        ->get_result()
        ->fetch_all(MYSQLI_ASSOC);
}


function criarComentario(
    $usuarioId,
    $produtoId,
    $comentario,
    $nota
) {
    global $conexao;

    $stmt = $conexao->prepare("
        INSERT INTO comentarios
        (
            usuario_id,
            produto_id,
            comentario,
            nota
        )
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iisi",
        $usuarioId,
        $produtoId,
        $comentario,
        $nota
    );

    return $stmt->execute();
}

function atualizarComentario(
    $id,
    $comentario,
    $nota
) {
    global $conexao;

    $stmt = $conexao->prepare("
        UPDATE comentarios
        SET
            comentario = ?,
            nota = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sii",
        $comentario,
        $nota,
        $id
    );

    return $stmt->execute();
}

function excluirComentario($id)
{
    global $conexao;

    $stmt = $conexao->prepare("
        DELETE FROM comentarios
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}
