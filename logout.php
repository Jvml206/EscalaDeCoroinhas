<?php
  require_once 'validaUser.php';

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php" ;
  });
  $usuario = new Usuario;

  $usuario->logout();