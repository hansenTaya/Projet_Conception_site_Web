<?php
session_start();
session_destroy();  // détruit la session
header("Location: accueil.php"); // redirige vers la page d'accueil
exit();
?>