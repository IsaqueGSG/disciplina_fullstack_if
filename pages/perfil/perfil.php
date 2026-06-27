<?php

$title = "Meu Perfil";
$pageCss = "/perfil.css"; // Caso queira adicionar estilos customizados depois

require_once __DIR__ . '/../../controllers/auth.controller.php';
require_once __DIR__ . '/../../controllers/usuarios.controller.php';
require_once __DIR__ . '/../../controllers/comentarios.controller.php';
require_once __DIR__ . '/../../controllers/reservas.controller.php';

// Proteção: Se não estiver logado, joga para o login
$usuarioLogado = usuarioLogado();
if (!$usuarioLogado) {
    header("Location: /projeto_fullstack/pages/login/login.php");
    exit;
}

$usuarioId = $usuarioLogado['id'];

// Feedbacks para o usuário
$mensagemSucesso = false;
$mensagemErro = false;

// ----------------------------------------------------
// PROCESSAMENTO DOS FORMULÁRIOS (POST)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Atualizar dados do Perfil
    if (isset($_POST['atualizar_perfil'])) {
        $nome = trim($_POST['nome']);

        if (!empty($nome)) {
            // Se você tiver uma função no usuarios.controller para atualizar o nome, chame-a aqui.
            // Exemplo usando a conexão diretamente para simplificar se não tiver a função pronta:
            global $conexao;
            $stmt = $conexao->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
            $stmt->bind_param("si", $nome, $usuarioId);

            if ($stmt->execute()) {
                // Atualiza a sessão para refletir a mudança imediatamente no layout
                $_SESSION['usuario']['nome'] = $nome;
                $mensagemSucesso = "Dados atualizados com sucesso!";
                $usuarioLogado = usuarioLogado(); // Recarrega a variável
            } else {
                $mensagemErro = "Erro ao atualizar os dados.";
            }
        }
    }

    // 2. Excluir Comentário
    if (isset($_POST['excluir_comentario'])) {
        $comentarioId = intval($_POST['comentario_id']);

        // Segurança extra: Garantir que o comentário pertence mesmo ao usuário logado
        global $conexao;
        $stmtCheck = $conexao->prepare("SELECT id FROM comentarios WHERE id = ? AND usuario_id = ?");
        $stmtCheck->bind_param("ii", $comentarioId, $usuarioId);
        $stmtCheck->execute();
        $resultadoCheck = $stmtCheck->get_result();

        if ($resultadoCheck->num_rows > 0) {
            // Se pertence, podemos apagar (ou se tiver a função deletarComentario no controller, use-a)
            $stmtDelete = $conexao->prepare("DELETE FROM comentarios WHERE id = ?");
            $stmtDelete->bind_param("i", $comentarioId);
            if ($stmtDelete->execute()) {
                $mensagemSucesso = "Comentário excluído com sucesso!";
            } else {
                $mensagemErro = "Erro ao excluir o comentário.";
            }
        } else {
            $mensagemErro = "Ação não permitida.";
        }
    }
}

// ----------------------------------------------------
// BUSCA DOS DADOS PARA EXIBIÇÃO
// ----------------------------------------------------
// Buscar os dados frescos do usuário
$dadosUsuario = buscarUsuario($usuarioId);

