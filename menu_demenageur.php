<nav class="mb-2 navbar navbar-expand-md navbar-dark bg-dark border-bottom border-body" data-bs-theme="dark">
  <div class="container-fluid">

    <!-- Partie gauche de la barre -->
    <a class="navbar-brand" href="index.php">🚛 Just Move It :) - Déménageur</a>

    <!-- Bouton burger pour mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Liens de droite -->
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="deconnexion.php">🚪 Déconnexion</a>
        </li>
      </ul>
    </div>
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
      <a href="missions_en_cour.php" class="btn btn-outline-success text-start">🚚 Missions en cours</a>
      <a href="historique_mission.php" class="btn btn-outline-dark text-start">📦 Historique</a>
      <a href="messagerie.php" class="btn btn-outline-info text-start">💬 Messagerie</a>
    </div>
  </div>

  <!-- Contenu principal -->
  <div class="flex-grow-1 p-4">
    <!-- Ton contenu principal ici -->
  </div>

</div>
