<?php
session_start();
$titre = "Messagerie";

include('header.inc.php');
include('message.inc.php');
require_once("param.inc.php");

$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) { die("Erreur DB : " . $mysqli->connect_error); }

$mon_id = $_SESSION['id_utilisateur'];

$destinataire_id = isset($_GET['destinataire_id']) ? intval($_GET['destinataire_id']) : null;
$recherche = isset($_GET['q']) ? trim($_GET['q']) : "";
$statut_utilisateur = $_SESSION['statut'];
?>

<main class="container my-4">
  <div class="row g-4">

    <!-- ========== COLONNE GAUCHE ========== -->
    <div class="col-lg-4">
      <div class="bg-white border rounded shadow-sm p-3" style="height: 80vh; overflow-y: auto;">

        <h5 class="mb-3 text-center">💬 Messagerie</h5>

        <form method="GET" action="messagerie.php" class="mb-3">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Rechercher…" value="<?= htmlspecialchars($recherche) ?>">
            <button class="btn btn-primary">🔍</button>
          </div>
        </form>

        <!-- ========== Résultats de recherche ========== -->
        <?php if ($recherche != ""): ?>
          <h6 class="text-muted mb-2">Résultats</h6>

          <div class="list-group mb-4">
            <?php
              $like = "%{$recherche}%";
              $sql = "SELECT * FROM utilisateur 
        WHERE (nom LIKE ? OR prenom LIKE ?)
          AND id_utilisateur != ? 
          AND statut != ? AND statut != 'administrateur'";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ssis', $like, $like, $mon_id, $statut_utilisateur);
$stmt->execute();
$result = $stmt->get_result();

              if ($result->num_rows == 0) {
                echo "<p class='text-muted ps-2'>Aucun résultat.</p>";
              }

              while ($u = $result->fetch_assoc()) {
                echo "
                <a href='messagerie.php?destinataire_id={$u['id_utilisateur']}'
                   class='list-group-item list-group-item-action'>
                   <strong>{$u['nom']} {$u['prenom']}</strong><br>
                   <small>Démarrer la conversation</small>
                </a>";
              }
            ?>
          </div>
        <?php endif; ?>


        <!-- ========== Liste des discussions ========== -->
        <h6 class="text-muted mb-2">Discussions</h6>

        <div class="list-group">
          <?php
            $sql = "
              SELECT DISTINCT 
                IF(client_id = $mon_id, demenageur_id, client_id) AS contact_id
              FROM messages
              WHERE client_id = $mon_id OR demenageur_id = $mon_id
            ";

            $res = $mysqli->query($sql);

            while ($row = $res->fetch_assoc()) {
                $idc = $row['contact_id'];

                $u = $mysqli->query("SELECT nom, prenom FROM utilisateur WHERE id_utilisateur = $idc")->fetch_assoc();

                $active = ($destinataire_id == $idc) ? "active" : "";
                echo "
                <a href='messagerie.php?destinataire_id=$idc'
                   class='list-group-item list-group-item-action $active'>
                   <strong>{$u['nom']} {$u['prenom']}</strong><br>
                   <small class='text-muted'>Discussion</small>
                </a>";
            }
          ?>
        </div>

      </div>
    </div>


    <!-- ========== COLONNE DROITE (CHAT) ========== -->
    <div class="col-lg-8">
      <div class="card shadow-sm h-100">

        <?php if (!$destinataire_id): ?>

          <div class="card-body d-flex flex-column justify-content-center text-center text-muted">
            <p>Aucune discussion sélectionnée.</p>
            <small>Choisissez un contact à gauche.</small>
          </div>

        <?php else: ?>

          <?php
            $user = $mysqli->query("SELECT nom, prenom FROM utilisateur WHERE id_utilisateur=$destinataire_id")->fetch_assoc();
          ?>

          <div class="card-header bg-light">
            <h5 class="mb-0">💬 <?= $user['nom'] . " " . $user['prenom'] ?></h5>
          </div>

          <div class="card-body" style="height:60vh; overflow-y:auto; background:#f5f5f5;">
            <?php
              $sql_msg = "
                SELECT * FROM messages
                WHERE (client_id=$mon_id AND demenageur_id=$destinataire_id)
                   OR (client_id=$destinataire_id AND demenageur_id=$mon_id)
                ORDER BY id ASC
              ";

              $msgs = $mysqli->query($sql_msg);
              if ($msgs->num_rows > 0) {
                  while ($m = $msgs->fetch_assoc()) {
                      $moi = ($m['expediteur_id'] == $mon_id);
                      if ($moi) {
                          echo "<div class='text-end mb-3'>
                                  <span class='badge bg-primary p-2'>{$m['message']}</span><br>
                                  <small class='text-muted'>{$m['time']}</small>
                                </div>";
                      } else {
                          echo "<div class='text-start mb-3'>
                                  <span class='badge bg-light text-dark p-2'>{$m['message']}</span><br>
                                  <small class='text-muted'>{$m['time']}</small>
                                </div>";
                      }
                  }
              } else {
                  echo "<p class='text-muted text-center'>Aucun message.</p>";
              }
            ?>
          </div>

          <div class="card-footer">
            <form method="POST" action="messagerie_chat_traiter.php">
              <input type="hidden" name="destinataire_id" value="<?= $destinataire_id ?>">
              <div class="input-group">
                <input type="text" name="message" class="form-control" placeholder="Votre message…" required>
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
