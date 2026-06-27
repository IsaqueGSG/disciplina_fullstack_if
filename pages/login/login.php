<?php
session_start();
require_once __DIR__ . '/../../controllers/auth.controller.php';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (login($email, $senha)) {
        header("Location: /projeto_fullstack/index.php");
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}

$title = "Login";
ob_start();
?>

<section class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="login-box" style="width: 100%; max-width: 400px;">
        
        <div class="card card-outline card-primary border-0 shadow-sm rounded bg-white">
            
            <div class="card-header text-center pt-4 pb-2 border-bottom-0">
                <h3 class="fw-bold text-dark mb-0">Portal <span class="text-primary">Jetski</span></h3>
                <p class="text-muted small mt-1">Faça login para gerenciar sua sessão</p>
            </div>

            <div class="card-body px-4 pb-4 pt-2">
                
                <?php if ($erro): ?>
                    <div class="alert alert-danger alert-dismissible shadow-sm mb-3">
                        <i class="icon bi bi-x-circle me-2"></i> <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    
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
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-pill">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Entrar
                            </button>
                        </div>
                    </div>

                </form>

                <div class="text-center mt-4 pt-3 border-top border-light">
                    <p class="text-muted mb-1 small">Ainda não possui uma conta?</p>
                    <a href="/projeto_fullstack/pages/registro/registro.php" class="text-primary fw-bold text-decoration-none small">
                        <i class="bi bi-person-plus me-1"></i> Cadastre-se aqui
                    </a>
                </div>

            </div>
            </div>
        </div>
</section>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layout/layout.php';