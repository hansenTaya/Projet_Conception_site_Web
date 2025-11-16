<?php 
 if (session_status() === PHP_SESSION_NONE)
 {
  session_start();
 }
 ?>



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

                $sql = "SELECT DISTINCT client_id AS contact_id
                          FROM messages
                        WHERE demenageur_id = $mon_id";

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

</div>
