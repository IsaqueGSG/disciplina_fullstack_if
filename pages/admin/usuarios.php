<?php
$title = "Admin Usuários";

require_once __DIR__ . '/../../controllers/usuarios.controller.php';
require_once __DIR__ . '/../../controllers/auth.controller.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $acao = $_POST['acao'] ?? null;

    if ($acao === 'bloquear') {
        bloquearUsuario($id);
    }
    if ($acao === 'desbloquear') {
        desbloquearUsuario($id);
    }
    if ($acao === 'role') {
        atualizarRole($id, $_POST['role']);
    }
}

$usuarios = listarUsuarios();
ob_start();
?>

<section class="min-vh-100 py-5">
    <div class="container-fluid mt-5 px-md-4">


        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-dark fw-bold">
                <i class="bi bi-people text-primary me-2"></i>Controle de Usuários
            </h1>
        </div>

        <div class="card card-outline card-navy border-0 shadow-sm rounded bg-white">
            <div class="card-header bg-transparent border-bottom pt-3">
                <h3 class="card-title fw-bold text-dark m-0"><i class="bi bi-shield-lock text-muted me-2"></i>Níveis de Acesso e Permissões</h3>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted uppercase text-xs">
                        <tr>
                            <th class="ps-4" style="width: 80px;">ID</th>
                            <th>Nome Completo</th>
                            <th>E-mail</th>
                            <th style="width: 180px;">Nível Operacional</th>
                            <th style="width: 140px;">Status</th>
                            <th class="text-end pe-4" style="width: 160px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#<?= $usuario['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($usuario['nome']) ?></span>
                                    </div>
                                </td>
                                <td class="text-muted"><?= htmlspecialchars($usuario['email']) ?></td>
                                <td>
                                    <form method="POST" class="m-0">
                                        <input type="hidden" name="acao" value="role">
                                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                        <select name="role" onchange="this.form.submit()" class="form-select form-select-sm fw-medium">
                                            <option value="cliente" <?= $usuario['role'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                                            <option value="admin" <?= $usuario['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($usuario['bloqueado']): ?>
                                        <span class="badge bg-danger-light text-danger border border-danger px-2.5 py-1.5 rounded-pill text-xs fw-bold">
                                            <i class="bi bi-x-circle-fill me-1"></i>Bloqueado
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success-light text-success border border-success px-2.5 py-1.5 rounded-pill text-xs fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i>Ativo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <form method="POST" class="m-0 d-inline">
                                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                        <?php if (!$usuario['bloqueado']): ?>
                                            <input type="hidden" name="acao" value="bloquear">
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-bold">
                                                <i class="bi bi-slash-circle me-1"></i>Bloquear
                                            </button>
                                        <?php else: ?>
                                            <input type="hidden" name="acao" value="desbloquear">
                                            <button type="submit" class="btn btn-sm btn-outline-success px-3 rounded-pill fw-bold">
                                                <i class="bi bi-shield-check me-1"></i>Liberar
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layout/layout.php';
