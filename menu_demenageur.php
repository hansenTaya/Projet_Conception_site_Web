<?php 
 if (session_status() === PHP_SESSION_NONE)
 {
  session_start();
 }
 ?>



<!-- Layout principal -->
<div class="d-flex flex-column flex-md-row min-vh-100">

  <!-- Sidebar gauche (desktop uniquement) -->
  <div class="sidebar-left bg-white border-end shadow-sm p-4 vh-100 d-none d-md-flex flex-column sticky-top" style="width: 280px;">
    <div class="text-center mb-4">
      <h4 class="fw-bold text-primary mb-2">Espace Déménageur</h4>
      <div class="border-bottom pb-3"></div>
    </div>
    <div class="d-grid gap-3">
      <a href="afficher_demande.php" class="btn btn-primary text-start d-flex align-items-center gap-3 py-3 shadow-sm">
        <span class="fs-4">📋</span>
        <div class="flex-grow-1">
          <div class="fw-semibold">Voir les demandes</div>
          <small class="text-white-50">Nouvelles opportunités</small>
        </div>
      </a>
      <a href="mes_proposition.php" class="btn btn-outline-primary text-start d-flex align-items-center gap-3 py-3">
        <span class="fs-4">💰</span>
        <div class="flex-grow-1">
          <div class="fw-semibold">Mes propositions</div>
          <small class="text-muted">Mes offres envoyées</small>
        </div>
      </a>
      <a href="mes_mission.php" class="btn btn-outline-success text-start d-flex align-items-center gap-3 py-3">
        <span class="fs-4">🚛</span>
        <div class="flex-grow-1">
          <div class="fw-semibold">Mes missions</div>
          <small class="text-muted">Déménagements acceptés</small>
        </div>
      </a>
      <a href="messagerie.php" class="btn btn-outline-info text-start d-flex align-items-center gap-3 py-3">
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
