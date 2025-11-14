<?php
  session_start();
  $titre = "Connexion";
  include('header.inc.php');
  include('menu.inc.php');
  include('message.inc.php');
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      <!-- Carte principale -->
      <div class="card shadow-lg border-0">
        <div class="card-body p-5">
          <!-- En-tête -->
          <div class="text-center mb-4">
            <div class="mb-3">
              <i class="fas fa-sign-in-alt text-primary" style="font-size: 3rem;"></i>
            </div>
            <h2 class="card-title fw-bold">Connexion à votre compte personnel</h2>
            <p class="text-muted">Accédez à votre espace personnel</p>
          </div>

          <!-- Formulaire -->
          <form method="POST" action="tt_connexion.php">
            <!-- Email -->
            <div class="mb-3">
              <label for="mail" class="form-label fw-semibold">
                <i class="fas fa-envelope me-2 text-primary"></i>Email
              </label>
              <input type="email" class="form-control form-control-lg" id="mail" name="mail" placeholder="votre.email@exemple.com" required>
            </div>

            <!-- Mot de passe -->
            <div class="mb-4">
              <label for="password" class="form-label fw-semibold">
                <i class="fas fa-lock me-2 text-primary"></i>Mot de passe
              </label>
              <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>

            <!-- Bouton de soumission -->
            <div class="d-grid mb-3">
              <button class="btn btn-primary btn-lg" type="submit">
                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
              </button>
            </div>

            <!-- Lien vers inscription -->
            <div class="text-center">
              <p class="text-muted mb-0">
                Vous n'avez pas de compte ? 
                <a href="inscription.php" class="text-primary fw-semibold text-decoration-none">Inscrivez-vous</a>
              </p>
            </div>
          </form>
        </div>
      </div>

      <!-- Message informatif -->
      <div class="text-center mt-4">
        <p class="text-muted small">
          <i class="fas fa-shield-alt me-1"></i>
          Vos données sont sécurisées et protégées
        </p>
      </div>
    </div>
  </div>
</div>

<?php
  include('footer.inc.php');
?>