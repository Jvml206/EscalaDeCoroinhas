<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        die("E-mail inválido.");
    }

    $usuario = new Usuario(); // ou a classe que implementa o método
    $sucesso = $usuario->solicitarRecuperacaoSenha($email, 'Recebemos sua solicitação para recuperação de senha.','Recuperação de Senha');

    if ($sucesso) {
        echo "<script>window.alert('Enviamos um link para redefinir sua senha no e-mail informado.'); window.location.href='index.php';</script>";
    } else {
        echo "E-mail não cadastrado ou erro no processo.";
    }
}
