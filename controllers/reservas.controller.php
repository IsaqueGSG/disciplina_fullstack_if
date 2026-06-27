<?php

$conexao = require __DIR__ . '/../config/database.php';

function criarReserva($usuarioId, $produtoId, $data, $hora)
{
    global $conexao;

    $stmt = $conexao->prepare("
        INSERT INTO reservas
        (usuario_id, produto_id, data_reserva, hora_reserva)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iiss",
        $usuarioId,
        $produtoId,
        $data,
        $hora
    );

    return $stmt->execute();
}

function listarReservasAdmin()
{
    global $conexao;

    $sql = "SELECT
                r.*,
                u.nome AS cliente_nome,
                u.email AS cliente_email,
                p.nome AS produto_nome
            FROM reservas r
            JOIN usuarios u ON r.usuario_id = u.id
            JOIN produtos p ON r.produto_id = p.id
            ORDER BY r.data_reserva DESC, r.hora_reserva DESC";

    $resultado = $conexao->query($sql);

    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function atualizarStatusReserva($id, $status)
{
    global $conexao;

    $stmt = $conexao->prepare("
        UPDATE reservas
        SET status = ?
        WHERE id = ?
    ");

    $stmt->bind_param("si", $status, $id);

    return $stmt->execute();
}

function excluirReserva($id)
{
    global $conexao;

    $stmt = $conexao->prepare("
        DELETE FROM reservas
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

function listarReservasUsuario($usuarioId)
{
    global $conexao;

    $stmt = $conexao->prepare("
        SELECT
            r.*,
            p.nome AS produto_nome,
            p.imagem AS produto_imagem
        FROM reservas r
        INNER JOIN produtos p
            ON r.produto_id = p.id
        WHERE r.usuario_id = ?
        ORDER BY r.data_reserva DESC, r.hora_reserva DESC
    ");

    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}