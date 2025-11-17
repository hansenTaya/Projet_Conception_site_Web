<?php
session_start();
if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['erreur'] = "Veuillez vous connecter.";
    header("Location: connexion.php");
    exit(); // 🔥 OBLIGATOIRE
}
require_once("param.inc.php");
$titre = "Mes missions";
include('header.inc.php');
include('menu_demenageur.php');

$id_utilisateur = $_SESSION['id_utilisateur'];
$statut = $_SESSION['statut'];

// Vérifie que l'utilisateur est un déménageur
if ($statut !== 'demenageur') {
    echo "<div class='alert alert-danger text-center'>Accès refusé. Cette page est réservée aux déménageurs.</div>";
    exit;
}

// Connexion à la base
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Récupère les propositions acceptées pour ce déménageur avec les détails de la demande
$sql = "SELECT p.*, 
               d.titre, d.date_prevue, d.adresse_depart, d.ville_depart, 
               d.adresse_arrive, d.ville_arrive,
               d.type_logement_depart, d.type_logement_arrive, 
               d.volume, d.nbr_demenageur,
               d.ascenseur, d.description, 
               u.nom AS nom_client
        FROM proposition p
        JOIN demande d ON p.id_demande = d.id_demande
        JOIN utilisateur u ON p.id_client = u.id_utilisateur
        WHERE p.id_demenageur = ?
        AND p.reponse = 'acceptee'
        ORDER BY d.date_prevue DESC";


$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();

?>
<div class="mb-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-primary mb-2">🚛 Mes Missions</h2>
    <p class="text-muted">Déménagements acceptés et en cours</p>
  </div>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="card border-0 shadow-lg mb-4 overflow-hidden">
          <div class="card-header bg-success bg-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
              <h4 class="mb-0 fw-bold"><?= htmlspecialchars($row['titre']) ?></h4>
              <span class="badge bg-white text-success fs-6 px-3 py-2">
                ✅ Acceptée
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
                    <div class="h6 mb-0 fw-semibold"><?= htmlspecialchars($row['nom_client']) ?></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">📅</div>
                  <div>
                    <div class="text-muted small">Date prévue</div>
                    <div class="h6 mb-0 fw-semibold"><?= htmlspecialchars($row['date_prevue']) ?></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">📦</div>
                  <div>
                    <div class="text-muted small">Volume</div>
                    <div class="h6 mb-0 fw-semibold"><?= htmlspecialchars($row['volume']) ?> m³</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">💸</div>
                  <div>
                    <div class="text-muted small">Montant</div>
                    <div class="h5 mb-0 fw-bold text-success"><?= htmlspecialchars($row['prix']) ?> €</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row g-4 mb-4">
              <div class="col-md-6">
                <div class="card bg-light border-0 p-3">
                  <h6 class="fw-bold text-primary mb-3">📍 Adresse de départ</h6>
                  <p class="mb-1"><strong><?= htmlspecialchars($row['adresse_depart']) ?></strong></p>
                  <p class="mb-1 text-muted"><?= htmlspecialchars($row['ville_depart']) ?></p>
                  <p class="mb-0"><small class="text-muted">Type : <?= htmlspecialchars($row['type_logement_depart']) ?></small></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card bg-light border-0 p-3">
                  <h6 class="fw-bold text-primary mb-3">📍 Adresse d'arrivée</h6>
                  <p class="mb-1"><strong><?= htmlspecialchars($row['adresse_arrive']) ?></strong></p>
                  <p class="mb-1 text-muted"><?= htmlspecialchars($row['ville_arrive']) ?></p>
                  <p class="mb-0"><small class="text-muted">Type : <?= htmlspecialchars($row['type_logement_arrive']) ?></small></p>
                </div>
              </div>
            </div>

            <div class="row g-4 mb-4">
              <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                  <span class="fs-4">🚪</span>
                  <div>
                    <div class="text-muted small">Ascenseur</div>
                    <div class="fw-semibold"><?= $row['ascenseur'] ? 'Oui' : 'Non' ?></div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                  <span class="fs-4">👥</span>
                  <div>
                    <div class="text-muted small">Déménageurs</div>
                    <div class="fw-semibold"><?= htmlspecialchars($row['nbr_demenageur']) ?> personnes</div>
                  </div>
                </div>
              </div>
            </div>

            <?php if (!empty($row['description'])): ?>
            <div class="mb-4">
              <h6 class="fw-bold text-primary mb-2">📝 Description</h6>
              <div class="card bg-light border-0 p-3">
                <p class="mb-0"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
              </div>
            </div>
            <?php endif; ?>

            <?php
            $photos = $mysqli->query("SELECT photo_path FROM photos_demande WHERE id_demande = " . $row['id_demande']);
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
            <?php endif; ?>

            <div class="border-top pt-4">
              <div class="d-flex gap-3 justify-content-end">
                <a href="messagerie.php?destinataire_id=<?= $row['id_client'] ?>" class="btn btn-outline-primary btn-lg px-4">
                  <span class="me-2">💬</span>Contacter le client
                </a>
              </div>
            </div>
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
        <h4 class="fw-bold text-muted mb-3">Aucune mission en cours</h4>
        <p class="text-muted mb-4">Vous n'avez actuellement aucun déménagement accepté.</p>
        <a href="afficher_demande.php" class="btn btn-primary btn-lg">
          <span class="me-2">📋</span>Voir les demandes
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
