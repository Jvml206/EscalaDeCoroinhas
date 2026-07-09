<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container">

    <a href="dashboard.php">
      <img src="Images/logo.png" alt="Logo" class="logo">
    </a>

    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">

      <ul class="navbar-nav main-menu ms-auto">

        <li class="nav-item">
          <a class="nav-link <?= $current === 'index.php' ? 'active' : '' ?>" href="index.php">Index</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'listaCoroinhas.php' ? 'active' : '' ?>"
            href="listaCoroinhas.php">Coroinhas/Acólitos</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link <?= $current === 'infosCoroinhas.php' ? 'active' : '' ?>"
            href="infosCoroinhas.php">Informações dos Coroinhas</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'listaComunidades.php' ? 'active' : '' ?>"
            href="listaComunidades.php">Comunidades</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'listaCelebracoes.php' ? 'active' : '' ?>"
            href="listaCelebracoes.php">Celebrações</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'escala.php' ? 'active' : '' ?>"
            href="escala.php">Escala</a>
        </li>
      </ul>

      <ul class="navbar-nav ms-lg-auto text-center text-lg-start">
        <?php if (isset($_SESSION['user_name'])): ?>
          <li class="nav-item dropdown">
            <!-- Usuário Logado -->
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <?= htmlspecialchars($_SESSION['user_name']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger" href="logout.php">Sair</a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- Usuário Não Logado -->
          <li class="nav-item">
            <a class="nav-link" href="login.php"><i class="bi bi-person-circle"></i> Entrar</a>
          </li>
        <?php endif; ?>
      </ul>

    </div>

  </div>
</nav>