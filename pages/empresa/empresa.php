<?php

$title = "Empresa";
$pageCss = "/empresa.css";

ob_start();

?>

<section class="min-vh-100 d-flex align-items-center py-5">
    
    <div class="container mt-5">

        <div class="row align-items-center">

            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="empresa-img text-center">
                    <img src="../../assets/usuarios/cristiano-jetski.webp" class="img-fluid rounded shadow-sm" alt="Experiência Jetsky">
                </div>
            </div>

            <div class="col-lg-6">

                <h2 class="empresa-title mb-4">
                    A melhor experiência de
                    <span class="text-primary fw-bold">Jetski</span> na água
                </h2>

                <p class="empresa-desc text-muted fs-5">
                    A <strong>Jetsky</strong> oferece aluguel de jetskis modernos,
                    seguros e prontos para aventura. Ideal para quem busca
                    emoção, turismo ou simplesmente curtir o mar com liberdade.
                </p>

                <p class="empresa-desc text-muted fs-5">
                    Nossos modelos garantem potência, estabilidade e uma
                    experiência inesquecível para iniciantes e aventureiros.
                </p>

                <div class="mt-4 pt-2">
                    <a href="/projeto_fullstack/pages/produtos/produtos.php"
                       class="btn btn-primary btn-lg me-3 px-4 shadow-sm">
                        Ver todos os produtos
                    </a>

                    <a href="#contato" class="btn btn-outline-dark btn-lg px-4">
                        Falar conosco
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>

<?php

$content = ob_get_clean();

require_once __DIR__ . '/../../layout/layout.php';