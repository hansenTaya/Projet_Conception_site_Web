<?php
session_start();
require_once("param.inc.php");

$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Erreur DB : " . $mysqli->connect_error);
}

$id_utilisateur = $_SESSION['id_utilisateur'];

// Récupération du formulaire
$date = $_POST['date'];
$titre = $_POST['titre'];
$adresse_depart = $_POST['adresse_depart'];
$ville_depart = $_POST['ville_depart'];
$adresse_arrive = $_POST['adresse_arrive'];
$ville_arrive = $_POST['ville_arrive'];
$type_logement_depart = $_POST['type_logement_depart'];
$type_logement_arrive = $_POST['type_logement_arrive'];
$volume = (float) $_POST['volume'];
$nbr_demenageur = (int) $_POST['nbr_demenageur'];
$ascenseur = isset($_POST['ascenseur']) ? 1 : 0;
$description = $_POST['description'];

// Vérification date >= aujourd’hui
$today = date("Y-m-d");
if ($date < $today) {
    $_SESSION['erreur'] = "❌ La date prévue ne peut pas être antérieure à aujourd'hui.";
    header("Location: demande.php");
    exit();
}

// INSERT DEMANDE (pas de photo ici)
$stmt = $mysqli->prepare(" INSERT INTO demande(
        date_prevue, titre, adresse_depart, ville_depart,
        adresse_arrive, ville_arrive, type_logement_depart,
        type_logement_arrive, volume, nbr_demenageur, ascenseur,
        description, id_utilisateur
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param( "ssssssssdissi",
    $date,
    $titre,
    $adresse_depart,
    $ville_depart,
    $adresse_arrive,
    $ville_arrive,
    $type_logement_depart,
    $type_logement_arrive,
    $volume,
    $nbr_demenageur,
    $ascenseur,
    $description,
    $id_utilisateur
);

if (!$stmt->execute()) {
    $_SESSION['erreur'] = "❌ Erreur lors de l’enregistrement : " . $stmt->error;
    header("Location: demande.php");
    exit();
}

// ID de la demande créée
$id_demande = $stmt->insert_id;
$stmt->close();


// 🔥 UPLOAD MULTIPLE PHOTOS APRÈS INSERT
if (!empty($_FILES['photos']['name'][0])) {

   $upload_dir = __DIR__ . '/uploads/';

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $count = count($_FILES['photos']['name']);

    for ($i = 0; $i < $count; $i++) {

        if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) continue;

        $tmp = $_FILES['photos']['tmp_name'][$i];
        $name = $_FILES['photos']['name'][$i];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) continue;

        $new_name = uniqid('photo_', true) . '.' . $ext;
        $dest = $upload_dir . $new_name;

        if (move_uploaded_file($tmp, $dest)) {

            $photo_path = "uploads/" . $new_name;

            $mysqli->query("
                INSERT INTO photos_demande(id_demande, photo_path)
                VALUES ($id_demande, '$photo_path')
            ");
        }
    }
}

$_SESSION['message'] = "✅ Demande enregistrée avec succès !";
header("Location: accueil_client.php");
exit();

?>
