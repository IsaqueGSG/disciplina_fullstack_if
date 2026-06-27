<?php

$title = "Contato";
$pageCss = "/contato.css";

require_once __DIR__ . '/../../controllers/empresa.controller.php';
require_once __DIR__ . '/../../controllers/contatos.controller.php'; // Incluindo o novo controller

$empresa = buscarEmpresa();

$mensagemSucesso = false;
$mensagemErro = false;

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_contato'])) {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : '';

    if (!empty($nome) && !empty($email) && !empty($mensagem)) {
        if (salvarContato($nome, $email, $mensagem)) {
            $mensagemSucesso = true;
        } else {
            $mensagemErro = true;
        }
    } else {
        $mensagemErro = true;
    }
}

ob_start();

?>

<section class="min-vh-100 py-5">
    <div class="container mt-5">

        <h1 class="text-center mb-5 fw-bold text-dark">
            Entre em <span class="text-primary">Contato</span>
        </h1>

        <?php if ($mensagemSucesso): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 text-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Sua mensagem foi enviada e salva com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Erro ao enviar a mensagem. Preencha todos os campos corretamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <div class="col-md-5">
                <div class="card card-outline card-primary border-0 shadow-sm rounded h-100 bg-white">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                        <h4 class="card-title fw-bold text-dark m-0">
                            <i class="bi bi-info-circle text-primary me-2"></i>Nossos Dados
                        </h4>
                    </div>

                    <div class="card-body px-4 pb-4">
                        <div class="d-flex flex-column gap-3 mt-2">
                            <div class="d-flex align-items-start p-2 rounded hover-light">
                                <div class="bg-light text-primary rounded p-3 me-3">
                                    <i class="bi bi-building fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Empresa</span>
                                    <strong class="text-dark fs-5"><?= htmlspecialchars($empresa['nome']) ?></strong>
                                </div>
                            </div>

                            <div class="d-flex align-items-start p-2 rounded hover-light">
                                <div class="bg-light text-success rounded p-3 me-3">
                                    <i class="bi bi-telephone fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Telefone</span>
                                    <strong class="text-dark fs-5"><?= htmlspecialchars($empresa['telefone']) ?></strong>
                                </div>
                            </div>

                            <div class="d-flex align-items-start p-2 rounded hover-light">
                                <div class="bg-light text-warning rounded p-3 me-3">
                                    <i class="bi bi-envelope fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">E-mail</span>
                                    <strong class="text-dark fs-5"><?= htmlspecialchars($empresa['email']) ?></strong>
                                </div>
                            </div>

                            <div class="d-flex align-items-start p-2 rounded hover-light">
                                <div class="bg-light text-danger rounded p-3 me-3">
                                    <i class="bi bi-geo-alt fs-4"></i>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Endereço</span>
                                    <strong class="text-dark fs-6"><?= htmlspecialchars($empresa['endereco']) ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card border-0 shadow-sm rounded h-100 bg-white">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                        <h4 class="card-title fw-bold text-dark m-0">
                            <i class="bi bi-envelope-paper text-primary me-2"></i>Envie uma Mensagem
                        </h4>
                    </div>

                    <div class="card-body px-4 pb-4">
                        <form action="" method="POST">

                            <div class="form-group mb-3">
                                <label class="form-label text-dark fw-medium" for="txtNome">Nome Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="nome" id="txtNome" class="form-control border-start-0 bg-light-focus" placeholder="Digite seu nome..." required>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label text-dark fw-medium" for="txtEmail">E-mail Corporativo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                    <input type="email" name="email" id="txtEmail" class="form-control border-start-0 bg-light-focus" placeholder="exemplo@email.com" required>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label text-dark fw-medium" for="txtMensagem">Mensagem ou Dúvida</label>
                                <textarea name="mensagem" id="txtMensagem" rows="4" class="form-control bg-light-focus" placeholder="Como podemos ajudar você hoje?" required></textarea>
                            </div>

                            <button type="submit" name="enviar_contato" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-pill">
                                <i class="bi bi-send me-2"></i> Enviar Mensagem
                            </button>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php

$content = ob_get_clean();

require_once __DIR__ . '/../../layout/layout.php';