<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/login.css?v=<?php echo filemtime('CSS/login.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Login</title>
</head>
<?php
if (filter_has_var(INPUT_POST, "logar")) {
    spl_autoload_register(function ($class) {
        require_once "Classes/{$class}.class.php";
    });
    $usuario = new Usuario;
    $usuario->setEmailUsuario(filter_input(INPUT_POST, 'email'));
    $usuario->setSenhaUsuario(filter_input(INPUT_POST, 'senha'));
    $mensagem = $usuario->login();
    echo "<script>alert('$mensagem');</script>";
}
?>

<body>

    <?php require_once "_parts/_header.php"; ?>
    <div class="container-fluid my-5 d-flex justify-content-center align-items-center ">

        <div class="login-card">
            <div class="row g-0">

                <div class="col-md-6 d-none d-md-flex login-left">
                    <img src="Images/logo.png" alt="Café" class="login-image">

                    <div class="login-left-content">
                        <h1>Seja bem-vindo<br>ao Sistema <strong>Escala Coroinhas</strong></h1>
                        <p>Facilitando a vida do coordenador dos coroinhas!</p>
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-center justify-content-center login-right">
                    <div class="form-login">
                        <h2 class="login-title">Login</h2>

                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="redirect"
                                value="<?php echo isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'dashboard.php'; ?>">

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Digite seu e-mail" required>
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha"
                                    placeholder="Digite sua senha" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-login" name="logar">
                                    Entrar
                                </button>
                            </div>
                        </form>

                        <div class="login-links"> <span>Esqueceu a senha?</span> <a href="redefinir_senha.php"
                                class="link-criar">Redefinir senha</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer mt-auto">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>