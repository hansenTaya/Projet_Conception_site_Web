<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("param.inc.php");
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Problème de connexion à la base de données : " . $mysqli->connect_error);
}
$id_demande = intval($_POST['id_demande']);
$sql = "DELETE FROM demande WHERE id_demande = ?";      
$stmt = $mysqli->prepare($sql);   
$stmt->bind_param("i", $id_demande);
if ($stmt->execute()) {
    echo "Demande supprimée avec succès.";
} else {
    echo "Erreur lors de la suppression de la demande : " . $stmt->error;
}
$stmt->close();
$mysqli->close();   
header('Location: page_admin.php');
?>