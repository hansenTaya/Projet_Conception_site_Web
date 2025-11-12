<?php
session_start();
require_once("param.inc.php");

// Vérifie que l'utilisateur est bien un client
if (!isset($_SESSION['statut']) || $_SESSION['statut'] !== 'client') {
    $_SESSION['erreur'] = "Accès refusé.";
    header('Location: index.php');
    exit();
}

$id_proposition = isset($_POST['id_proposition']) ? (int) $_POST['id_proposition'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($id_proposition <= 0 || !in_array($action, ['accepter', 'refuser'])) {
    $_SESSION['erreur'] = "Requête invalide.";
    header('Location: propositions.php');
    exit();
}

// Connexion à la base
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    $_SESSION['erreur'] = "Erreur de connexion : " . $mysqli->connect_error;
    header('Location: propositions.php');
    exit();
}

// Détermine la réponse à insérer
$reponse = ($action === 'accepter') ? 'Acceptée' : 'Refusée';

// 1️⃣ Met à jour la table proposition
$stmt = $mysqli->prepare("UPDATE proposition SET reponse = ? WHERE id_proposition = ?");
$stmt->bind_param("si", $reponse, $id_proposition);
$stmt->execute();

// 2️⃣ Si le client a accepté, créer la mission
if ($action === 'accepter') {
    // On récupère les infos nécessaires
    $info = $mysqli->query("SELECT id_demenageur, id_demande, id_client, prix FROM proposition WHERE id_proposition = $id_proposition")->fetch_assoc();

    if ($info) {
        $stmt2 = $mysqli->prepare("
            INSERT INTO mission (id_demenageur, id_demande, id_client, prix, statut)
            VALUES (?, ?, ?, ?, 'Acceptée')
        ");
        $stmt2->bind_param("iiid", $info['id_demenageur'], $info['id_demande'], $info['id_client'], $info['prix']);
        $stmt2->execute();
        $stmt2->close();
    }

    // Refuser toutes les autres propositions pour la même demande
    $mysqli->query("UPDATE proposition SET reponse = 'Refusée' WHERE id_demande = {$info['id_demande']} AND id_proposition != $id_proposition");
}

$stmt->close();
$mysqli->close();

$_SESSION['message'] = ($action === 'accepter') ? "✅ Proposition acceptée et mission créée !" : "❌ Proposition refusée.";
header('Location: propositions.php');
exit();
?>
