<?php

$title = "Redes Sociais";
$pageCss = "/redes.css";

require_once __DIR__ . '/../../controllers/empresa.controller.php';

$empresa = buscarEmpresa();

ob_start();

?>

<section class="min-vh-100 py-5">
    <div class="container mt-5">

        <h1 class="text-center mb-5 fw-bold text-dark">
            Conecte-se <span class="text-primary">Conosco</span>
        </h1>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card border-0 shadow-sm rounded bg-white">
                    <div class="card-body p-4">
                        
                        <p class="text-muted text-center mb-4">Siga nossos perfis oficiais e fique por dentro de todas as novidades do mundo náutico!</p>
                        
                        <div class="d-flex flex-column gap-3">

                            <?php if (!empty($empresa['instagram'])): ?>
                                <a href="<?= htmlspecialchars($empresa['instagram']) ?>" target="_blank" 
                                   class="btn d-flex align-items-center text-white p-3 rounded shadow-sm transition-hover" 
                                   style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);">
                                    <i class="bi bi-instagram fs-4 me-3"></i>
                                    <span class="fw-bold flex-grow-1 text-start">Siga-nos no Instagram</span>
                                    <i class="bi bi-arrow-right small opacity-70"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($empresa['facebook'])): ?>
                                <a href="<?= htmlspecialchars($empresa['facebook']) ?>" target="_blank" 
                                   class="btn d-flex align-items-center text-white p-3 rounded shadow-sm transition-hover" 
                                   style="background-color: #1877F2;">
                                    <i class="bi bi-facebook fs-4 me-3"></i>
                                    <span class="fw-bold flex-grow-1 text-start">Curta nossa página no Facebook</span>
                                    <i class="bi bi-arrow-right small opacity-70"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($empresa['youtube'])): ?>
                                <a href="<?= htmlspecialchars($empresa['youtube']) ?>" target="_blank" 
                                   class="btn d-flex align-items-center text-white p-3 rounded shadow-sm transition-hover" 
                                   style="background-color: #FF0000;">
                                    <i class="bi bi-youtube fs-4 me-3"></i>
                                    <span class="fw-bold flex-grow-1 text-start">Inscreva-se no YouTube</span>
                                    <i class="bi bi-arrow-right small opacity-70"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($empresa['whatsapp'])): ?>
                                <a href="https://wa.me/<?= preg_replace('/\D/', '', $empresa['whatsapp']) ?>" target="_blank" 
                                   class="btn d-flex align-items-center text-white p-3 rounded shadow-sm transition-hover" 
                                   style="background-color: #25D366;">
                                    <i class="bi bi-whatsapp fs-4 me-3"></i>
                                    <span class="fw-bold flex-grow-1 text-start">Chame no WhatsApp</span>
                                    <i class="bi bi-arrow-right small opacity-70"></i>
                                </a>
                            <?php endif; ?>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<?php

$content = ob_get_clean();

require_once __DIR__ . '/../../layout/layout.php';