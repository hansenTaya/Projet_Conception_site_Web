<?php
  session_start();
  $titre = "Inscription";
  include('header.inc.php');
  include('menu.inc.php');
  include('message.inc.php');
?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">
      <!-- Carte principale -->
      <div class="card shadow-lg border-0">
        <div class="card-body p-5">
          <!-- En-tête -->
          <div class="text-center mb-4">
            <div class="mb-3">
              <i class="fas fa-user-plus text-primary" style="font-size: 3rem;"></i>
            </div>
            <h2 class="card-title fw-bold">Création d'un compte personnel</h2>
            <p class="text-muted">Rejoignez-nous et profitez de nos services de déménagement</p>
          </div>

          <!-- Formulaire -->
          <form method="POST" action="tt_inscription.php">
            <!-- Nom et Prénom -->
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="nom" class="form-label fw-semibold">
                  <i class="fas fa-user me-2 text-primary"></i>Nom
                </label>
                <input type="text" class="form-control form-control-lg" id="nom" name="nom" placeholder="Votre nom" required>
              </div>
              <div class="col-md-6">
                <label for="prenom" class="form-label fw-semibold">
                  <i class="fas fa-user me-2 text-primary"></i>Prénom
                </label>
                <input type="text" class="form-control form-control-lg" id="prenom" name="prenom" placeholder="Votre prénom" required>
              </div>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="mail" class="form-label fw-semibold">
                <i class="fas fa-envelope me-2 text-primary"></i>Email
              </label>
              <input type="email" class="form-control form-control-lg" id="mail" name="mail" placeholder="votre.email@exemple.com" required>
            </div>

            <!-- Mot de passe -->
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">
                <i class="fas fa-lock me-2 text-primary"></i>Mot de passe
              </label>
              <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>

            <!-- Téléphone et Statut -->
            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="telephone" class="form-label fw-semibold">
                  <i class="fas fa-phone me-2 text-primary"></i>Téléphone
                </label>
                <input type="tel" class="form-control form-control-lg" id="telephone" name="telephone" placeholder="06 12 34 56 78" required>
              </div>
              <div class="col-md-6">
                <label for="statut" class="form-label fw-semibold">
                  <i class="fas fa-user-tag me-2 text-primary"></i>Statut
                </label>
                <select class="form-select form-select-lg" id="statut" name="statut" required>
                  <option value="" selected disabled>Choisissez votre statut</option>
                  <option value="client">
                    <i class="fas fa-shopping-cart"></i> Client
                  </option>
                  <option value="demenageur">
                    <i class="fas fa-truck"></i> Déménageur
                  </option>
                </select>
              </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="d-grid">
              <button class="btn btn-primary btn-lg" type="submit">
                <i class="fas fa-user-plus me-2"></i>Créer mon compte
              </button>
            </div>

            <!-- Lien vers connexion -->
            <div class="text-center mt-4">
              <p class="text-muted mb-0">
                Vous avez déjà un compte ? 
                <a href="connexion.php" class="text-primary fw-semibold text-decoration-none">Connectez-vous</a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  include('footer.inc.php');
?>