<?php

$conexao = require __DIR__ . '/../config/database.php';

function listarUsuarios()
{
    global $conexao;

    $sql = "SELECT * FROM usuarios";

    $resultado = $conexao->query($sql);

    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function buscarUsuario($id)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "SELECT * FROM usuarios WHERE id=?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function buscarUsuarioPorEmail($email)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "SELECT * FROM usuarios WHERE email=?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function criarUsuario(
    $nome,
    $email,
    $senha
)
{
    global $conexao;

    $senhaHash =
        password_hash(
            $senha,
            PASSWORD_DEFAULT
        );

    $stmt = $conexao->prepare(
        "INSERT INTO usuarios
        (nome,email,senha)
        VALUES (?,?,?)"
    );

    $stmt->bind_param(
        "sss",
        $nome,
        $email,
        $senhaHash
    );

    return $stmt->execute();
}

function excluirUsuario($id)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "DELETE FROM usuarios
        WHERE id=?"
    );

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

function atualizarRole(
    $id,
    $role
)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "UPDATE usuarios
         SET role = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "si",
        $role,
        $id
    );

    return $stmt->execute();
}

function bloquearUsuario($id)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "UPDATE usuarios
         SET bloqueado = 1
         WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

function desbloquearUsuario($id)
{
    global $conexao;

    $stmt = $conexao->prepare(
        "UPDATE usuarios
         SET bloqueado = 0
         WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}