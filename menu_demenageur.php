<?php 
 if (session_status() === PHP_SESSION_NONE)
 {
  session_start();
 }
 ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">🚚 Just Move It :) - Déménageur</span>
        <span class="text-white">Connecté : <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></span>
    </div>
</nav>

<!-- Layout principal -->
<div class="d-flex flex-column flex-md-row">

  <!-- Sidebar gauche (desktop uniquement) -->
  <div class="sidebar-left bg-light border-end p-3 vh-100 d-none d-md-flex flex-column sticky-top" style="width: 240px;">
    <h5 class="text-center mb-4 mt-2">Espace Déménageur</h5>

    <div class="d-grid gap-2">
      <a href="afficher_demande.php" class="btn btn-primary text-start">📋 Voir les demandes</a>
      <a href="mes_proposition.php" class="btn btn-outline-secondary text-start">💰 Mes propositions</a>
      <a href="mes_mission.php" class="btn btn-outline-secondary text-start">💰 Mes Mission</a>
      <a href="messagerie.php" class="btn btn-outline-info text-start">💬 Messagerie</a>
    </div>
  </div>

  <!-- Contenu principal -->
  <div class="flex-grow-1 p-4">
    <!-- Ton contenu principal ici -->
  </div>

</div>
