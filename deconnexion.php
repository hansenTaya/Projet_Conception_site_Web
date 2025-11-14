<?php
session_start();
$titre = "Déconnexion";
include('header.inc.php');
include('menu.inc.php');
include('message.inc.php');

// Détruire la session
session_destroy();
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-lg border-0 text-center">
        <div class="card-body p-5">
          <!-- Icône de déconnexion -->
          <div class="mb-4">
            <i class="fas fa-sign-out-alt text-danger" style="font-size: 4rem;"></i>
          </div>
          
          <!-- Titre -->
          <h2 class="card-title mb-3">Vous êtes déconnecté(e)</h2>
          
          <!-- Message -->
          <p class="card-text text-muted mb-4">
            Vous avez été déconnecté(e) avec succès de votre compte.<br>
            Merci d'avoir utilisé nos services !
          </p>
          
          <!-- Boutons d'action -->
          <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <a href="index.php" class="btn btn-primary btn-lg me-md-2">
              <i class="fas fa-home me-2"></i>Retour à l'accueil
            </a>
            <a href="connexion.php" class="btn btn-outline-primary btn-lg">
              <i class="fas fa-sign-in-alt me-2"></i>Se reconnecter
            </a>
          </div>
        </div>
      </div>
      
      <!-- Message informatif -->
      <div class="alert alert-info mt-4 text-center" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <small>Pour votre sécurité, votre session a été fermée. Vous pouvez vous reconnecter à tout moment.</small>
      </div>
    </div>
  </div>
</div>

<?php
include('footer.inc.php');
?>