<?php

$title = "Produtos";
$pageCss = "/produtos.css";

require_once __DIR__ . '/../../controllers/produtos.controller.php';

$produtos = listarProdutos();

ob_start();

?>

<section class="min-vh-100 py-5">
    
    <div class="container mt-5">

        <h2 class="section-title text-center mb-5 fw-bold">
            Nossos <span class="text-primary">Jetskis</span>
        </h2>

        <div class="row g-4">

            <?php foreach ($produtos as $produto): ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card card-widget widget-user-2 h-100 shadow-sm border-0 transition-hover">

                        <div class="card-header bg-transparent border-bottom-0 pt-4">
                            <h4 class="fw-bold text-dark mb-0"><?= htmlspecialchars($produto['nome']) ?></h4>
                        </div>

                        <div class="img-container px-3">
                            <img src="../../assets/produtos/<?= htmlspecialchars($produto['imagem']) ?>"
                                 class="card-img-top rounded"
                                 style="height: 240px; object-fit: cover;"
                                 alt="<?= htmlspecialchars($produto['nome']) ?>">
                        </div>

                        <div class="card-footer bg-transparent border-top-0 pt-3 pb-4 px-3 mt-auto">
                            <a href="/projeto_fullstack/pages/detalhes/detalhes.php?id=<?= $produto['id'] ?>"
                               class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                                <i class="bi bi-eye me-2"></i> Ver detalhes
                            </a>
                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>

<?php

$content = ob_get_clean();

require_once __DIR__ . '/../../layout/layout.php';