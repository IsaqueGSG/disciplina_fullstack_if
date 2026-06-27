<?php

$title = "Clientes";
$pageCss = "/clientes.css";

require_once __DIR__ . '/../../controllers/usuarios.controller.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $usuario = buscarUsuario($id);
    if ($usuario) {
        $usuarios = [$usuario];
    } else {
        $usuarios = array_filter(
            listarUsuarios(),
            fn($u) => $u['role'] !== 'admin'
        );
    }
} else {
    $usuarios = array_filter(
        listarUsuarios(),
        fn($u) => $u['role'] !== 'admin'
    );
}

ob_start();

?>

<section class="min-vh-100 py-5">
    <div class="container mt-5">

        <h2 class="section-title text-center mb-5 fw-bold text-dark">
            Nossos <span class="text-primary">Clientes</span>
        </h2>

        <div class="row g-4">

            <?php foreach ($usuarios as $usuario): ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card card-widget widget-user shadow-sm border-0 h-100 bg-white">
                        
                        <div class="widget-user-header bg-light pt-4 text-center">
                            <h3 class="widget-user-username fw-bold text-dark m-0">
                                <?= htmlspecialchars($usuario['nome']) ?>
                            </h3>
                            <h5 class="widget-user-desc text-muted small mt-1">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($usuario['cidade']) ?>
                            </h5>
                        </div>

                        <div class="text-center my-4">
                            <div class="d-inline-block position-relative overflow-hidden rounded-circle border border-2 border-white shadow-sm" 
                                 style="width: 120px; height: 120px; background-color: #f8f9fa;">
                                
                                <img src="../../assets/usuarios/<?= htmlspecialchars($usuario['foto'] ?? 'default.webp') ?>"
                                     class="position-absolute top-50 start-50 translate-middle w-100 h-100"
                                     style="object-fit: cover;"
                                     alt="Foto de <?= htmlspecialchars($usuario['nome']) ?>">
                                     
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-top-0 px-4 pb-4 mt-auto">
                            <a href="/projeto_fullstack/pages/comentarios/comentarios.php?usuario_id=<?= $usuario['id'] ?>"
                               class="btn btn-primary w-100 fw-bold shadow-sm rounded-pill py-2">
                                <i class="bi bi-chat-left-text me-2"></i> Ver Comentários
                            </a>
                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <?php if (count($usuarios) === 0): ?>
            <div class="alert alert-warning alert-dismissible shadow-sm text-center mt-5">
                <h5><i class="icon bi bi-exclamation-triangle me-2"></i> Oops!</h5>
                Nenhum cliente cadastrado ou encontrado.
            </div>
        <?php endif; ?>

    </div>
</section>

<?php

$content = ob_get_clean();

require_once __DIR__ . '/../../layout/layout.php';