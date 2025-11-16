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
    <div class="bg-white border rounded shadow-sm p-3" 
         style="width: 320px; height: 100vh; overflow-y: auto;">

        <h5 class="mb-3 text-center">💬 Messagerie</h5>

        <!-- Barre de recherche -->
        <form method="GET" action="messagerie_recherche.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Rechercher un utilisateur…">
                <button class="btn btn-primary">🔍</button>
            </div>
        </form>

        <h6 class="text-muted mb-3">Discussions récentes</h6>

        <!-- Liste des discussions -->
        <div class="list-group">
            <?php
                $mon_id = $_SESSION['id_utilisateur']; 
                
                require_once("param.inc.php");
                $mysqli = new mysqli($host, $login, $passwd, $dbname);

                $sql = "SELECT DISTINCT demenageur_id AS contact_id
                          FROM messages
                        WHERE client_id = $mon_id";

                $liste = $mysqli->query($sql);

                while ($c = $liste->fetch_assoc()) {
                    $id_contact = $c['contact_id'];

                    $res = $mysqli->query("SELECT nom, prenom FROM utilisateur WHERE id_utilisateur = $id_contact");
                    $user = $res->fetch_assoc();

                    echo "
                    <a href='messagerie_chat_modal.inc.php?destinataire_id=$id_contact'
                      class='list-group-item list-group-item-action'>
                        <strong>{$user['nom']} {$user['prenom']}</strong><br>
                        <small class='text-muted'>Cliquez pour ouvrir la discussion</small>
                    </a>";
                }
            ?>

        </div>
    </div>

    <!-- 🔶 ZONE DE CONTENU (VIDE POUR L’INSTANT) -->
    <div class="flex-grow-1">
        <h3>Bienvenue dans la messagerie</h3>
        <p>Sélectionnez un utilisateur à gauche pour commencer.</p>
    </div>

</div>
