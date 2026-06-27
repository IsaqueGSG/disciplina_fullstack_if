<?php

$title = "Local";
$pageCss = "/local.css";

require_once __DIR__ . '/../../controllers/empresa.controller.php';

$empresa = buscarEmpresa();

ob_start();

?>

<section class="min-vh-100 py-5">
    <div class="container mt-5">

        <h1 class="text-center mb-5 fw-bold text-dark">
            Nossa <span class="text-primary">Localização</span>
        </h1>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="card border-0 shadow-sm rounded overflow-hidden bg-white">
                    
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-light text-primary rounded p-3 me-3">
                                <i class="bi bi-geo-alt-fill fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($empresa['nome']) ?></h4>
                                <p class="text-muted m-0 fs-6">
                                    <?= htmlspecialchars($empresa['endereco']) ?><br>
                                    <strong><?= htmlspecialchars($empresa['cidade']) ?> - <?= htmlspecialchars($empresa['estado']) ?></strong>
                                </p>
                            </div>
                        </div>

                        <div class="ratio ratio-16x9 rounded shadow-sm overflow-hidden border">
                            <iframe 
                                src="https://maps.google.com/maps?q=<?= urlencode($empresa['endereco'] . ', ' . $empresa['cidade']) ?>&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
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