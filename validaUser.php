<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$usuario = new Usuario;

if ($usuario->sessaoExpirou()) {
    header("Location: login.php?session_expired=true");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    $redirect = basename($_SERVER['SCRIPT_NAME'], '.php');
    header("Location: login.php?redirect=$redirect.php");
    exit;
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=not_logged_in");
    exit;
}

if(isset($nivelPermitidos)){
    if (!$usuario->verificarNivelAcesso($nivelPermitidos)) {
        // Redireciona para a página de login ou exibe mensagem de erro
        echo "<script>window.alert('Usuário não tem nível de acesso necessário.'); window.location.href='dashboard.php';</script>";
        exit();
    }
}