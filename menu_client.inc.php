<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('message.inc.php');
?>

<!-- Layout principal -->
<div class="d-flex flex-column flex-md-row min-vh-100">

  <!-- Sidebar gauche (desktop uniquement) -->
  <div class="sidebar-left bg-white border-end shadow-sm p-4 vh-100 d-none d-md-flex flex-column sticky-top" style="width: 280px;">
    <div class="text-center mb-4">
      <h4 class="fw-bold text-primary mb-2">Espace Client</h4>
      <div class="border-bottom pb-3"></div>
    </div>
    <div class="d-grid gap-3">
      <a href="demande.php" class="btn btn-primary text-start d-flex align-items-center gap-3 py-3 shadow-sm">
        <span class="fs-4">🧳</span>
        <div class="flex-grow-1">
          <div class="fw-semibold">Faire une demande</div>
          <small class="text-white-50">Nouveau déménagement</small>
        </div>
      </a>
      <a href="voir_proposition.php" class="btn btn-outline-primary text-start d-flex align-items-center gap-3 py-3">
        <span class="fs-4">📦</span>
        <div class="flex-grow-1">
          <div class="fw-semibold">Voir les propositions</div>
          <small class="text-muted">Mes offres reçues</small>
        </div>
      </a>
      <a href="demenagement_encours.php" class="btn btn-outline-info text-start d-flex align-items-center gap-3 py-3">
        <span class="fs-4">🚛</span>
        <div class="flex-grow-1">
          <div class="fw-semibold">Déménagements en cours</div>
          <small class="text-muted">Missions actives</small>
        </div>
      </a>
      <a href="evaluer.php" class="btn btn-outline-warning text-start d-flex align-items-center gap-3 py-3">
        <span class="fs-4">⭐</span>
        <div class="flex-grow-1">
          <div class="fw-semibold">Évaluer</div>
          <small class="text-muted">Noter un service</small>
        </div>
      </a>
      <a href="messagerie.php" class="btn btn-outline-success text-start d-flex align-items-center gap-3 py-3">
        <span class="fs-4">💬</span>
        <div class="flex-grow-1">
          <div class="fw-semibold">Messagerie</div>
          <small class="text-muted">Messages & discussions</small>
        </div>
      </a>
    </div>
  </div>

  <!-- Contenu principal -->
  <div class="flex-grow-1 p-4 p-md-5">
    <div class="container-fluid px-0">
