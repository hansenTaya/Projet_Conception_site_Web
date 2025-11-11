<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $titre; ?></title>
    <a href="deconnexion.php" class="btn btn-outline-danger mt-3">🚪 Se déconnecter</a>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">  
  </head>
  <body>
  