// Buscar apenas os comentários deste usuário específicos (com dados do produto)
global $conexao;
$stmtComentarios = $conexao->prepare("
    SELECT comentarios.*, produtos.nome AS produto_nome, produtos.imagem AS produto_imagem
    FROM comentarios
    INNER JOIN produtos ON comentarios.produto_id = produtos.id
    WHERE comentarios.usuario_id = ?
    ORDER BY comentarios.criado_em DESC
");
$stmtComentarios->bind_param("i", $usuarioId);
$stmtComentarios->execute();
$meusComentarios = $stmtComentarios->get_result()->fetch_all(MYSQLI_ASSOC);
$minhasReservas = listarReservasUsuario($usuarioId);

ob_start();
?>

<section class="min-vh-100 py-5 bg-light">
    <div class="container mt-5">

        <h1 class="fw-bold text-dark mb-4"><i class="bi bi-person-circle text-primary me-2"></i>Minha Conta</h1>

        <?php if ($mensagemSucesso): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $mensagemSucesso ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $mensagemErro ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3 position-relative d-inline-block">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($dadosUsuario['nome']) ?>&background=0D6EFD&color=fff&size=128"
                                class="rounded-circle shadow-sm border border-4 border-white" alt="Avatar">
                        </div>
                        <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($dadosUsuario['nome']) ?></h4>
                        <p class="text-muted small mb-3"><?= htmlspecialchars($dadosUsuario['email']) ?></p>
                        <span class="badge bg-primary uppercase text-xs px-3 py-2 rounded-pill">
                            <?= $dadosUsuario['role'] === 'admin' ? 'Administrador' : 'Cliente' ?>
                        </span>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded">
                    <div class="card-header bg-transparent border-bottom pt-3">
                        <h5 class="fw-bold m-0"><i class="bi bi-gear me-2 text-primary"></i>Editar Dados</h5>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body p-4">
                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold" for="nome">Nome Completo</label>
                                <input type="text" name="nome" id="nome" class="form-control"
                                    value="<?= htmlspecialchars($dadosUsuario['nome']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label text-muted small fw-bold" for="email">E-mail (Não alterável)</label>
                                <input type="email" id="email" class="form-control bg-light text-muted"
                                    value="<?= htmlspecialchars($dadosUsuario['email']) ?>" readonly>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
                            <button type="submit" name="atualizar_perfil" class="btn btn-primary fw-bold px-4 rounded shadow-sm btn-sm">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded">

                    <div class="card-header bg-white border-bottom-0 pb-0">

                        <ul class="nav nav-tabs card-header-tabs" role="tablist">

                            <li class="nav-item">
                                <button
                                    class="nav-link active fw-bold"
                                    data-bs-toggle="tab"
                                    data-bs-target="#avaliacoes"
                                    type="button">
                                    <i class="bi bi-chat-left-text me-2"></i>
                                    Minhas Avaliações
                                    <span class="badge bg-primary ms-2"><?= count($meusComentarios) ?></span>
                                </button>
                            </li>

                            <li class="nav-item">
                                <button
                                    class="nav-link fw-bold"
                                    data-bs-toggle="tab"
                                    data-bs-target="#reservas"
                                    type="button">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    Minhas Reservas
                                    <span class="badge bg-success ms-2"><?= count($minhasReservas) ?></span>
                                </button>
                            </li>

                        </ul>

                    </div>

                    <div class="card-body">

                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="avaliacoes">

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light text-xs uppercase text-muted">
                                            <tr>
                                                <th>Produto</th>
                                                <th>Nota</th>
                                                <th>Comentário</th>
                                                <th class="text-end">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($meusComentarios as $com): ?>
                                                <tr>
                                                    <td style="min-width: 150px;">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="../../assets/produtos/<?= htmlspecialchars($com['produto_imagem']) ?>"
                                                                style="width: 40px; height: 40px; object-fit: cover;" class="rounded" alt="">
                                                            <span class="fw-bold text-dark small text-truncate" style="max-width: 120px;">
                                                                <?= htmlspecialchars($com['produto_nome']) ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-warning fw-bold">
                                                            <?= $com['nota'] ?> <i class="bi bi-star-fill text-xs"></i>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <p class="m-0 small text-muted text-wrap" style="max-width: 250px;">
                                                            <?= htmlspecialchars($com['comentario']) ?>
                                                        </p>
                                                        <span class="text-muted d-block" style="font-size: 10px;">
                                                            <?= date('d/m/Y H:i', strtotime($com['criado_em'])) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <form action="" method="POST" onsubmit="return confirm('Deseja mesmo excluir esta avaliação?');" style="display:inline-block;">
                                                            <input type="hidden" name="comentario_id" value="<?= $com['id'] ?>">
                                                            <button type="submit" name="excluir_comentario" class="btn btn-outline-danger btn-sm rounded shadow-sm" title="Excluir">
                                                                <i class="bi bi-trash3-fill"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="reservas">


                                <div class="card border-0 shadow-sm rounded mt-4">
                                    <div class="card-header bg-transparent border-bottom pt-3">
                                        <h5 class="fw-bold m-0">
                                            <i class="bi bi-calendar-check me-2 text-primary"></i>
                                            Minhas Reservas (<?= count($minhasReservas) ?>)
                                        </h5>
                                    </div>

                                    <div class="card-body">

                                        <?php if (count($minhasReservas) == 0): ?>

                                            <div class="text-center text-muted py-5">
                                                <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
                                                Você ainda não realizou nenhuma reserva.
                                            </div>

                                        <?php else: ?>

                                            <div class="table-responsive">

                                                <table class="table table-hover align-middle">

                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Produto</th>
                                                            <th>Data</th>
                                                            <th>Hora</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        <?php foreach ($minhasReservas as $r): ?>

                                                            <?php

                                                            $badge = "bg-warning text-dark";

                                                            if ($r['status'] == "Aprovada")
                                                                $badge = "bg-success";

                                                            if ($r['status'] == "Cancelada")
                                                                $badge = "bg-danger";

                                                            ?>

                                                            <tr>

                                                                <td>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <img
                                                                            src="../../assets/produtos/<?= htmlspecialchars($r['produto_imagem']) ?>"
                                                                            width="45"
                                                                            class="rounded">

                                                                        <?= htmlspecialchars($r['produto_nome']) ?>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <?= date('d/m/Y', strtotime($r['data_reserva'])) ?>
                                                                </td>

                                                                <td>
                                                                    <?= date('H:i', strtotime($r['hora_reserva'])) ?>
                                                                </td>

                                                                <td>
                                                                    <span class="badge <?= $badge ?>">
                                                                        <?= htmlspecialchars($r['status']) ?>
                                                                    </span>
                                                                </td>

                                                            </tr>

                                                        <?php endforeach; ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        <?php endif; ?>

                                    </div>
                                </div>

                            </div>

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
