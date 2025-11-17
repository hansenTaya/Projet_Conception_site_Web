<?php
session_start();
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
require_once("param.inc.php");

// Vérifie que l'utilisateur connecté est bien un déménageur
if (!isset($_SESSION['statut']) || $_SESSION['statut'] !== 'demenageur') {
    $_SESSION['erreur'] = "Accès refusé. Vous devez être un déménageur.";
    header('Location: index.php');
    exit();
}

// Sécurisation des données reçues
$id_demande = isset($_POST['id_demande']) ? (int) $_POST['id_demande'] : 0;
$id_client = isset($_POST['id_client']) ? (int) $_POST['id_client'] : 0;
$id_demenageur = (int) $_SESSION['id_utilisateur']; // déménageur connecté
$prix = isset($_POST['prix']) ? (float) $_POST['prix'] : 0.0;

// Vérification de validité
if ($id_demande <= 0 || $id_client <= 0 || $id_demenageur <= 0 || $prix <= 0) {
    $_SESSION['erreur'] = "Champs manquants ou invalides.";
    header('Location: liste_demandes.php');
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    $_SESSION['erreur'] = "Erreur de connexion : " . $mysqli->connect_error;
    header('Location: liste_demandes.php');
    exit();
}

// Préparation de l’insertion
$stmt = $mysqli->prepare("
    INSERT INTO proposition (id_demande, id_client, id_demenageur, prix, reponse)
    VALUES (?, ?, ?, ?, 'en_attente')
");

$stmt->bind_param("iiid", $id_demande, $id_client, $id_demenageur, $prix);

// Exécution
if ($stmt->execute()) {
    $_SESSION['message'] = "💬 Proposition envoyée avec succès !";
} else {
    $_SESSION['erreur'] = "❌ Erreur lors de l'envoi : " . $stmt->error;
}

$stmt->close();
$mysqli->close();

// Redirection après ajout
header('Location: afficher_demande.php');
exit();
?>
