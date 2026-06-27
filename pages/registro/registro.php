<?php
require_once __DIR__ . '/../../controllers/usuarios.controller.php';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $usuarioExistente = buscarUsuarioPorEmail($email);

    if ($usuarioExistente) {
        $erro = "Este e-mail já está cadastrado em nossa base.";
    } else {
        criarUsuario($nome, $email, $senha);
        header("Location: /projeto_fullstack/index.php");
        exit;
    }
}

$title = "Registro";
ob_start();
?>

<section class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="register-box" style="width: 100%; max-width: 400px;">
        
        <div class="card card-outline card-success border-0 shadow-sm rounded bg-white">
            
            <div class="card-header text-center pt-4 pb-2 border-bottom-0">
                <h3 class="fw-bold text-dark mb-0">Criar <span class="text-success">Conta</span></h3>
                <p class="text-muted small mt-1">Preencha os dados abaixo para se registrar</p>
            </div>

            <div class="card-body px-4 pb-4 pt-2">
                
                <?php if ($erro): ?>
                    <div class="alert alert-danger alert-dismissible shadow-sm mb-3">
                        <i class="icon bi bi-exclamation-triangle me-2"></i> <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="text" name="nome" class="form-control bg-light-focus" placeholder="Nome Completo" required>
                            <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control bg-light-focus" placeholder="E-mail" required>
                            <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <div class="input-group">
                            <input type="password" name="senha" class="form-control bg-light-focus" placeholder="Senha" required>
                            <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm rounded-pill">
                                <i class="bi bi-check-circle me-2"></i> Finalizar Cadastro
                            </button>
                        </div>
                    </div>

                </form>

                <div class="text-center mt-4 pt-3 border-top border-light">
                    <p class="text-muted mb-1 small">Já possui um perfil criado?</p>
                    <a href="/projeto_fullstack/pages/login/login.php" class="text-success fw-bold text-decoration-none small">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Ir para o login
                    </a>
                </div>

            </div>
            </div>
        </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layout/layout.php';