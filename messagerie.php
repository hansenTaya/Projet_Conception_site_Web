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

<main class="container-fluid my-4">
  <div class="row g-4 h-100">

    <!-- ========== COLONNE GAUCHE ========== -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-lg h-100">
        <div class="card-header bg-primary text-white py-3">
          <h5 class="mb-0 fw-bold text-center">💬 Messagerie</h5>
        </div>
        <div class="card-body p-3" style="height: 80vh; overflow-y: auto;">

          <!-- Lien rapide vers les propositions -->
          <div class="mb-4">
            <?php if ($statut_utilisateur === 'client'): ?>
              <a href="voir_proposition.php" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2 mb-3">
                <span>📦</span>
                <span class="fw-semibold">Voir mes propositions</span>
              </a>
            <?php else: ?>
              <a href="mes_proposition.php" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2 mb-3">
                <span>💰</span>
                <span class="fw-semibold">Mes propositions</span>
              </a>
            <?php endif; ?>
          </div>

          <form method="GET" action="messagerie.php" class="mb-4">
            <div class="input-group input-group-lg">
              <input type="text" name="q" class="form-control" placeholder="Rechercher un contact…" value="<?= htmlspecialchars($recherche) ?>">
              <button class="btn btn-primary" type="submit">🔍</button>
            </div>
          </form>

          <!-- ========== Résultats de recherche ========== -->
          <?php if ($recherche != ""): ?>
            <h6 class="text-muted mb-3 fw-semibold">Résultats de recherche</h6>

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
                  echo "<div class='alert alert-info text-center py-3'><small>Aucun résultat trouvé.</small></div>";
                }

                while ($u = $result->fetch_assoc()) {
                  echo "
                  <a href='messagerie.php?destinataire_id={$u['id_utilisateur']}'
                     class='list-group-item list-group-item-action border-0 rounded mb-2 shadow-sm'>
                     <div class='d-flex align-items-center gap-3'>
                       <div class='bg-primary text-white rounded-circle d-flex align-items-center justify-content-center' style='width: 45px; height: 45px;'>
                         <span class='fs-5'>👤</span>
                       </div>
                       <div>
                         <strong class='d-block'>{$u['nom']} {$u['prenom']}</strong>
                         <small class='text-muted'>Démarrer la conversation</small>
                       </div>
                     </div>
                  </a>";
                }
              ?>
            </div>
          <?php endif; ?>


          <!-- ========== Liste des discussions ========== -->
          <h6 class="text-muted mb-3 fw-semibold">Discussions</h6>

          <div class="list-group">
            <?php
              $sql = "
                SELECT DISTINCT 
                  IF(client_id = $mon_id, demenageur_id, client_id) AS contact_id
                FROM messages
                WHERE client_id = $mon_id OR demenageur_id = $mon_id
              ";

              $res = $mysqli->query($sql);

              if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $idc = $row['contact_id'];

                    $u = $mysqli->query("SELECT nom, prenom FROM utilisateur WHERE id_utilisateur = $idc")->fetch_assoc();

                    $active = ($destinataire_id == $idc) ? "bg-primary text-white" : "bg-white";
                    $textColor = ($destinataire_id == $idc) ? "text-white" : "text-dark";
                    echo "
                    <a href='messagerie.php?destinataire_id=$idc'
                       class='list-group-item list-group-item-action border-0 rounded mb-2 shadow-sm $active'>
                       <div class='d-flex align-items-center gap-3'>
                         <div class='bg-" . (($destinataire_id == $idc) ? "white text-primary" : "primary text-white") . " rounded-circle d-flex align-items-center justify-content-center' style='width: 45px; height: 45px;'>
                           <span class='fs-5'>💬</span>
                         </div>
                         <div>
                           <strong class='d-block $textColor'>{$u['nom']} {$u['prenom']}</strong>
                           <small class='" . (($destinataire_id == $idc) ? "text-white-50" : "text-muted") . "'>Discussion active</small>
                         </div>
                       </div>
                    </a>";
                }
              } else {
                echo "<div class='alert alert-info text-center py-3'><small>Aucune discussion pour le moment.</small></div>";
              }
            ?>
          </div>

        </div>
      </div>
    </div>


    <!-- ========== COLONNE DROITE (CHAT) ========== -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-lg h-100">

        <?php if (!$destinataire_id): ?>

          <div class="card-body d-flex flex-column justify-content-center align-items-center text-center text-muted" style="height: 80vh;">
            <div class="mb-4">
              <span class="display-1">💬</span>
            </div>
            <h4 class="fw-bold mb-2">Aucune discussion sélectionnée</h4>
            <p class="mb-0">Choisissez un contact à gauche pour démarrer une conversation.</p>
          </div>

        <?php else: ?>

          <?php
            $user = $mysqli->query("SELECT nom, prenom FROM utilisateur WHERE id_utilisateur=$destinataire_id")->fetch_assoc();
          ?>

          <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <span>💬</span>
                <span><?= $user['nom'] . " " . $user['prenom'] ?></span>
              </h5>
              <div>
                <?php if ($statut_utilisateur === 'client'): ?>
                  <a href="voir_proposition.php" class="btn btn-light btn-sm">
                    <span class="me-1">📦</span>Propositions
                  </a>
                <?php else: ?>
                  <a href="mes_proposition.php" class="btn btn-light btn-sm">
                    <span class="me-1">💰</span>Mes propositions
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="card-body p-4" style="height:60vh; overflow-y:auto; background: linear-gradient(to bottom, #f8f9fa, #ffffff);">
            <?php
              $sql_msg = "
                SELECT * FROM messages
                WHERE (client_id=$mon_id AND demenageur_id=$destinataire_id)
                   OR (client_id=$destinataire_id AND demenageur_id=$mon_id)
                ORDER BY id ASC
              ";

              $msgs = $mysqli->query($sql_msg);
              if ($msgs && $msgs->num_rows > 0) {
                  while ($m = $msgs->fetch_assoc()) {
                      $moi = ($m['expediteur_id'] == $mon_id);
                      if ($moi) {
                          echo "<div class='d-flex justify-content-end mb-3'>
                                  <div class='bg-primary text-white rounded-4 p-3 shadow-sm' style='max-width: 70%;'>
                                    <div class='mb-1'>{$m['message']}</div>
                                    <small class='text-white-50 d-block text-end' style='font-size: 0.75rem;'>{$m['time']}</small>
                                  </div>
                                </div>";
                      } else {
                          echo "<div class='d-flex justify-content-start mb-3'>
                                  <div class='bg-white border rounded-4 p-3 shadow-sm' style='max-width: 70%;'>
                                    <div class='mb-1 text-dark'>{$m['message']}</div>
                                    <small class='text-muted d-block text-start' style='font-size: 0.75rem;'>{$m['time']}</small>
                                  </div>
                                </div>";
                      }
                  }
              } else {
                  echo "<div class='text-center text-muted py-5'>
                          <span class='fs-1 d-block mb-3'>💬</span>
                          <p>Aucun message. Envoyez le premier message !</p>
                        </div>";
              }
            ?>
          </div>

          <div class="card-footer bg-light border-0 p-3">
            <form method="POST" action="messagerie_chat_traiter.php">
              <input type="hidden" name="destinataire_id" value="<?= $destinataire_id ?>">
              <div class="input-group input-group-lg">
                <input type="text" name="message" class="form-control form-control-lg" placeholder="Tapez votre message…" required>
                <button class="btn btn-primary btn-lg px-4" type="submit">
                  <span class="me-1">📤</span>Envoyer
                </button>
              </div>
            </form>
          </div>

        <?php endif; ?>

      </div>
    </div>

  </div>
</main>

<?php include('footer.inc.php'); ?>
