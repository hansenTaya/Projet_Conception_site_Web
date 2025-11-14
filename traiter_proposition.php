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

// Connexion à la base
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Récupération des infos sur la proposition et la demande associée
$sql = "
    SELECT p.id_proposition, p.id_demande, d.nbr_demenageur
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
$nb_max_demenageurs = (int) $info['nbr_demenageur'];

if ($action === 'accepter') {
    // ✅ Met à jour la réponse à 'acceptee'
    $update = $mysqli->prepare("UPDATE proposition SET reponse = 'acceptee' WHERE id_proposition = ?");
    $update->bind_param("i", $id_proposition);
    $update->execute();

    // ✅ Compte combien de propositions ont été acceptées pour cette demande
    $resCount = $mysqli->query("
        SELECT COUNT(*) AS nb_acceptes 
        FROM proposition 
        WHERE id_demande = $id_demande AND reponse = 'acceptee'
    ");
    $nb_acceptes = (int)$resCount->fetch_assoc()['nb_acceptes'];

    // ✅ Si on atteint le nombre max de déménageurs, on refuse les autres
    if ($nb_acceptes >= $nb_max_demenageurs) {
        $mysqli->query("
            UPDATE proposition 
            SET reponse = 'refusee'
            WHERE id_demande = $id_demande AND reponse = 'en_attente'
        ");
    }

    $_SESSION['message'] = "✅ Proposition acceptée avec succès ! ($nb_acceptes / $nb_max_demenageurs déménageur(s) confirmé(s))";

} elseif ($action === 'refuser') {
    // ❌ Supprime la proposition refusée
    $delete = $mysqli->prepare("DELETE FROM proposition WHERE id_proposition = ?");
    $delete->bind_param("i", $id_proposition);
    $delete->execute();

    $_SESSION['message'] = "❌ Proposition supprimée avec succès.";

} else {
    $_SESSION['message'] = "⚠️ Action non reconnue.";
}

// Fermeture propre
$stmt->close();
$mysqli->close();

// Rechargement de la page
header("Location: voir_proposition.php");
exit();
?>
