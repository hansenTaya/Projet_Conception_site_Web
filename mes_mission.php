<?php
session_start();

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

echo "<h2 class='mb-4 text-center'>🚛 Mes missions</h2>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card shadow-sm p-3 mb-4 border-0'>";
        echo "  <div class='card-body'>";
        echo "    <h4 class='card-title text-primary'>" . htmlspecialchars($row['titre']) . "</h4>";
        echo "    <p class='card-text mb-2'><strong>Client :</strong> " . htmlspecialchars($row['nom_client']) . "</p>";
        echo "    <p class='card-text mb-2'><strong>Date :</strong> " . htmlspecialchars($row['date_prevue']) . "</p>";
        echo "    <p class='card-text mb-2'><strong>Adresse départ :</strong> " . htmlspecialchars($row['adresse_depart'] . ", " . $row['ville_depart']) . "</p>";
        echo "    <p class='card-text mb-2'><strong>Adresse arrivée :</strong> " . htmlspecialchars($row['adresse_arrive'] . ", " . $row['ville_arrive']) . "</p>";
        echo "    <p class='card-text mb-2'><strong>Type logement départ :</strong> " . htmlspecialchars($row['type_logement_depart']) . "</p>";
        echo "    <p class='card-text mb-2'><strong>Type logement arrivée :</strong> " . htmlspecialchars($row['type_logement_arrive']) . "</p>";
        echo "    <p class='card-text mb-2'><strong>Volume :</strong> " . htmlspecialchars($row['volume']) . " m³</p>";
        echo "    <p class='card-text mb-2'><strong>Ascenseur :</strong> " . ($row['ascenseur'] ? 'Oui' : 'Non') . "</p>";
        echo "    <p class='card-text mb-2'><strong>Nombre de déménageurs :</strong> " . htmlspecialchars($row['nbr_demenageur']) . "</p>";
        echo "    <p class='card-text mb-2'><strong>Montant proposé :</strong> " . htmlspecialchars($row['prix']) . " €</p>";
        echo "    <p class='card-text mb-2'><strong>Description :</strong> " . nl2br(htmlspecialchars($row['description'])) . "</p>";
        if (!empty($row['photo_path'])) {
            echo "<p><img src='" . htmlspecialchars($row['photo_path']) . "' alt='Photo' style='max-width:300px; height:auto;' class='img-thumbnail'></p>";
        }
        echo "    <p class='card-text mb-0'><span class='badge bg-success'>Acceptée</span></p>";
        echo "  </div>";
        echo "</div>";
    }
} else {
    echo "<div class='alert alert-info text-center p-4 rounded shadow-sm'>";
    echo "  <h5>Aucune mission en cours 💤</h5>";
    echo "  <p class='mb-0'>Vous n'avez actuellement aucun déménagement accepté.</p>";
    echo "</div>";
}
?>
