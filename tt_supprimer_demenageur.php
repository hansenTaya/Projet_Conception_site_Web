<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['statut']) || $_SESSION['statut'] !== 'administrateur') {
    header('Location: deconnexion.php');
    exit();
}
require_once("param.inc.php");

$mysqli = new mysqli($host, $login, $passwd, $dbname);  
if ($mysqli->connect_error) {
    die("Problème de connexion à la base de données : " . $mysqli->connect_error);
}   
$id_demenageur = intval($_POST['id_demenageur']);
$sql = "DELETE FROM utilisateur WHERE id_utilisateur = ?";      
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_demenageur);    
if ($stmt->execute()) {
    echo "Demenageur supprimé avec succès.";
} else {
    echo "Erreur lors de la suppression du demenageur : " . $stmt->error;
}
$stmt->close();
$mysqli->close();  
header('Location: page_admin.php');
?> 