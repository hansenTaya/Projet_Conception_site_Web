<?php
session_start();
require_once("param.inc.php");

// Connexion à la BDD
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) die("Erreur de connexion");

// On récupère toutes les demandes créées par des clients
$sql = "SELECT *
        FROM demande
        ORDER BY date DESC"; 

$sql = "SELECT d.*, u.nom AS nom_client
        FROM demande d
        JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
        ORDER BY d.date DESC";

$result = $mysqli->query($sql);
if (!$result) {
    die("Erreur SQL : " . $mysqli->error);
}

$result = $mysqli->query($sql);
if (!$result) {
    die("Erreur SQL : " . $mysqli->error . "<br>Requête : " . $sql);
}
?>

<h2>📋 Liste des demandes de déménagement</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($user = $result->fetch_assoc()): ?>
    <div class="card p-3 mb-3">
        <h4><?= htmlspecialchars($user['titre']) ?></h4>
        <p><strong>Date :</strong> <?= htmlspecialchars($user['date']) ?></p>
       <p><strong>Client :</strong> <?= htmlspecialchars($user['nom_client']) ?></p>
        <p><strong>Adresse départ :</strong> <?= htmlspecialchars($user['adresse_depart']) ?>, <?= htmlspecialchars($user['ville_depart']) ?></p>
        <p><strong>Adresse arrivée :</strong> <?= htmlspecialchars($user['adresse_arrive']) ?>, <?= htmlspecialchars($user['ville_arrive']) ?></p>
        <p><strong>Type logement départ :</strong> <?= htmlspecialchars($user['type_logement_depart']) ?></p>
        <p><strong>Type logement arrivée :</strong> <?= htmlspecialchars($user['type_logement_arrive']) ?></p>
        <p><strong>Volume :</strong> <?= htmlspecialchars($user['volume']) ?> m³</p>
        <p><strong>Nombre de déménageurs :</strong> <?= htmlspecialchars($user['nbr_demenageur']) ?></p>
        <p><strong>Ascenseur :</strong> <?= $user['ascenseur'] ? 'Oui' : 'Non' ?></p>
        <p><strong>Description :</strong> <?= htmlspecialchars($user['description']) ?></p>

        <?php if (!empty($user['photo_path'])): ?>
            <p><strong>Photo :</strong></p>
            <img src="<?= htmlspecialchars($user['photo_path']) ?>" alt="Photo de la demande" style="max-width:300px; height:auto; border:1px solid #ccc; padding:5px;">
        <?php endif; ?>



        <!-- Formulaire pour proposer un prix -->
        <form action="ajouter_proposition.php" method="post" class="mt-2">
            <input type="hidden" name="id_demande" value="<?= $user['id_demande'] ?>">
            <label>💰 Proposer un prix (€):</label>
            <input type="number" name="prix" step="0.01" required class="form-control w-25 d-inline">
            <button type="submit" class="btn btn-primary btn-sm">Envoyer</button>
        </form>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="alert alert-info">😔 Désolé, aucune demande de déménagement disponible pour le moment.</div>
<?php endif; ?>
