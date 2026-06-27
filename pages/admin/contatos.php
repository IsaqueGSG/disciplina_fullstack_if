<?php

$title = "Gerenciar Contatos";
$pageCss = "/admin.css"; // ajuste para o seu css de admin se houver

require_once __DIR__ . '/../../controllers/auth.controller.php';
require_once __DIR__ . '/../../controllers/contatos.controller.php';

// Trava a página: se não for admin, redireciona para a Home
requireAdmin();

$contatos = listarContatos();

ob_start();
?>

<section class="py-5 min-vh-100">
    <div class="container mt-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h1 class="fw-bold text-dark m-0">Mensagens de <span class="text-primary">Contato</span></h1>
            <span class="badge bg-primary fs-6 rounded-pill"><?= count($contatos) ?> Mensagem(ns)</span>
        </div>

        <?php if (count($contatos) === 0): ?>
            <div class="alert alert-info text-center py-4 shadow-sm border-0">
                <i class="bi bi-chat-left-dots fs-2 d-block mb-2 text-secondary"></i>
                <h5 class="fw-bold m-0">Nenhuma mensagem recebida por enquanto.</h5>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($contatos as $c): ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3 bg-white mb-2">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 border-bottom pb-2">
                                    <div>
                                        <h5 class="fw-bold text-dark m-0 mb-1">
                                            <i class="bi bi-person-fill text-secondary me-2"></i><?= htmlspecialchars($c['nome']) ?>
                                        </h5>
                                        <span class="text-muted small">
                                            <i class="bi bi-envelope-fill me-1"></i> <?= htmlspecialchars($c['email']) ?>
                                        </span>
                                    </div>
                                    <div class="text-end mt-2 mt-md-0">
                                        <span class="badge bg-light text-secondary border">
                                            <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-3 bg-light rounded text-secondary border-start border-primary border-3" style="white-space: pre-wrap; font-style: italic;">
                                    "<?= htmlspecialchars($c['mensagem']) ?>"
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layout/layout.php';