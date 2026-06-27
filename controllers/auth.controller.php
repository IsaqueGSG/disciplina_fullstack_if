<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/usuarios.controller.php';

function login($email, $senha)
{
    $usuario = buscarUsuarioPorEmail($email);

    if (!$usuario) {
        return false;
    }

    if ($usuario['bloqueado'] == 1) {
        return false;
    }

    if (!password_verify($senha, $usuario['senha'])) {
        return false;
    }

    // Salva os dados na sessão
    $_SESSION['usuario'] = [
        'id' => $usuario['id'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
        'role' => $usuario['role']
    ];

    return true;
}

function logout()
{
    // session_start() removido daqui pois já inicia globalmente no topo
    unset($_SESSION['usuario']);
    session_destroy();
}

function usuarioLogado()
{
    return $_SESSION['usuario'] ?? null;
}

function isAdmin()
{
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['role'] === 'admin';
}

function requireAdmin()
{
    if (!isAdmin()) {
        header("Location: /projeto_fullstack/index.php");
        exit;
    }
}

function requireAutenticado()
{
    if (!isset($_SESSION['usuario'])) {
        header("Location: /projeto_fullstack/pages/login/login.php");
        exit;
    }
}
