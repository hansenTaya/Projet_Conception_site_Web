<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();

}
date_default_timezone_set('Europe/Paris');


?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $titre ?> </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    
  </head>
  <body class="bg-light">
    <header>
      <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 mb-4">
        <div class="container">
          <a class="navbar-brand fw-bold text-primary" href="index.php">Just Move It</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="visual.php">Visualiser demenagement</a>
              </li>
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-2">
              <li class="nav-item">
                <a class="btn btn-outline-primary" href="inscription.php">Inscription</a>
              </li>
              <li class="nav-item">
                <a class="btn btn-primary text-white" href="connexion.php">Connexion</a>
              </li>
              <?php if (!empty($_SESSION['id_utilisateur'])): ?>
              <li class="nav-item">
                <a class="btn btn-outline-danger" href="deconnexion.php">Se deconnecter</a>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </nav>
    </header>
