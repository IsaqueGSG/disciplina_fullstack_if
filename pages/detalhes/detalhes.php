<?php

$title = "Detalhes";
$pageCss = "/detalhes.css";

require_once __DIR__ . '/../../controllers/produtos.controller.php';
require_once __DIR__ . '/../../controllers/auth.controller.php';
require_once __DIR__ . '/../../controllers/comentarios.controller.php';

$usuarioLogado = usuarioLogado();
$id = $_GET['id'] ?? null;

if ($id) {
    $produto = buscarProduto($id);
    if ($produto) {
        $produtos = [$produto];
    } else {
        $produtos = listarProdutos();
    }
} else {
    $produtos = listarProdutos();
}

// envia mensagem de sucesso ou erro para o usuário após tentar criar um comentário
$mensagemSucesso = false;
$mensagemErro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_comentario'])) {
    if ($usuarioLogado) {
        $usuarioId  = $usuarioLogado['id'];
        $produtoId  = isset($_POST['produto_id']) ? intval($_POST['produto_id']) : 0;
        $nota       = isset($_POST['nota']) ? intval($_POST['nota']) : 5;
        $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

        if ($produtoId > 0 && !empty($comentario)) {
            // Chamando a sua função diretamente!
            $sucesso = criarComentario($usuarioId, $produtoId, $comentario, $nota);
            if ($sucesso) {
                $mensagemSucesso = true;
            } else {
                $mensagemErro = true;
            }
        }
    }
}

ob_start();

?>

<section class="min-vh-100 py-5">
    <div class="container mt-5">

        <?php if ($mensagemSucesso): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Avaliação enviada com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($mensagemErro): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Erro ao enviar comentário. Tente novamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php foreach ($produtos as $produto): ?>

            <div class="card card-solid border-0 shadow-sm rounded mb-5">
                <div class="card-body p-4 p-md-5">

                    <div class="row align-items-center">

                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="product-image-container position-relative overflow-hidden rounded shadow-sm"
                                style="width: 100%; height: 380px; background-color: #f8f9fa;">

                                <img src="../../assets/produtos/<?= htmlspecialchars($produto['imagem']) ?>"
                                    class="position-absolute top-50 start-50 translate-middle w-100 h-100"
                                    style="object-fit: cover; object-position: center;"
                                    alt="<?= htmlspecialchars($produto['nome']) ?>">

                            </div>
                        </div>

                        <div class="col-md-6">

                            <h1 class="text-dark fw-bold mb-3"><?= htmlspecialchars($produto['nome']) ?></h1>

                            <hr class="border-light">

                            <p class="text-muted fs-5 my-4 line-height-md">
                                <?= htmlspecialchars($produto['descricao']) ?>
                            </p>

                            <div class="bg-light p-3 rounded mb-4 border-start border-success border-4">
                                <span class="text-muted d-block fs-6 small uppercase">Valor do Aluguel</span>
                                <h2 class="text-success fw-bold m-0">
                                    R$ <?= number_format($produto['preco'], 2, ',', '.') ?> <span class="fs-6 text-muted font-weight-normal">/ hora</span>
                                </h2>
                            </div>

                            <div class="d-flex flex-wrap gap-3 mt-3">
                                <a href="/projeto_fullstack/pages/precos/precos.php?id=<?= $produto['id'] ?>"
                                    class="btn btn-success btn-lg px-4 fw-bold shadow-sm">
                                    <i class="bi bi-tags me-2"></i> Ver Tabela de Preços
                                </a>

                                <a href="/projeto_fullstack/pages/comentarios/comentarios.php?id=<?= $produto['id'] ?>"
                                    class="btn btn-primary btn-lg px-4 fw-bold shadow-sm">
                                    <i class="bi bi-chat-text me-2"></i> Ver Comentários
                                </a>
                            </div>

                        </div>

                    </div>

                    <div class="card card-outline card-primary border-0 shadow-sm rounded bg-white mt-5">
                        <div class="card-header bg-transparent border-bottom pt-3 d-flex justify-content-between align-items-center">
                            <h3 class="card-title fw-bold text-dark flex-grow-1 m-0">
                                <i class="bi bi-chat-left-text text-primary me-2"></i>Avaliações
                            </h3>
                            <button class="btn btn-outline-primary btn-sm fw-bold px-3 rounded-pill"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseFormComentario<?= $produto['id'] ?>"
                                aria-expanded="false"
                                aria-controls="collapseFormComentario<?= $produto['id'] ?>">
                                <i class="bi bi-pencil-square me-1"></i> Deixar Avaliação
                            </button>
                        </div>

                        <div class="collapse" id="collapseFormComentario<?= $produto['id'] ?>">
                            <?php if ($usuarioLogado): ?>
                                <form action="" method="POST">
                                    <div class="card-body p-4">

                                        <input type="hidden" name="produto_id" value="<?= htmlspecialchars($produto['id']) ?>">

                                        <div class="row g-3">

                                            <div class="col-12 form-group">
                                                <label class="form-label text-muted text-xs uppercase fw-bold" for="nota">Sua Nota (1 a 5 Estrelas)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light text-warning"><i class="bi bi-star-fill"></i></span>
                                                    <select name="nota" id="nota" class="form-select fw-bold text-dark" required>
                                                        <option value="5" selected>5 - Excelente</option>
                                                        <option value="4">4 - Muito Bom</option>
                                                        <option value="3">3 - Regular</option>
                                                        <option value="2">2 - Ruim</option>
                                                        <option value="1">1 - Péssimo</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 form-group mt-3">
                                                <label class="form-label text-muted text-xs uppercase fw-bold" for="comentario">Seu Comentário</label>
                                                <textarea name="comentario" id="comentario" class="form-control" rows="4" placeholder="Conte-nos o que você achou deste produto..." required></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
                                        <button type="submit" name="enviar_comentario" class="btn btn-primary fw-bold px-4 rounded shadow-sm">
                                            <i class="bi bi-send-fill me-2"></i>Enviar Comentário
                                        </button>
                                    </div>
                                </form>

                            <?php else: ?>
                                <div class="card-body p-4 text-center">
                                    <div class="p-4 border rounded bg-light">
                                        <i class="bi bi-lock text-muted display-6 d-block mb-3"></i>
                                        <h5 class="fw-bold text-dark mb-2">Quer deixar sua opinião sobre o produto?</h5>
                                        <p class="text-muted text-sm mb-3">Você precisa estar conectado à sua conta para enviar uma avaliação.</p>
                                        <a href="/projeto_fullstack/pages/login/login.php" class="btn btn-outline-primary btn-sm fw-bold px-4 rounded-pill">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Entrar / Criar Conta
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        <?php endforeach; ?>

        <?php if (count($produtos) === 0): ?>
            <div class="alert alert-warning alert-dismissible shadow-sm text-center mt-5">
                <h5><i class="icon bi bi-exclamation-triangle me-2"></i> Atenção!</h5>
                Produto não encontrado em nosso catálogo.
            </div>
        <?php endif; ?>

    </div>
</section>

<?php

$content = ob_get_clean();

require_once __DIR__ . '/../../layout/layout.php';