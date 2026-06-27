<?php
session_start();

require_once __DIR__ . '/../../controllers/empresa.controller.php';
require_once __DIR__ . '/../../controllers/auth.controller.php';

requireAdmin();

$empresa = buscarEmpresa();

$sucesso = null;
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = atualizarEmpresa(
        $_POST['nome'],
        $_POST['descricao'],
        $_POST['telefone'],
        $_POST['email'],
        $_POST['endereco'],
        $_POST['cidade'],
        $_POST['estado'],
        $_POST['mapa_iframe'],
        $_POST['instagram'],
        $_POST['facebook'],
        $_POST['youtube'],
        $_POST['whatsapp']
    );

    if ($ok) {
        $sucesso = "Configurações da empresa atualizadas com sucesso!";
        $empresa = buscarEmpresa();
    } else {
        $erro = "Houve um erro interno ao atualizar os dados da empresa.";
    }
}

$title = "Admin - Empresa";
ob_start();
?>
<section class="min-vh-100 py-5">
    <div class="container-fluid mt-5 px-md-4">
        <div class="row justify-content-center">
            <div class="col-xl-10">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="bi bi-gear text-primary me-2"></i>Configurações da Empresa
                    </h1>
                </div>

                <?php if ($sucesso): ?>
                    <div class="alert alert-success alert-dismissible shadow-sm mb-4">
                        <i class="icon bi bi-check-circle me-2"></i> <?= htmlspecialchars($sucesso) ?>
                    </div>
                <?php endif; ?>

                <?php if ($erro): ?>
                    <div class="alert alert-danger alert-dismissible shadow-sm mb-4">
                        <i class="icon bi bi-x-circle me-2"></i> <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <div class="card card-outline card-primary border-0 shadow-sm rounded">
                    <div class="card-header bg-transparent border-bottom pt-3">
                        <h3 class="card-title fw-bold text-dark m-0">Informações Institucionais e de Contato</h3>
                    </div>

                    <form method="POST">
                        <div class="card-body p-4">

                            <div class="row g-3 mb-4">
                                <div class="col-md-6 form-group">
                                    <label class="form-label text-dark fw-medium">Nome da Empresa</label>
                                    <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($empresa['nome']) ?>" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="form-label text-dark fw-medium">E-mail de Atendimento</label>
                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($empresa['email']) ?>" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="form-label text-dark fw-medium">Telefone Comercial</label>
                                    <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($empresa['telefone']) ?>">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="form-label text-dark fw-medium">WhatsApp corporativo</label>
                                    <input type="text" class="form-control" name="whatsapp" value="<?= htmlspecialchars($empresa['whatsapp']) ?>">
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label text-dark fw-medium">Sobre a Empresa (Descrição)</label>
                                <textarea class="form-control" name="descricao" rows="4"><?= htmlspecialchars($empresa['descricao']) ?></textarea>
                            </div>

                            <div class="row g-3 mb-4 border-top pt-4">
                                <div class="col-12 form-group">
                                    <label class="form-label text-dark fw-medium">Endereço Completo</label>
                                    <input type="text" class="form-control" name="endereco" value="<?= htmlspecialchars($empresa['endereco']) ?>">
                                </div>

                                <div class="col-md-8 form-group">
                                    <label class="form-label text-dark fw-medium">Cidade</label>
                                    <input type="text" class="form-control" name="cidade" value="<?= htmlspecialchars($empresa['cidade']) ?>">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label class="form-label text-dark fw-medium">Estado (UF)</label>
                                    <input type="text" class="form-control" name="estado" value="<?= htmlspecialchars($empresa['estado']) ?>">
                                </div>

                                <div class="col-12 form-group">
                                    <label class="form-label text-dark fw-medium">Iframe do Google Maps (Código HTML)</label>
                                    <textarea class="form-control font-monospace text-sm" name="mapa_iframe" rows="2" placeholder="<iframe src='...'></iframe>"><?= htmlspecialchars($empresa['mapa_iframe']) ?></textarea>
                                </div>
                            </div>

                            <div class="border-top pt-4">
                                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-share me-2 text-muted"></i>Redes Sociais</h5>

                                <div class="row g-3">
                                    <div class="col-md-4 form-group">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-instagram text-danger"></i></span>
                                            <input type="url" class="form-control" name="instagram" value="<?= htmlspecialchars($empresa['instagram']) ?>" placeholder="URL do Instagram">
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-facebook text-primary"></i></span>
                                            <input type="url" class="form-control" name="facebook" value="<?= htmlspecialchars($empresa['facebook']) ?>" placeholder="URL do Facebook">
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-youtube text-danger"></i></span>
                                            <input type="url" class="form-control" name="youtube" value="<?= htmlspecialchars($empresa['youtube']) ?>" placeholder="URL do YouTube">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary fw-bold px-4 rounded shadow-sm">
                                <i class="bi bi-check2-circle me-2"></i>Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layout/layout.php';
