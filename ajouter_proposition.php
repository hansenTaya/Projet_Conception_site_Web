<?php
session_start();
require_once("param.inc.php");

// Vérifier que l'utilisateur est bien un déménageur
if ($_SESSION['statut'] !== 'demenageur') {
    $_SESSION['erreur'] = "Accès refusé. Vous devez être un déménageur.";
    header('Location: index.php');
    exit();
}

$id_demande = (int) $_POST['id_demande'];
$id_client = (int) $_POST['id_client'];  // Récupéré depuis la demande
$id_demenageur = $_SESSION['id_utilisateur'];
$prix = (float) $_POST['prix'];

$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    $_SESSION['erreur'] = "Erreur de connexion : " . $mysqli->connect_error;
    header('Location: liste_demandes.php');
    exit();
}

$stmt = $mysqli->prepare("
    INSERT INTO proposition (id_demande, id_client, id_demenageur, prix, reponse)
    VALUES (?, ?, ?, ?, 'en_attente')
");

$stmt->bind_param("iiid", $id_demande, $id_client, $id_demenageur, $prix);

if ($stmt->execute()) {
    $_SESSION['message'] = "💬 Proposition envoyée avec succès !";
} else {
    $_SESSION['erreur'] = "❌ Erreur lors de l'envoi : " . $stmt->error;
}

header('Location: liste_demandes.php');
exit();
?>

