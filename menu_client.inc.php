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
      <i class="fas fa-truck-moving me-2"></i>Just move it
    </a>

    <!-- Bouton burger (mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarClient" aria-controls="navbarClient" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    
  </div>
</nav>

<!-- Layout principal -->
<div class="d-flex flex-column flex-md-row min-vh-100">

  <!-- Sidebar gauche (desktop uniquement) -->
  <div class="sidebar-left bg-light border-end p-3 vh-100 d-none d-md-flex flex-column sticky-top" style="width: 230px;">
    <h5 class="text-center mb-4 mt-2">Espace Actions</h5>
    <div class="d-grid gap-2">
      <a href="demande.php" class="btn btn-primary text-start">🧳 Faire une demande</a>
      <a href="voir_proposition.php" class="btn btn-outline-secondary text-start">📦 Voir les propositions</a>
       <a href="demenagement_encours.php" class="btn btn-outline-secondary text-start">Demenagement en cours </a>
      <a href="evaluer.php" class="btn btn-outline-warning text-start">⭐ Évaluer</a>
      <a href="messagerie.php" class="btn btn-outline-success text-start">💬 Messagerie</a>
    </div>
  </div>

  <!-- Contenu principal -->
  <div class="flex-grow-1 p-4">