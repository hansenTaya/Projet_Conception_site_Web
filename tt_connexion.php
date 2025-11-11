<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mail = htmlentities($_POST['mail']);
$password = htmlentities($_POST['password']);

require_once("param.inc.php");
$mysqli = new mysqli($host, $login, $passwd, $dbname);

if ($mysqli->connect_error) {
    $_SESSION['erreur'] = "Problème de connexion à la base de données !";
    header('Location: connexion.php');
    exit();
}

// Préparation de la requête
$sql = "SELECT * FROM utilisateur WHERE mail = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $mail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // ⚠️ Pour un vrai projet, il faut utiliser password_verify(), mais tu compares en clair ici :
    if ($password === $user['password']) {
        // Stocker les infos utilisateur dans la session
         
        $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['statut'] = $user['statut'];

        // Redirection selon le statut
        switch ($user['statut']) {
            case 'admin':
                header('Location: page_admin.php');
                break;
            case 'client':
                header('Location: menu_client.inc.php');
                break;
            case 'demenageur':
                header('Location: page_demenageur.php');
                break;
            default:
                $_SESSION['erreur'] = "Statut inconnu, contactez un administrateur.";
                header('Location: connexion.php');
                break;
        }
        exit();
    } else {
        $_SESSION['erreur'] = "Email ou mot de passe incorrect.";
        header('Location: connexion.php');
        exit();
    }
} else {
    $_SESSION['erreur'] = "Aucun utilisateur trouvé.";
    header('Location: connexion.php');
    exit();
}
?>
