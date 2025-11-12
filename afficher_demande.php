<?php
session_start();
require_once("param.inc.php");
include('menu_demenageur.php');
// Connexion à la BDD
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) die("Erreur de connexion : " . $mysqli->connect_error);

// Récupération de toutes les demandes avec le nom du client
$sql = "
    SELECT d.*, u.nom AS nom_client
    FROM demande d
    JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
    ORDER BY d.date DESC
";

$result = $mysqli->query($sql);
if (!$result) {
    die("Erreur SQL : " . $mysqli->error);
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
                <img src="<?= htmlspecialchars($user['photo_path']) ?>" alt="Photo de la demande"
                     style="max-width:300px; height:auto; border:1px solid #ccc; padding:5px;">
            <?php endif; ?>
<!-- ✅ Formulaire pour proposer un prix -->
<?php if ($_SESSION['statut'] === 'demenageur'): ?>

    <?php
    // Récupération de l'id du client
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

    <?php if ($existe == 0): ?>
        <!-- Le déménageur n’a pas encore fait de proposition -->
        <form action="ajouter_proposition.php" method="post" class="mt-2">
            <input type="hidden" name="id_demande" value="<?= htmlspecialchars($id_demande) ?>">
            <input type="hidden" name="id_client" value="<?= htmlspecialchars($id_client) ?>">
            <input type="hidden" name="id_demenageur" value="<?= htmlspecialchars($id_demenageur) ?>">

            <label>💰 Proposer un prix (€):</label>
            <input type="number" name="prix" step="0.01" min="0" required class="form-control w-25 d-inline">
            <button type="submit" class="btn btn-primary btn-sm ms-2">Envoyer</button>
        </form>
    <?php else: ?>
        <!-- Il a déjà proposé un prix -->
        <p class="text-success"><strong>✅ Vous avez déjà proposé un prix pour cette demande.</strong></p>
    <?php endif; ?>

<?php else: ?>
    <p class="text-muted"><em>Seuls les déménageurs peuvent proposer un prix.</em></p>
<?php endif; ?>

        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="alert alert-info">
        😔 Désolé, aucune demande de déménagement disponible pour le moment.
    </div>
<?php endif; ?>
