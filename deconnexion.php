<?php
session_start();
$titre = "Déconnexion";
// Détruire la session
session_destroy();
header('Location: index.php');
?>



<?php
include('footer.inc.php');
?>