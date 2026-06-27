<?php

$title = "Painel de Reservas";
$pageCss = "/admin.css";

require_once __DIR__ . '/../../controllers/auth.controller.php';
require_once __DIR__ . '/../../controllers/reservas.controller.php';

// Proteção da página: Garante que apenas administradores acessem
requireAdmin();

$mensagemSucesso = false;
$mensagemErro = false;

// Processamento das ações do CRUD (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ação: Atualizar Status
    if (isset($_POST['acao']) && $_POST['acao'] === 'alterar_status') {
        $id = intval($_POST['reserva_id']);
        $novoStatus = $_POST['status'];
        
        if (atualizarStatusReserva($id, $novoStatus)) {
            $mensagemSucesso = "Status da reserva atualizado com sucesso!";
        } else {
            $mensagemErro = "Erro ao atualizar o status da reserva.";
        }
    }

    // Ação: Excluir Reserva
    if (isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
        $id = intval($_POST['reserva_id']);
        
        if (excluirReserva($id)) {
            $mensagemSucesso = "Reserva excluída permanentemente.";
        } else {
            $mensagemErro = "Erro ao excluir a reserva.";
        }
    }
}

// Busca a lista atualizada de reservas após qualquer modificação
$reservas = listarReservasAdmin();

ob_start();
?>

<section class="py-5 min-vh-100">
    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h1 class="fw-bold text-dark m-0">Gerenciar <span class="text-primary">Reservas</span></h1>
            <span class="badge bg-primary fs-6 rounded-pill"><?= count($reservas) ?> Agendamento(s)</span>
        </div>

        <?php if ($mensagemSucesso): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $mensagemSucesso ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $mensagemErro ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (count($reservas) === 0): ?>
            <div class="alert alert-info text-center py-5 shadow-sm border-0">
                <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                <h5 class="fw-bold m-0">Nenhuma solicitação de reserva encontrada.</h5>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="ps-4 py-3">Cliente / Contato</th>
                                <th scope="col" class="py-3">Veículo</th>
                                <th scope="col" class="py-3">Data / Hora</th>
                                <th scope="col" class="py-3 text-center">Status</th>
                                <th scope="col" class="pe-4 py-3 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $r): ?>
                                <?php 
                                    // Definição de cores conforme o status do agendamento
                                    $badgeColor = 'bg-warning text-dark';
                                    if ($r['status'] === 'Aprovada') $badgeColor = 'bg-success';
                                    if ($r['status'] === 'Cancelada') $badgeColor = 'bg-danger';
                                ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <strong class="text-dark d-block"><?= htmlspecialchars($r['cliente_nome']) ?></strong>
                                        <span class="text-muted small"><?= htmlspecialchars($r['cliente_email']) ?></span>
                                    </td>
                                    <td class="py-3 fw-medium text-secondary">
                                        <?= htmlspecialchars($r['produto_nome']) ?>
                                    </td>
                                    <td class="py-3">
                                        <span class="d-block text-dark fw-bold"><?= date('d/m/Y', strtotime($r['data_reserva'])) ?></span>
                                        <span class="text-muted small"><i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($r['hora_reserva'])) ?></span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge <?= $badgeColor ?> px-3 py-2 rounded-pill fw-bold">
                                            <?= htmlspecialchars($r['status']) ?>
                                        </span>
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-inline-flex gap-1">
                                            
                                            <?php if ($r['status'] !== 'Aprovada'): ?>
                                                <form action="" method="POST" class="m-0">
                                                    <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                                                    <input type="hidden" name="status" value="Aprovada">
                                                    <input type="hidden" name="acao" value="alterar_status">
                                                    <button type="submit" class="btn btn-sm btn-success rounded-circle shadow-sm" title="Aprovar Agendamento">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($r['status'] !== 'Cancelada'): ?>
                                                <form action="" method="POST" class="m-0">
                                                    <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                                                    <input type="hidden" name="status" value="Cancelada">
                                                    <input type="hidden" name="acao" value="alterar_status">
                                                    <button type="submit" class="btn btn-sm btn-warning text-dark rounded-circle shadow-sm" title="Cancelar Agendamento">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <form action="" method="POST" class="m-0" onsubmit="return confirm('Tem certeza absoluta que deseja excluir permanentemente esta reserva?');">
                                                <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                                                <input type="hidden" name="acao" value="excluir">
                                                <button type="submit" class="btn btn-sm btn-danger rounded-circle shadow-sm" title="Excluir Registro">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layout/layout.php';
?>