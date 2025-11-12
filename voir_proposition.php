<?php
session_start();

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
    // Le client voit les propositions qu'il a reçues
    $sql = "SELECT p.*, u.nom AS nom_demenageur, d.titre 
            FROM proposition p
            JOIN utilisateur u ON p.id_demenageur = u.id_utilisateur
            JOIN demande d ON p.id_demande = d.id_demande
            WHERE p.id_client = ?
            ORDER BY p.date_creation DESC";
} else {
    // Le déménageur voit les propositions qu'il a faites
    $sql = "SELECT p.*, u.nom AS nom_client, d.titre 
            FROM proposition p
            JOIN utilisateur u ON p.id_client = u.id_utilisateur
            JOIN demande d ON p.id_demande = d.id_demande
            WHERE p.id_demenageur = ?
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


echo "<h2 class='mb-4 text-center'>💰 Mes propositions</h2>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Détermination de la couleur selon la réponse
        $reponse = isset($row['reponse']) ? $row['reponse'] : 'En attente';
        $badgeClass = 'secondary'; // par défaut
        if ($reponse === 'Acceptée') $badgeClass = 'success';
        elseif ($reponse === 'Refusée') $badgeClass = 'danger';
        elseif ($reponse === 'En attente') $badgeClass = 'warning';

        echo "<div class='card shadow-sm p-3 mb-4 border-0'>";
        echo "  <div class='card-body'>";
        echo "    <h4 class='card-title text-primary'>" . htmlspecialchars($row['titre']) . "</h4>";
        echo "    <p class='card-text mb-2'><strong>💸 Montant proposé :</strong> " . htmlspecialchars($row['prix']) . " €</p>";
        echo "    <p class='card-text mb-0'><strong>Statut :</strong> <span class='badge bg-$badgeClass'>" . htmlspecialchars($reponse) . "</span></p>";
        echo "  </div>";
        echo "</div>";
    }
} else {
    echo "<div class='alert alert-info text-center p-4 rounded shadow-sm'>";
    echo "  <h5>Aucune proposition pour le moment 💤</h5>";
    echo "  <p class='mb-0'>Revenez plus tard pour voir les réponses des déménageurs.</p>";
    echo "</div>";
}

?>
