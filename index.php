<?php

$title = "Home";
$pageCss = "/index.css";

ob_start();

?>

<div id="carouselFull"
    class="carousel slide carousel-fade vh-100"
    data-bs-ride="carousel">

    <div class="carousel-inner h-100">
        <div class="carousel-item active h-100">
            <img src="./assets/usuarios/cristiano-jetski.webp"
                class="d-block w-100 h-100 object-fit-cover">

            <div class="carousel-caption d-flex flex-column justify-content-center h-100 pb-0" style="top: 0;">
                <h1>Jetsky</h1>
                <p class="lead">Experiência única sobre as águas</p>
            </div>
        </div>

        <div class="carousel-item h-100">
            <img src="./assets/usuarios/bolsonaro.webp"
                class="d-block w-100 h-100 object-fit-cover">

            <div class="carousel-caption d-flex flex-column justify-content-center h-100 pb-0" style="top: 0;">
                <h1>Velocidade</h1>
                <p class="lead">Potência e adrenalina</p>
            </div>
        </div>

        <div class="carousel-item h-100">
            <img src="./assets/usuarios/ney-vini.jpg"
                class="d-block w-100 h-100 object-fit-cover">

            <div class="carousel-caption d-flex flex-column justify-content-center h-100 pb-0" style="top: 0;">
                <h1>Aventura</h1>
                <p class="lead">Momentos inesquecíveis</p>
            </div>
        </div>

    </div>

    <button class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselFull"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next"
        type="button"
        data-bs-target="#carouselFull"
        data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>

<?php

$content = ob_get_clean();

require_once __DIR__ . '/layout/layout.php';
