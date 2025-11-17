<?php
session_start();
if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['erreur'] = "Veuillez vous connecter.";
    header("Location: connexion.php");
    exit(); // 🔥 OBLIGATOIRE
}
require_once("param.inc.php");
$titre = "Voir les demandes";
include('header.inc.php');
include('menu_demenageur.php');
// Connexion à la BDD
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) die("Erreur de connexion : " . $mysqli->connect_error);

// Récupération de toutes les demandes avec le nom du client
$sql = "
    SELECT d.*, u.nom AS nom_client
    FROM demande d
    JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
    ORDER BY d.date_prevue DESC
";

$result = $mysqli->query($sql);
if (!$result) {
    die("Erreur SQL : " . $mysqli->error);
}
?>

<div class="mb-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-primary mb-2">📋 Liste des Demandes de Déménagement</h2>
    <p class="text-muted">Consultez les demandes et proposez vos services</p>
  </div>

<?php if ($result->num_rows > 0): ?>
    <?php while ($user = $result->fetch_assoc()): 
        $id_client = $user['id_utilisateur'];
        $id_demenageur = $_SESSION['id_utilisateur'];
        $id_demande = $user['id_demande'];

        // Vérifie si une proposition existe déjà pour ce déménageur sur cette demande
        $check = $mysqli->prepare("SELECT COUNT(*) FROM proposition WHERE id_demenageur = ? AND id_demande = ?");
        $check->bind_param("ii", $id_demenageur, $id_demande);
        $check->execute();
        $check->bind_result($existe);
        $check->fetch();
        $check->close();
    ?>
        <div class="card border-0 shadow-lg mb-4 overflow-hidden">
          <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
              <h4 class="mb-0 fw-bold"><?= htmlspecialchars($user['titre']) ?></h4>
              <span class="badge bg-white text-primary fs-6 px-3 py-2">
                📅 <?= htmlspecialchars($user['date_prevue']) ?>
              </span>
            </div>
          </div>
          <div class="card-body p-4">
            <div class="row g-4 mb-4">
              <div class="col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">👤</div>
                  <div>
                    <div class="text-muted small">Client</div>
                    <div class="h6 mb-0 fw-semibold"><?= htmlspecialchars($user['nom_client']) ?></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">📦</div>
                  <div>
                    <div class="text-muted small">Volume</div>
                    <div class="h6 mb-0 fw-semibold"><?= htmlspecialchars($user['volume']) ?> m³</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">👥</div>
                  <div>
                    <div class="text-muted small">Déménageurs</div>
                    <div class="h6 mb-0 fw-semibold"><?= htmlspecialchars($user['nbr_demenageur']) ?> personnes</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">🚪</div>
                  <div>
                    <div class="text-muted small">Ascenseur</div>
                    <div class="h6 mb-0 fw-semibold"><?= $user['ascenseur'] ? 'Oui' : 'Non' ?></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row g-4 mb-4">
              <div class="col-md-6">
                <div class="card bg-light border-0 p-3">
                  <h6 class="fw-bold text-primary mb-3">📍 Adresse de départ</h6>
                  <p class="mb-1"><strong><?= htmlspecialchars($user['adresse_depart']) ?></strong></p>
                  <p class="mb-1 text-muted"><?= htmlspecialchars($user['ville_depart']) ?></p>
                  <p class="mb-0"><small class="text-muted">Type : <?= htmlspecialchars($user['type_logement_depart']) ?></small></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card bg-light border-0 p-3">
                  <h6 class="fw-bold text-primary mb-3">📍 Adresse d'arrivée</h6>
                  <p class="mb-1"><strong><?= htmlspecialchars($user['adresse_arrive']) ?></strong></p>
                  <p class="mb-1 text-muted"><?= htmlspecialchars($user['ville_arrive']) ?></p>
                  <p class="mb-0"><small class="text-muted">Type : <?= htmlspecialchars($user['type_logement_arrive']) ?></small></p>
                </div>
              </div>
            </div>

            <?php if (!empty($user['description'])): ?>
            <div class="mb-4">
              <h6 class="fw-bold text-primary mb-2">📝 Description</h6>
              <div class="card bg-light border-0 p-3">
                <p class="mb-0"><?= nl2br(htmlspecialchars($user['description'])) ?></p>
              </div>
            </div>
            <?php endif; ?>

            <?php
            $photos = $mysqli->query("SELECT photo_path FROM photos_demande WHERE id_demande = ".$user['id_demande']);
            if ($photos && $photos->num_rows > 0): ?>
              <div class="mb-4">
                <h6 class="fw-bold text-primary mb-3">📷 Photos</h6>
                <div class="d-flex flex-wrap gap-2">
                  <?php while ($p = $photos->fetch_assoc()): ?>
                    <img src="<?= htmlspecialchars($p['photo_path']) ?>" 
                         alt="Photo" 
                         class="img-thumbnail rounded" 
                         style="max-width:150px; height:auto;">
                  <?php endwhile; ?>
                </div>
              </div>
            <?php else: ?>
              <div class="mb-4">
                <p class="text-muted"><small>Aucune photo disponible</small></p>
              </div>
            <?php endif; ?>

            <!-- ✅ Formulaire pour proposer un prix -->
            <?php if ($_SESSION['statut'] === 'demenageur'): ?>
              <div class="border-top pt-4">
                <?php if ($existe == 0): ?>
                  <div class="card bg-success bg-opacity-10 border-success p-4">
                    <h6 class="fw-bold text-success mb-3">💰 Proposer un prix</h6>
                    <form action="ajouter_proposition.php" method="post" class="d-flex gap-3 align-items-end">
                      <input type="hidden" name="id_demande" value="<?= htmlspecialchars($id_demande) ?>">
                      <input type="hidden" name="id_client" value="<?= htmlspecialchars($id_client) ?>">
                      <input type="hidden" name="id_demenageur" value="<?= htmlspecialchars($id_demenageur) ?>">
                      <div class="flex-grow-1">
                        <label class="form-label fw-semibold">Montant proposé (€)</label>
                        <input type="number" name="prix" step="0.01" min="0" required class="form-control form-control-lg" placeholder="Ex: 500.00">
                      </div>
                      <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                        <span class="me-2">📤</span>Envoyer
                      </button>
                    </form>
                  </div>
                <?php else: ?>
                  <div class="alert alert-success d-flex align-items-center gap-3">
                    <span class="fs-3">✅</span>
                    <div>
                      <strong>Vous avez déjà proposé un prix pour cette demande.</strong>
                      <p class="mb-0 small">En attente de la réponse du client.</p>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="card border-0 shadow-lg">
      <div class="card-body text-center p-5">
        <div class="mb-4">
          <span class="display-1">😔</span>
        </div>
        <h4 class="fw-bold text-muted mb-3">Aucune demande disponible</h4>
        <p class="text-muted mb-0">Il n'y a actuellement aucune demande de déménagement disponible.</p>
      </div>
    </div>
<?php endif; ?>
</div>
  </div>
</div>
</div>

<?php
include('footer.inc.php');            
?>  
