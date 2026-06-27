<?php

$title = "Preços";
$pageCss = "/precos.css";

require_once __DIR__ . '/../../controllers/produtos.controller.php';
require_once __DIR__ . '/../../controllers/auth.controller.php';
require_once __DIR__ . '/../../controllers/reservas.controller.php'; 

$usuarioLogado = usuarioLogado();
$id = $_GET['id'] ?? null;

if ($id) {
    $produtoEncontrado = buscarProduto($id);
    $produtos = $produtoEncontrado ? [$produtoEncontrado] : [];
} else {
    $produtos = listarProdutos();
}

$mensagemSucesso = false;
$mensagemErro = false;

// Processa o envio do formulário de reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar_reserva'])) {
    if ($usuarioLogado) {
        $usuarioId  = $usuarioLogado['id'];
        $produtoId  = isset($_POST['produto_id']) ? intval($_POST['produto_id']) : 0;
        $data       = isset($_POST['data_reserva']) ? $_POST['data_reserva'] : '';
        $hora       = isset($_POST['hora_reserva']) ? $_POST['hora_reserva'] : '';

        if ($produtoId > 0 && !empty($data) && !empty($hora)) {
            if (criarReserva($usuarioId, $produtoId, $data, $hora)) {
                $mensagemSucesso = true;
            } else {
                $mensagemErro = true;
            }
        } else {
            $mensagemErro = true;
        }
    }
}

ob_start();
?>

<section class="min-vh-100 py-5">
    <div class="container mt-5">

        <h1 class="text-center mb-5 fw-bold text-dark">
            Tabela de <span class="text-primary">Preços & Reservas</span>
        </h1>

        <?php if ($mensagemSucesso): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 text-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Sua solicitação de reserva foi enviada com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Erro ao processar reserva. Verifique os dados fornecidos.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php foreach ($produtos as $produto): ?>
            <?php if (!$produto) continue; ?>
            <?php $precoHora = $produto['preco']; ?>

            <div class="card card-outline card-primary border-0 shadow-sm rounded mb-4">

                <div class="card-header d-flex align-items-center bg-transparent border-bottom-0 pt-4 px-4">
                    <h4 class="card-title fw-bold text-dark flex-grow-1 m-0">
                        <?= htmlspecialchars($produto['nome']) ?>
                    </h4>

                    <div class="d-flex gap-2">
                        <a href="/projeto_fullstack/pages/detalhes/detalhes.php?id=<?= $produto['id'] ?>"
                            class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-bold">
                            <i class="bi bi-info-circle me-1"></i> Detalhes
                        </a>
                        
                        <button type="button" class="btn btn-sm btn-primary px-4 rounded-pill fw-bold shadow-sm" 
                                data-bs-toggle="modal" data-bs-target="#modalReserva<?= $produto['id'] ?>">
                            <i class="bi bi-calendar-check me-1"></i> Reservar
                        </button>
                    </div>
                </div>

                <div class="card-body px-4 pb-4 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="py-3 ps-4" style="border-top-left-radius: 6px;">Duração</th>
                                    <th scope="col" class="py-3 pe-4 text-end" style="border-top-right-radius: 6px;">Preço Estimado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-3 ps-4 fw-medium">1 Hora</td>
                                    <td class="py-3 pe-4 text-end text-success fw-bold fs-5">R$ <?= number_format($precoHora, 2, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td class="py-3 ps-4 fw-medium">2 Horas</td>
                                    <td class="py-3 pe-4 text-end text-success fw-bold fs-5">R$ <?= number_format($precoHora * 2, 2, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td class="py-3 ps-4 fw-medium">4 Horas</td>
                                    <td class="py-3 pe-4 text-end text-success fw-bold fs-5">R$ <?= number_format($precoHora * 4, 2, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td class="py-3 ps-4 fw-medium"><span class="badge bg-primary me-2">Melhor Custo</span>Diária (8h)</td>
                                    <td class="py-3 pe-4 text-end text-success fw-bold fs-5">R$ <?= number_format($precoHora * 8, 2, ',', '.') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalReserva<?= $produto['id'] ?>" unset-tabindex="-1" aria-labelledby="labelReserva<?= $produto['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold" id="labelReserva<?= $produto['id'] ?>">
                                <i class="bi bi-calendar2-range me-2"></i>Solicitar Reserva
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <?php if ($usuarioLogado): ?>
                            <form action="" method="POST">
                                <div class="modal-body p-4">
                                    <p class="text-muted">Você está reservando: <strong class="text-dark"><?= htmlspecialchars($produto['nome']) ?></strong></p>
                                    
                                    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                                    
                                    <div class="mb-3">
                                        <label for="data_reserva<?= $produto['id'] ?>" class="form-label fw-medium text-dark">Escolha a Data</label>
                                        <input type="date" name="data_reserva" id="data_reserva<?= $produto['id'] ?>" class="form-control" min="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label for="hora_reserva<?= $produto['id'] ?>" class="form-label fw-medium text-dark">Horário de Início</label>
                                        <input type="time" name="hora_reserva" id="hora_reserva<?= $produto['id'] ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light border-top-0">
                                    <button type="button" class="btn btn-secondary fw-bold rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="solicitar_reserva" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm">Confirmar Reserva</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="modal-body p-4 text-center">
                                <i class="bi bi-lock text-muted display-4 mb-3 d-block"></i>
                                <h5 class="fw-bold text-dark mb-2">Login Requerido</h5>
                                <p class="text-muted mb-4">Você precisa estar logado na sua conta para solicitar o agendamento de um veículo.</p>
                                <a href="/projeto_fullstack/pages/login/login.php" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Ir para Login
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

        <?php if (count($produtos) === 0): ?>
            <div class="alert alert-warning alert-dismissible shadow-sm text-center mt-5">
                <h5><i class="icon bi bi-exclamation-triangle me-2"></i> Sem registros!</h5>
                Nenhum preço ou veículo encontrado no momento.
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layout/layout.php';
?>