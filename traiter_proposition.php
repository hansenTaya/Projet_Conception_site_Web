<?php
session_start();
require_once("param.inc.php");

// Vérification des données reçues
if (!isset($_POST['id_proposition'], $_POST['action'])) {
    $_SESSION['message'] = "⚠️ Action invalide.";
    header("Location: voir_proposition.php");
    exit();
}

$id_proposition = (int) $_POST['id_proposition'];
$action = trim($_POST['action']);

// Connexion BDD
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// 🔎 Récupère infos sur la proposition + demande
$sql = "
    SELECT p.id_demande, d.nbr_demenageur
    FROM proposition p
    JOIN demande d ON p.id_demande = d.id_demande
    WHERE p.id_proposition = ?
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_proposition);
$stmt->execute();
$result = $stmt->get_result();

$info = $result->fetch_assoc();
if (!$info) {
    $_SESSION['message'] = "❌ Proposition introuvable.";
    header("Location: voir_proposition.php");
    exit();
}

$id_demande = $info['id_demande'];
$nb_max = (int)$info['nbr_demenageur'];


// --------------------------------------------------------------
// 🟢 ACTION : ACCEPTER
// --------------------------------------------------------------
if ($action === 'accepter') {

    // 1) La proposition devient acceptée
    $mysqli->query("UPDATE proposition SET reponse='acceptee' WHERE id_proposition=$id_proposition");

    // 2) Met à jour la demande en 'acceptee'
    $mysqli->query("UPDATE demande SET statut='acceptee' WHERE id_demande=$id_demande");

    // 3) Compte combien acceptées au total
    $nb_acc = $mysqli->query("
        SELECT COUNT(*) AS total 
        FROM proposition 
        WHERE id_demande=$id_demande AND reponse='acceptee'
    ")->fetch_assoc()['total'];

    // 4) Si on atteint le quota → refuser les autres
    if ($nb_acc >= $nb_max) {
        $mysqli->query("
            UPDATE proposition 
            SET reponse='refusee' 
            WHERE id_demande=$id_demande AND reponse='en_attente'
        ");
    }

    $_SESSION['message'] = "✅ Proposition acceptée ! ($nb_acc / $nb_max déménageur(s) confirmé(s))";

}


// --------------------------------------------------------------
// 🔴 ACTION : REFUSER
// --------------------------------------------------------------
elseif ($action === 'refuser') {

    // 1) Supprime la proposition
    $mysqli->query("DELETE FROM proposition WHERE id_proposition=$id_proposition");

    // 2) Vérifie s'il reste d'autres propositions
    $reste = $mysqli->query("
        SELECT COUNT(*) AS total FROM proposition WHERE id_demande=$id_demande
    ")->fetch_assoc()['total'];

    // 3) S'il ne reste AUCUNE proposition → demande refusée
    if ($reste == 0) {
        $mysqli->query("UPDATE demande SET statut='refusee' WHERE id_demande=$id_demande");
    }

    $_SESSION['message'] = "❌ Proposition refusée.";
}


// --------------------------------------------------------------
$stmt->close();
$mysqli->close();
header("Location: voir_proposition.php");
exit();

?>
