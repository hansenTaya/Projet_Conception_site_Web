<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("param.inc.php");
include('header.inc.php');
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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>🚚 Just Move It :) - Déménageur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">🚚 Just Move It :) - Déménageur</span>
        <span class="text-white">Connecté : <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></span>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">💰 Mes propositions</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($p = $result->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm p-3">
                <h5 class="card-title"><?= htmlspecialchars($p['titre']) ?></h5>
                <p>Client : <strong><?= htmlspecialchars($p['nom_client']) ?></strong></p>
                <p>Prix proposé : <?= htmlspecialchars($p['prix']) ?> €</p>
                <p>Statut :
                    <?php if ($p['reponse'] === 'acceptee'): ?>
                        <span class="badge bg-success">Acceptée ✅</span>
                    <?php elseif ($p['reponse'] === 'refusee'): ?>
                        <span class="badge bg-danger">Refusée ❌</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">En attente...</span>
                    <?php endif; ?>
                </p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">Aucune proposition trouvée pour l’instant.</div>
    <?php endif; ?>

    
</div>

</body>
</html>


