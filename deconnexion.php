<?php
session_start();
$titre = "Déconnexion";
// Détruire la session
session_destroy();
session_start(); // on redémarre pour afficher le message
$_SESSION['message'] = "Vous êtes déconnecté(e).";
header('Location: index.php');
?>



<?php
include('footer.inc.php');
?>