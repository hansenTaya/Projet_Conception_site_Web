<?php
session_start();
$titre = "Messagerie";
if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['erreur'] = "Veuillez vous connecter.";
    header("Location: connexion.php");
    exit(); // 🔥 OBLIGATOIRE
}
include('header.inc.php');
include('message.inc.php');
require_once("param.inc.php");

// Connexion DB
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Erreur DB : " . $mysqli->connect_error);
}

$mon_id = $_SESSION['id_utilisateur'];

// SI un destinataire est choisi dans l’URL
$destinataire_id = isset($_GET['destinataire_id']) ? intval($_GET['destinataire_id']) : null;
?>

<main class="container my-4">
  <div class="row g-4">

    <!-- 🔵 COLONNE GAUCHE : Conversations -->
    <div class="col-lg-4">
      <div class="bg-white border rounded shadow-sm p-3" style="height: 80vh; overflow-y: auto;">

        <h5 class="mb-3 text-center">💬 Messagerie</h5>

        <form method="GET" action="messagerie_recherche.php" class="mb-4">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Rechercher un utilisateur…">
            <button class="btn btn-primary">🔍</button>
          </div>
        </form>

        <h6 class="text-muted mb-3">Discussions</h6>

        <div class="list-group">

          <?php
          // Liste des contacts avec qui l’utilisateur a parlé
          $sqlContacts = "
              SELECT DISTINCT 
                IF(client_id = $mon_id, demenageur_id, client_id) AS contact_id
              FROM messages
              WHERE client_id = $mon_id OR demenageur_id = $mon_id
          ";

          $contacts = $mysqli->query($sqlContacts);

          while ($row = $contacts->fetch_assoc()) {
              $id_contact = $row['contact_id'];

              $u = $mysqli->query("SELECT nom, prenom FROM utilisateur WHERE id_utilisateur = $id_contact")->fetch_assoc();

              $activeClass = ($destinataire_id == $id_contact) ? "active" : "";

              echo "
              <a href='messagerie.php?destinataire_id=$id_contact'
                 class='list-group-item list-group-item-action $activeClass'>
                <strong>{$u['nom']} {$u['prenom']}</strong><br>
                <small class='text-muted'>Cliquez pour ouvrir la discussion</small>
              </a>";
          }
          ?>

        </div>
      </div>
    </div>


    <!-- 🔵 COLONNE DROITE : Chat -->
    <div class="col-lg-8">
      <div class="card shadow-sm h-100">

        <?php if (!$destinataire_id): ?>

          <!-- Aucun contact sélectionné -->
          <div class="card-body d-flex flex-column justify-content-center text-center text-muted">
            <p class="mb-2">Choisissez une discussion dans la liste.</p>
            <small>Le chat s'affiche ici.</small>
          </div>

        <?php else: ?>

          <?php
          // Récupérer les infos du contact
          $user = $mysqli->query("SELECT * FROM utilisateur WHERE id_utilisateur = $destinataire_id")->fetch_assoc();
          ?>

          <div class="card-header bg-light">
            <h5 class="mb-0">💬 Discussion avec <?= $user['nom']." ".$user['prenom'] ?></h5>
          </div>

          <!-- 🔵 Messages -->
          <div class="card-body" style="height: 60vh; overflow-y: auto; background:#f5f5f5;">

            <?php
            $sql_messages = "
              SELECT *
              FROM messages
              WHERE 
                (client_id = $mon_id AND demenageur_id = $destinataire_id)
                OR
                (client_id = $destinataire_id AND demenageur_id = $mon_id)
              ORDER BY id ASC
            ";

            $msgs = $mysqli->query($sql_messages);

            if ($msgs->num_rows > 0):

              while ($msg = $msgs->fetch_assoc()) {

                  $moi = ($msg['expediteur_id'] == $mon_id);

                  if ($moi) {
                      echo "
                      <div class='text-end mb-3'>
                        <span class='badge bg-primary p-2' style='font-size:1rem; border-radius:20px;'>{$msg['message']}</span><br>
                        <small class='text-muted'>{$msg['time']}</small>
                      </div>";
                  } else {
                      echo "
                      <div class='text-start mb-3'>
                        <span class='badge bg-light text-dark p-2' style='font-size:1rem; border-radius:20px;'>{$msg['message']}</span><br>
                        <small class='text-muted'>{$msg['time']}</small>
                      </div>";
                  }

              }

            else:
              echo "<p class='text-center text-muted'>Aucun message pour le moment.</p>";
            endif;
            ?>

          </div>

          <!-- 🟢 Formulaire d'envoi -->
          <div class="card-footer">
            <form method="POST" action="messagerie_chat_traiter.php">
              <input type="hidden" name="destinataire_id" value="<?= $destinataire_id ?>">

              <div class="input-group">
                <input type="text" name="message" class="form-control" placeholder="Votre message..." required>
                <button class="btn btn-primary">📤</button>
              </div>
            </form>
          </div>

        <?php endif; ?>

      </div>
    </div>

  </div>
</main>

<?php include('footer.inc.php'); ?>
