<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['erreur'] = "Veuillez vous connecter.";
    header("Location: connexion.php");
    exit(); // 🔥 OBLIGATOIRE
}
require_once("param.inc.php");
$titre = "Mes propositions";
include('header.inc.php');
include('menu_demenageur.php');

if (!isset($_SESSION['id_utilisateur'])) {
    die("Erreur : l'utilisateur n'est pas connecté.");
}

$id_demenageur = intval($_SESSION['id_utilisateur']);

$mysqli = new mysqli($host, $login, $passwd, $dbname);

$sql = "SELECT p.*, d.titre, u.nom AS nom_client
        FROM proposition p
        JOIN demande d ON p.id_demande = d.id_demande
        JOIN utilisateur u ON p.id_client = u.id_utilisateur
        WHERE p.id_demenageur = $id_demenageur
        ORDER BY p.date_creation DESC";

$result = $mysqli->query($sql);
if (!$result) {
    die("Erreur SQL : " . $mysqli->error . "<br>Requête : " . $sql);
}
?>
<div class="mb-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold text-primary mb-2">💰 Mes Propositions</h2>
    <p class="text-muted">Suivez l'état de vos offres envoyées aux clients</p>
  </div>

<?php if ($result->num_rows > 0): ?>
    <?php while ($p = $result->fetch_assoc()): 
        $badgeClass = 'warning';
        $badgeText = 'En attente';
        $badgeIcon = '⏳';
        if ($p['reponse'] === 'acceptee') {
            $badgeClass = 'success';
            $badgeText = 'Acceptée';
            $badgeIcon = '✅';
        } elseif ($p['reponse'] === 'refusee') {
            $badgeClass = 'danger';
            $badgeText = 'Refusée';
            $badgeIcon = '❌';
        }
    ?>
        <div class="card border-0 shadow-lg mb-4 overflow-hidden">
          <div class="card-header bg-gradient bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
              <h4 class="mb-0 fw-bold"><?= htmlspecialchars($p['titre']) ?></h4>
              <span class="badge bg-white text-<?= $badgeClass ?> fs-6 px-3 py-2">
                <?= $badgeIcon ?> <?= $badgeText ?>
              </span>
            </div>
          </div>
          <div class="card-body p-4">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                  <div class="fs-1">👤</div>
                  <div>
                    <div class="text-muted small">Client</div>
                    <div class="h5 mb-0 fw-semibold"><?= htmlspecialchars($p['nom_client']) ?></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                  <div class="fs-1">💸</div>
                  <div>
                    <div class="text-muted small">Prix proposé</div>
                    <div class="h4 mb-0 fw-bold text-primary"><?= htmlspecialchars($p['prix']) ?> €</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="card border-0 shadow-lg">
      <div class="card-body text-center p-5">
        <div class="mb-4">
          <span class="display-1">💤</span>
        </div>
        <h4 class="fw-bold text-muted mb-3">Aucune proposition pour le moment</h4>
        <p class="text-muted mb-4">Vous n'avez pas encore envoyé de propositions.</p>
        <a href="afficher_demande.php" class="btn btn-primary btn-lg">
          <span class="me-2">📋</span>Voir les demandes
        </a>
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


