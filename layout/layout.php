<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Jetsky' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="/projeto_fullstack/dist/css/adminlte.css">
    <link rel="stylesheet" href="/projeto_fullstack/layout/layout.css">
</head>

<body>
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand-md fixed-top border-bottom-0">
            <div class="container-fluid">

                <a class="navbar-brand text-dark fw-bold" href="/projeto_fullstack/index.php">
                    Jetsky
                </a>

                <button class="navbar-toggler border-0"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarMain">
                    <i class="bi bi-list"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/empresa/empresa.php">Empresa</a></li>
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/produtos/produtos.php">Produtos</a></li>
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/detalhes/detalhes.php">Detalhes</a></li>
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/precos/precos.php">Preços</a></li>
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/clientes/clientes.php">Clientes</a></li>
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/comentarios/comentarios.php">Comentários</a></li>
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/contatos/contato.php">Contato</a></li>
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/local/local.php">Local</a></li>
                        <li class="nav-item"><a class="nav-link" href="/projeto_fullstack/pages/redes/redes.php">Redes</a></li>
                    </ul>

                    <ul class="navbar-nav ms-auto">

                        <?php if (isset($_SESSION['usuario'])): ?>

                            <?php if ($_SESSION['usuario']['role'] === 'admin'): ?>

                                <li class="nav-item dropdown">

                                    <a class="nav-link dropdown-toggle"
                                        href="#"
                                        role="button"
                                        data-bs-toggle="dropdown">

                                        <i class="bi bi-person-circle"></i>
                                        <?= $_SESSION['usuario']['nome'] ?>

                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <a class="dropdown-item" href="/projeto_fullstack/pages/admin/usuarios.php">
                                                Usuários
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="/projeto_fullstack/pages/admin/produtos.php">
                                                Produtos
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="/projeto_fullstack/pages/admin/empresa.php">
                                                Empresa
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="/projeto_fullstack/pages/admin/reservas.php">
                                                Reservas
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="/projeto_fullstack/pages/admin/contatos.php">
                                                Contatos
                                            </a>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>
                                            <a class="dropdown-item text-danger"
                                                href="/projeto_fullstack/pages/logout.php">
                                                Sair
                                            </a>
                                        </li>

                                    </ul>

                                </li>

                            <?php else: ?>

                                <li class="nav-item dropdown">

                                    <a class="nav-link dropdown-toggle"
                                        href="#"
                                        role="button"
                                        data-bs-toggle="dropdown">

                                        <i class="bi bi-person-circle"></i>
                                        <?= $_SESSION['usuario']['nome'] ?>

                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <a class="dropdown-item" href="/projeto_fullstack/pages/perfil/perfil.php">
                                                Perfil
                                            </a>
                                        </li>


                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>
                                            <a class="dropdown-item text-danger"
                                                href="/projeto_fullstack/pages/logout.php">
                                                Sair
                                            </a>
                                        </li>

                                    </ul>

                                </li>

                            <?php endif; ?>

                        <?php else: ?>

                            <li class="nav-item">
                                <a class="nav-link" href="/projeto_fullstack/pages/login/login.php">
                                    Entrar
                                </a>
                            </li>

                        <?php endif; ?>

                    </ul>
                </div>

            </div>
        </nav>

        <div class="main-content-wrapper">
            <main>
                <?= $content ?>
            </main>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/projeto_fullstack/dist/js/adminlte.js"></script>
</body>

</html>