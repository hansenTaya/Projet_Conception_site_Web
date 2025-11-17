<?php
session_start();
if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['erreur'] = "Veuillez vous connecter.";
    header("Location: connexion.php");
    exit(); // 🔥 OBLIGATOIRE
}
require_once("param.inc.php");
$titre = "Demande de Déménagement";
  include('header.inc.php');
  include('menu_client.inc.php');

$id_utilisateur = $_SESSION['id_utilisateur'];
$statut = $_SESSION['statut'];

// Connexion à la base
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Requête selon le rôle de l'utilisateur
if ($statut === 'client') {
    $sql = "SELECT p.*, u.nom AS nom_demenageur, d.titre 
            FROM proposition p
            JOIN utilisateur u ON p.id_demenageur = u.id_utilisateur
            JOIN demande d ON p.id_demande = d.id_demande
            WHERE p.id_client = ? 
            AND p.reponse = 'en_attente'
            ORDER BY p.date_creation DESC";
} else {
    $sql = "SELECT p.*, u.nom AS nom_client, d.titre 
            FROM proposition p
            JOIN utilisateur u ON p.id_client = u.id_utilisateur
            JOIN demande d ON p.id_demande = d.id_demande
            WHERE p.id_demenageur = ?
            AND p.reponse = 'en_attente'
            ORDER BY p.date_creation DESC";
}
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();

// Vérifie le résultat
if (!$result) {
    die("Erreur SQL : " . $mysqli->error . "<br>Requête : " . $sql);
}

// Affichage
?>
<div class="mb-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold text-primary mb-2">💰 Mes Propositions</h2>
    <p class="text-muted">Gérez les offres reçues pour vos demandes de déménagement</p>
  </div>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Détermination de la couleur selon la réponse
        $reponse = isset($row['reponse']) ? $row['reponse'] : 'en_attente';
        $badgeClass = 'secondary';
        $badgeText = 'En attente';
        $badgeIcon = '⏳';
        if ($reponse === 'acceptee') {
            $badgeClass = 'success';
            $badgeText = 'Acceptée';
            $badgeIcon = '✅';
        } elseif ($reponse === 'refusee') {
            $badgeClass = 'danger';
            $badgeText = 'Refusée';
            $badgeIcon = '❌';
        } elseif ($reponse === 'en_attente') {
            $badgeClass = 'warning';
            $badgeText = 'En attente';
            $badgeIcon = '⏳';
        }

        $nom_contact = ($statut === 'client') ? htmlspecialchars($row['nom_demenageur']) : htmlspecialchars($row['nom_client']);
        ?>
        <div class="card border-0 shadow-lg mb-4 overflow-hidden">
          <div class="card-header bg-gradient bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
              <h4 class="mb-0 fw-bold"><?= htmlspecialchars($row['titre']) ?></h4>
              <span class="badge bg-white text-<?= $badgeClass ?> fs-6 px-3 py-2">
                <?= $badgeIcon ?> <?= $badgeText ?>
              </span>
            </div>
          </div>
          <div class="card-body p-4">
            <div class="row g-4 mb-4">
              <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                  <div class="fs-1">💸</div>
                  <div>
                    <div class="text-muted small">Montant proposé</div>
                    <div class="h4 mb-0 fw-bold text-primary"><?= htmlspecialchars($row['prix']) ?> €</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                  <div class="fs-1">👤</div>
                  <div>
                    <div class="text-muted small"><?= ($statut === 'client') ? 'Déménageur' : 'Client' ?></div>
                    <div class="h5 mb-0 fw-semibold"><?= $nom_contact ?></div>
                  </div>
                </div>
              </div>
            </div>
            
            <?php if ($reponse === 'en_attente'): ?>
            <div class="border-top pt-4">
              <form action="traiter_proposition.php" method="post" class="d-flex gap-3 justify-content-end">
                <input type="hidden" name="id_proposition" value="<?= $row['id_proposition'] ?>">
                <button type="submit" name="action" value="accepter" class="btn btn-success btn-lg px-5 shadow-sm">
                  <span class="me-2">✅</span>Accepter
                </button>
                <button type="submit" name="action" value="refuser" class="btn btn-outline-danger btn-lg px-5">
                  <span class="me-2">❌</span>Refuser
                </button>
              </form>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="card border-0 shadow-lg">
      <div class="card-body text-center p-5">
        <div class="mb-4">
          <span class="display-1">💤</span>
        </div>
        <h4 class="fw-bold text-muted mb-3">Aucune proposition pour le moment</h4>
        <p class="text-muted mb-4">Revenez plus tard pour voir les réponses des déménageurs.</p>
        <a href="demande.php" class="btn btn-primary btn-lg">
          <span class="me-2">🧳</span>Créer une demande
        </a>
      </div>
    </div>
    <?php
}
?>
</div>
  </div>
</div>
</div>

<?php
include('footer.inc.php');
?>
