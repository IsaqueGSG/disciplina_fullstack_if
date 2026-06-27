<?php

$title = "Comentários";
$pageCss = "/comentarios.css";

require_once __DIR__ . '/../../controllers/produtos.controller.php';
require_once __DIR__ . '/../../controllers/comentarios.controller.php';
require_once __DIR__ . '/../../controllers/auth.controller.php';
require_once __DIR__ . '/../../controllers/usuarios.controller.php'; 

$mensagemSucesso = false;
$mensagemErro = false;

// Processamento da exclusão do Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_comentario'])) {
    if (isAdmin()) {
        $comentarioId = intval($_POST['comentario_id']);

        if (excluirComentario($comentarioId)) {
            $mensagemSucesso = "Comentário excluído com sucesso pelo Administrador.";
        } else {
            $mensagemErro = "Erro ao tentar excluir o comentário.";
        }
    } else {
        $mensagemErro = "Ação não permitida.";
    }
}

// ----------------------------------------------------
// FILTROS INDEPENDENTES (PRODUTO VS CLIENTE)
// ----------------------------------------------------
$produtoId = $_GET['id'] ?? null;
$usuarioId = $_GET['usuario_id'] ?? null;

$produto = null;
$cliente = null;
$comentarios = [];

if ($produtoId) {
    $produto = buscarProduto($produtoId);
    if ($produto) {
        $comentarios = listarComentariosProduto($produtoId);
    } else {
        $comentarios = listarComentarios();
    }
} elseif ($usuarioId) {
    $cliente = buscarUsuario($usuarioId);
    if ($cliente) {
        global $conexao;
        $stmt = $conexao->prepare("
            SELECT comentarios.*, usuarios.nome AS usuario_nome, usuarios.foto, produtos.nome AS produto_nome
            FROM comentarios
            INNER JOIN usuarios ON comentarios.usuario_id = usuarios.id
            INNER JOIN produtos ON comentarios.produto_id = produtos.id
            WHERE comentarios.usuario_id = ?
            ORDER BY produtos.nome ASC, comentarios.criado_em DESC
        ");
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $comentarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $comentarios = listarComentarios();
    }
} else {
    $comentarios = listarComentarios();
}

// ----------------------------------------------------
// AGRUPAMENTO POR PRODUTO (CASO SEJA FILTRO DE CLIENTE)
// ----------------------------------------------------
$comentariosAgrupados = [];
if ($cliente) {
    foreach ($comentarios as $c) {
        $nomeProduto = $c['produto_nome'] ?? 'Outros';
        $comentariosAgrupados[$nomeProduto][] = $c;
    }
}

ob_start();

?>

<section class="min-vh-100 py-5">
    <div class="container mt-5">

        <?php if ($mensagemSucesso): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 text-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $mensagemSucesso ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $mensagemErro ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($produto): ?>
            <h1 class="mb-5 fw-bold text-dark text-center">
                Avaliações sobre <span class="text-primary"><?= htmlspecialchars($produto['nome']) ?></span>
            </h1>
        <?php elseif ($cliente): ?>
            <h1 class="mb-5 fw-bold text-dark text-center">
                Avaliações feitas por <span class="text-primary"><?= htmlspecialchars($cliente['nome']) ?></span>
            </h1>
        <?php else: ?>
            <h1 class="mb-5 fw-bold text-dark text-center">
                Comentários dos <span class="text-primary">Clientes</span>
            </h1>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <?php if ($cliente && count($comentariosAgrupados) > 0): ?>
                    
                    <?php foreach ($comentariosAgrupados as $nomeDoProduto => $listaComentarios): ?>
                        
                        <div class="mt-4 mb-3 border-bottom pb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam text-primary fs-4"></i>
                            <h3 class="h4 m-0 fw-bold text-secondary"><?= htmlspecialchars($nomeDoProduto) ?></h3>
                            <span class="badge bg-secondary rounded-pill small"><?= count($listaComentarios) ?></span>
                        </div>

                        <?php foreach ($listaComentarios as $comentario): ?>
                            <div class="card card-widget shadow-sm border-0 rounded mb-4 bg-white position-relative">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="position-relative overflow-hidden rounded-circle bg-light border" style="width: 55px; height: 55px; min-width: 55px;">
                                            <img src="../../assets/usuarios/<?= htmlspecialchars($comentario['foto'] ?? 'default.webp') ?>" class="position-absolute top-50 start-50 translate-middle w-100 h-100" style="object-fit: cover;" alt="Avatar">
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <h5 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($comentario['usuario_nome']) ?></h5>
                                        </div>
                                        <div class="text-warning font-weight-bold bg-light px-3 py-1 rounded border-start border-warning border-3 d-flex align-items-center gap-2">
                                            <span class="fs-6"><?= (int)$comentario['nota'] ?></span> <i class="bi bi-star-fill text-warning small"></i>
                                            <?php if (isAdmin()): ?>
                                                <form action="" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar este comentário permanentemente?');" class="ms-2 d-inline">
                                                    <input type="hidden" name="comentario_id" value="<?= $comentario['id'] ?>">
                                                    <button type="submit" name="excluir_comentario" class="btn btn-sm btn-outline-danger border-0 p-1 lh-1 rounded">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="text-muted fs-5 m-0 pt-2 border-top border-light line-height-md" style="font-style: italic;">
                                        "<?= htmlspecialchars($comentario['comentario']) ?>"
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    <?php endforeach; ?>

                <?php else: ?>

                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="card card-widget shadow-sm border-0 rounded mb-4 bg-white position-relative">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="position-relative overflow-hidden rounded-circle bg-light border" style="width: 55px; height: 55px; min-width: 55px;">
                                        <img src="../../assets/usuarios/<?= htmlspecialchars($comentario['foto'] ?? 'default.webp') ?>" class="position-absolute top-50 start-50 translate-middle w-100 h-100" style="object-fit: cover;" alt="Avatar">
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h5 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($comentario['usuario_nome']) ?></h5>
                                        <?php if (!empty($comentario['produto_nome'])): ?>
                                            <span class="badge bg-light text-primary border mt-1 fs-7">
                                                <i class="bi bi-box-seam me-1"></i> <?= htmlspecialchars($comentario['produto_nome']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-warning font-weight-bold bg-light px-3 py-1 rounded border-start border-warning border-3 d-flex align-items-center gap-2">
                                        <span class="fs-6"><?= (int)$comentario['nota'] ?></span> <i class="bi bi-star-fill text-warning small"></i>
                                        <?php if (isAdmin()): ?>
                                            <form action="" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar este comentário permanentemente?');" class="ms-2 d-inline">
                                                <input type="hidden" name="comentario_id" value="<?= $comentario['id'] ?>">
                                                <button type="submit" name="excluir_comentario" class="btn btn-sm btn-outline-danger border-0 p-1 lh-1 rounded">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="text-muted fs-5 m-0 pt-2 border-top border-light line-height-md" style="font-style: italic;">
                                    "<?= htmlspecialchars($comentario['comentario']) ?>"
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

                <?php if (count($comentarios) === 0): ?>
                    <div class="alert alert-info alert-dismissible shadow-sm text-center mt-5">
                        <h5><i class="icon bi bi-info-circle me-2"></i> Sem avaliações</h5>
                        Nenhuma avaliação correspondente foi encontrada.
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</section>

<?php

$content = ob_get_clean();

require_once __DIR__ . '/../../layout/layout.php';