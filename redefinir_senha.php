<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="CSS/login.css?v=<?php echo filemtime('CSS/login.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Recuperar Senha</title>
</head>

<body>
    <?php require_once "_parts/_header.php"; ?>

    <main class="flex-grow-1 d-flex justify-content-center align-items-center my-5 px-3">
        <div class="form-login">
            <p>Recuperar Senha</p>
            <form action="solicitar_recuperacao.php" method="post" class="mt-4">
                <div class="mb-3">
                    <label for="email" class="form-label">Informe seu e-mail cadastrado</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="seuemail@exemplo.com"
                        required>
                </div>
                <button type="submit" class="btn btn-login">Enviar Link</button>
            </form>
        </div>
    </main>

    <footer class="footer mt-auto">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Botão do VLibras -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>

    <!-- Script do VLibras -->
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>