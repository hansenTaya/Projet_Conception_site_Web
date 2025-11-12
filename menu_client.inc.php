<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('message.inc.php');
?>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-body" data-bs-theme="dark">
  <div class="container-fluid">
    <!-- Partie gauche -->
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="fas fa-truck-moving me-2"></i>Just move it :)
    </a>

    <!-- Bouton burger (mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarClient" aria-controls="navbarClient" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Liens de navigation -->
    <div class="collapse navbar-collapse" id="navbarClient">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item dropdown d-md-none">
          <a class="nav-link dropdown-toggle" href="#" id="mobileMenuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bars me-2"></i>Menu
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileMenuDropdown">
            <li><a class="dropdown-item" href="demande.php"><i class="fas fa-clipboard-list me-2"></i>Faire une demande</a></li>
            <li><a class="dropdown-item" href="voir_proposition.php"><i class="fas fa-box me-2"></i>Voir les propositions</a></li>
            <li><a class="dropdown-item" href="evaluer.php"><i class="fas fa-star me-2"></i>Évaluer</a></li>
            <li><a class="dropdown-item" href="messagerie.php"><i class="fas fa-comments me-2"></i>Messagerie</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="deconnexion.php"><i class="fas fa-sign-out-alt me-2"></i>Se déconnecter</a></li>
          </ul>
        </li>
        <li class="nav-item d-none d-md-block">
          <span class="navbar-text text-light me-3">
            <i class="fas fa-user-circle me-1"></i>
            <?php echo isset($_SESSION['prenom']) ? htmlspecialchars($_SESSION['prenom']) . ' ' . htmlspecialchars($_SESSION['nom']) : 'Client'; ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="deconnexion.php">
            <i class="fas fa-sign-out-alt me-1"></i>Se déconnecter
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Layout principal -->
<div class="d-flex flex-column flex-md-row min-vh-100">

  <!-- Sidebar gauche (Desktop) -->
  <div class="sidebar-left bg-light border-end p-4 vh-100 d-none d-md-flex flex-column sticky-top" style="width: 260px;">
    <!-- En-tête sidebar -->
    <div class="text-center mb-4">
      <div class="mb-3">
        <i class="fas fa-user-circle text-primary" style="font-size: 3rem;"></i>
      </div>
      <h5 class="fw-bold mb-1">Espace Client</h5>
      <p class="text-muted small mb-0">
        <?php echo isset($_SESSION['prenom']) ? htmlspecialchars($_SESSION['prenom']) : 'Bienvenue'; ?>
      </p>
    </div>

    <hr class="my-3">

    <!-- Menu de navigation -->
    <div class="d-grid gap-2 flex-grow-1">
      <a href="demande.php" class="btn btn-primary text-start py-3">
        <i class="fas fa-clipboard-list me-2"></i>
        <span class="fw-semibold">Faire une demande</span>
      </a>
      <a href="voir_proposition.php" class="btn btn-outline-secondary text-start py-3">
        <i class="fas fa-box me-2"></i>
        <span class="fw-semibold">Voir les propositions</span>
      </a>
      <a href="evaluer.php" class="btn btn-outline-warning text-start py-3">
        <i class="fas fa-star me-2"></i>
        <span class="fw-semibold">Évaluer</span>
      </a>
      <a href="messagerie.php" class="btn btn-outline-success text-start py-3">
        <i class="fas fa-comments me-2"></i>
        <span class="fw-semibold">Messagerie</span>
      </a>
    </div>

    <!-- Footer sidebar -->
    <div class="mt-auto pt-3 border-top">
      <a href="index.php" class="btn btn-outline-dark btn-sm w-100">
        <i class="fas fa-home me-2"></i>Retour à l'accueil
      </a>
    </div>
  </div>

  <!-- Contenu principal -->
  <div class="flex-grow-1 p-4">