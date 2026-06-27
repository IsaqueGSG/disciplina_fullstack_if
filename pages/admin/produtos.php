<?php
$title = "Admin Produtos";

require_once __DIR__ . '/../../controllers/produtos.controller.php';
require_once __DIR__ . '/../../controllers/auth.controller.php';

requireAdmin();

$acao = $_POST['acao'] ?? null;

if ($acao === 'criar') {
    $imagem = '';
    if (!empty($_FILES['imagem']['name'])) {
        $imagem = basename($_FILES['imagem']['name']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], __DIR__ . '/../../assets/produtos/' . $imagem);
    }
    criarProduto($_POST['nome'], $_POST['descricao'], $_POST['preco'], $imagem);
}

if ($acao === 'editar') {
    $imagem = $_POST['imagem_atual'];
    if (!empty($_FILES['imagem']['name'])) {
        $imagem = basename($_FILES['imagem']['name']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], __DIR__ . '/../../assets/produtos/' . $imagem);
    }
    atualizarProduto($_POST['id'], $_POST['nome'], $_POST['descricao'], $_POST['preco'], $imagem);
    header("Location: /projeto_fullstack/pages/admin/produtos.php");
    exit;
}

if ($acao === 'excluir') {
    excluirProduto($_POST['id']);
}

$produtoEditar = null;
if (isset($_GET['editar'])) {
    $produtoEditar = buscarProduto($_GET['editar']);
}

$produtos = listarProdutos();
ob_start();
?>
<section class="min-vh-100 py-5">
    <div class="container-fluid mt-5 px-md-4">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-dark fw-bold">
                <i class="bi bi-box-seam text-primary me-2"></i>Administração de Produtos
            </h1>
        </div>

        <div class="row g-4">

            <div class="col-lg-4">
                <div class="card card-outline <?= $produtoEditar ? 'card-warning' : 'card-success' ?> border-0 shadow-sm rounded bg-white">
                    <div class="card-header bg-transparent border-bottom pt-3">
                        <h3 class="card-title fw-bold text-dark m-0">
                            <?= $produtoEditar ? '<i class="bi bi-pencil-square text-warning me-2"></i>Editar Produto' : '<i class="bi bi-plus-circle text-success me-2"></i>Novo Produto' ?>
                        </h3>
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="card-body p-3">
                            <input type="hidden" name="acao" value="<?= $produtoEditar ? 'editar' : 'criar' ?>">

                            <?php if ($produtoEditar): ?>
                                <input type="hidden" name="id" value="<?= $produtoEditar['id'] ?>">
                                <input type="hidden" name="imagem_atual" value="<?= $produtoEditar['imagem'] ?>">
                            <?php endif; ?>

                            <div class="form-group mb-3">
                                <label class="form-label text-dark fw-medium">Nome do Produto</label>
                                <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($produtoEditar['nome'] ?? '') ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label text-dark fw-medium">Preço (R$)</label>
                                <input type="number" step="0.01" name="preco" class="form-control" required value="<?= htmlspecialchars($produtoEditar['preco'] ?? '') ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label text-dark fw-medium">Descrição</label>
                                <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($produtoEditar['descricao'] ?? '') ?></textarea>
                            </div>

                            <div class="form-group mb-2">
                                <label class="form-label text-dark fw-medium">Imagem do Produto</label>
                                <input type="file" name="imagem" class="form-control">
                                <?php if ($produtoEditar && !empty($produtoEditar['imagem'])): ?>
                                    <div class="mt-2 text-center border rounded p-2 bg-light">
                                        <small class="text-muted d-block mb-1">Imagem Atual:</small>
                                        <img src="../../assets/produtos/<?= $produtoEditar['imagem'] ?>" width="60" class="img-thumbnail">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-top p-3 d-flex gap-2">
                            <button type="submit" class="btn <?= $produtoEditar ? 'btn-warning' : 'btn-success' ?> flex-grow-1 fw-bold">
                                <i class="bi bi-check-lg me-1"></i><?= $produtoEditar ? 'Atualizar' : 'Cadastrar' ?>
                            </button>
                            <?php if ($produtoEditar): ?>
                                <a href="/projeto_fullstack/pages/admin/produtos.php" class="btn btn-secondary"><i class="bi bi-x-lg"></i></a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded bg-white">
                    <div class="card-header bg-transparent border-bottom pt-3">
                        <h3 class="card-title fw-bold text-dark m-0"><i class="bi bi-list-stars text-muted me-2"></i>Produtos Cadastrados</h3>
                    </div>

                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted uppercase text-xs">
                                <tr>
                                    <th class="ps-4" style="width: 70px;">ID</th>
                                    <th style="width: 100px;">Imagem</th>
                                    <th>Nome do Produto</th>
                                    <th>Preço</th>
                                    <th class="text-end pe-4" style="width: 160px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($produtos)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Nenhum produto cadastrado.</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($produtos as $produto): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-secondary">#<?= $produto['id'] ?></td>
                                        <td>
                                            <img src="../../assets/produtos/<?= htmlspecialchars($produto['imagem'] ?: 'default.jpg') ?>" class="rounded border" width="50" height="40" style="object-fit: cover;">
                                        </td>
                                        <td>
                                            <span class="d-block fw-bold text-dark"><?= htmlspecialchars($produto['nome']) ?></span>
                                            <small class="text-muted d-block text-truncate" style="max-width: 250px;"><?= htmlspecialchars($produto['descricao']) ?></small>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group shadow-sm">
                                                <a href="?editar=<?= $produto['id'] ?>" class="btn btn-sm btn-light border" title="Editar"><i class="bi bi-pencil text-warning"></i></a>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="acao" value="excluir">
                                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-light border" onclick="return confirm('Tem certeza que deseja remover este produto?')" title="Excluir">
                                                        <i class="bi bi-trash text-danger"></i>
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
            </div>

        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layout/layout.php';
