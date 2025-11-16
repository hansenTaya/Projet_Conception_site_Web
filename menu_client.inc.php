<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('message.inc.php');
?>

<!-- Barre de navigation -->


<script>
  // Petite validation côté client : empêche l'envoi si la recherche est vide
  function validateSearch(form) {
    var input = form.q;
    if (!input) return true;
    var val = (input.value || '').trim();
    if (!val) {
      // ajoute une classe Bootstrap d'erreur temporaire
      input.classList.add('is-invalid');
      setTimeout(function(){ input.classList.remove('is-invalid'); }, 1700);
      return false;
    }
    return true;
  }
</script>

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
  <!-- Contenu principal -->
<div class="flex-grow-1 p-4 d-flex" style="gap: 20px;">

    <!-- 🔵 BARRE VERTICALE : Recherche + conversations -->
    
   
    

</div>
