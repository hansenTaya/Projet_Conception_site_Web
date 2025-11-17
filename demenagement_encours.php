<?php
session_start();

require_once("param.inc.php");
$titre = "Déménagements en cours";
include('header.inc.php');
include('menu_client.inc.php');

$id_utilisateur = $_SESSION['id_utilisateur'];
$statut = $_SESSION['statut'];

// Connexion à la base
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Requête selon le rôle de l'utilisateur (seulement les propositions acceptées)
if ($statut === 'client') {
    $sql = "SELECT p.*, u.nom AS nom_demenageur, d.titre 
            FROM proposition p
            JOIN utilisateur u ON p.id_demenageur = u.id_utilisateur
            JOIN demande d ON p.id_demande = d.id_demande
            WHERE p.id_client = ? 
            AND p.reponse = 'acceptee'
            ORDER BY p.date_creation DESC";
} else { // déménageur
    $sql = "SELECT p.*, u.nom AS nom_client, d.titre 
            FROM proposition p
            JOIN utilisateur u ON p.id_client = u.id_utilisateur
            JOIN demande d ON p.id_demande = d.id_demande
            WHERE p.id_demenageur = ?
            AND p.reponse = 'acceptee'
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
  <div class="text-center mb-5">
    <h2 class="fw-bold text-primary mb-2">🚛 Déménagements en cours</h2>
    <p class="text-muted">Suivez vos missions de déménagement actives</p>
  </div>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nom_contact = ($statut === 'client') ? htmlspecialchars($row['nom_demenageur']) : htmlspecialchars($row['nom_client']);
        $role_contact = ($statut === 'client') ? 'Déménageur' : 'Client';
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
            <div class="row g-4">
              <div class="col-md-4">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">👤</div>
                  <div>
                    <div class="text-muted small"><?= $role_contact ?></div>
                    <div class="h5 mb-0 fw-semibold"><?= $nom_contact ?></div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">💸</div>
                  <div>
                    <div class="text-muted small">Montant convenu</div>
                    <div class="h4 mb-0 fw-bold text-success"><?= htmlspecialchars($row['prix']) ?> €</div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded h-100">
                  <div class="fs-1">📅</div>
                  <div>
                    <div class="text-muted small">Date de création</div>
                    <div class="h6 mb-0 fw-semibold">
                      <?= isset($row['date_creation']) ? date('d/m/Y', strtotime($row['date_creation'])) : 'N/A' ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-4 pt-4 border-top">
              <div class="d-flex gap-3 justify-content-end">
                <a href="messagerie.php<?= ($statut === 'client') ? '?destinataire_id=' . $row['id_demenageur'] : '?destinataire_id=' . $row['id_client'] ?>" class="btn btn-outline-primary btn-lg px-4">
                  <span class="me-2">💬</span>Contacter
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
        <h4 class="fw-bold text-muted mb-3">Aucun déménagement en cours</h4>
        <p class="text-muted mb-4">Vous n'avez actuellement aucun déménagement accepté.</p>
        <?php if ($statut === 'client'): ?>
        <a href="demande.php" class="btn btn-primary btn-lg me-2">
          <span class="me-2">🧳</span>Créer une demande
        </a>
        <?php endif; ?>
        <a href="voir_proposition.php" class="btn btn-outline-primary btn-lg">
          <span class="me-2">📦</span>Voir les propositions
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
