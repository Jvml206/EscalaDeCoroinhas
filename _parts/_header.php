<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container">

    <a href="index.php">
      <img src="Images/logo.png" alt="Logo" class="logo">
    </a>

    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">

      <ul class="navbar-nav main-menu ms-auto">

        <li class="nav-item">
          <a class="nav-link <?= $current === 'index.php' ? 'active' : '' ?>" href="index.php">Escala</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'nivel1.php' ? 'active' : '' ?>" href="nivel1.php">Nível 1</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'nivel2.php' ? 'active' : '' ?>" href="nivel2.php">Nível 2</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'acolitos.php' ? 'active' : '' ?>" href="acolitos.php">Acólitos</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'comunidades.php' ? 'active' : '' ?>" href="comunidades.php">Comunidades</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $current === 'dados.php' ? 'active' : '' ?>" href="dados.php">Dados</a>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <?php
        if (session_status() === PHP_SESSION_NONE) {
          session_start();
        }
        if (isset($_SESSION['user_name'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <?php echo htmlspecialchars($_SESSION['user_name']); ?>
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
          <li class="nav-item">
            <a class="nav-link" href="login.php"><i class="bi bi-person-circle"></i> Entrar</a>
          </li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>