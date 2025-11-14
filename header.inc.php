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

    




    
    <nav class="mb-2 navbar navbar-expand-md bg-dark border-bottom border-body" data-bs-theme="dark">
     <div class="container-fluid">

     
    <a class="navbar-brand" href="index.php">Just move it :)</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="visual.php">Visualiser démenagement</a>
        </li>

      </ul>

      <!-- Partie droite -->
      <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="inscription.php">Inscription</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="connexion.php">Connexion</a>
          </li>
      </ul>
    </div>
  </div>
</nav>