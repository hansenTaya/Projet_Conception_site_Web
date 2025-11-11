<?php
session_start();
require_once("param.inc.php");

$id_demenageur = $_SESSION['id_utilisateur'];
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

<h2>💰 Mes propositions</h2>
<?php while ($p = $result->fetch_assoc()): ?>
<div class="card mb-3 p-3">
    <h5><?= htmlspecialchars($p['titre']) ?></h5>
    <p>Client : <?= htmlspecialchars($p['nom_client']) ?></p>
    <p>Prix proposé : <?= $p['prix'] ?> €</p>
    <p>Statut :
        <?php if ($p['reponse'] == 'acceptee'): ?>
            <span class="badge bg-success">Acceptée ✅</span>
        <?php elseif ($p['reponse'] == 'refusee'): ?>
            <span class="badge bg-danger">Refusée ❌</span>
        <?php else: ?>
            <span class="badge bg-warning">En attente...</span>
        <?php endif; ?>
    </p>
</div>
<?php endwhile; ?>
