<?php
  session_start();
  if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['erreur'] = "Veuillez vous connecter.";
    header("Location: connexion.php");
    exit(); // 🔥 OBLIGATOIRE
}
  $titre = "Demande de Déménagement";
  include('header.inc.php');
  include('menu_client.inc.php');
?>

  <!-- Contenu principal (formulaire) -->
  <div class="max-w-1200 mx-auto">
    <div class="card border-0 shadow-lg mb-4">
      <div class="card-header bg-primary text-white py-4">
        <h1 class="mb-0 fw-bold">🧳 Nouvelle Demande de Déménagement</h1>
        <p class="mb-0 mt-2 text-white-50">Remplissez les informations ci-dessous pour créer votre demande</p>
      </div>
      <div class="card-body p-4 p-md-5">
        <form method="POST" action="tt_demande.php" enctype="multipart/form-data">
          <!-- Informations principales -->
          <div class="mb-5">
            <h5 class="fw-bold text-primary mb-4 d-flex align-items-center gap-2">
              <span class="badge bg-primary rounded-circle p-2">1</span>
              Informations principales
            </h5>
            <div class="row g-4">
              <div class="col-12 col-md-6">
                <label for="titre" class="form-label fw-semibold">Titre de la demande</label>
                <input type="text" class="form-control form-control-lg" id="titre" name="titre" placeholder="Ex : Déménagement appartement vers maison" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="date" class="form-label fw-semibold">Date prévue</label>
                <input type="date" class="form-control form-control-lg" id="date" name="date" min="<?= date('Y-m-d'); ?>" required>
              </div>
            </div>
          </div>

          <!-- Adresse de départ -->
          <div class="mb-5">
            <h5 class="fw-bold text-primary mb-4 d-flex align-items-center gap-2">
              <span class="badge bg-primary rounded-circle p-2">2</span>
              Adresse de départ
            </h5>
            <div class="card bg-light border-0 p-4">
              <div class="row g-4">
                <div class="col-12 col-md-6">
                  <label for="adresse_depart" class="form-label fw-semibold">Adresse complète</label>
                  <input type="text" class="form-control" id="adresse_depart" name="adresse_depart" placeholder="Ex : 10 rue de Paris" required>
                </div>
                <div class="col-12 col-md-6">
                  <label for="ville_depart" class="form-label fw-semibold">Ville</label>
                  <input type="text" class="form-control" id="ville_depart" name="ville_depart" placeholder="Ville de départ" required>
                </div>
                <div class="col-12 col-md-6">
                  <label for="type_logement_depart" class="form-label fw-semibold">Type de logement</label>
                  <input type="text" class="form-control" id="type_logement_depart" name="type_logement_depart" placeholder="Ex : Appartement T3" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Adresse d'arrivée -->
          <div class="mb-5">
            <h5 class="fw-bold text-primary mb-4 d-flex align-items-center gap-2">
              <span class="badge bg-primary rounded-circle p-2">3</span>
              Adresse d'arrivée
            </h5>
            <div class="card bg-light border-0 p-4">
              <div class="row g-4">
                <div class="col-12 col-md-6">
                  <label for="adresse_arrive" class="form-label fw-semibold">Adresse complète</label>
                  <input type="text" class="form-control" id="adresse_arrive" name="adresse_arrive" placeholder="Ex : 15 avenue Victor Hugo" required>
                </div>
                <div class="col-12 col-md-6">
                  <label for="ville_arrive" class="form-label fw-semibold">Ville</label>
                  <input type="text" class="form-control" id="ville_arrive" name="ville_arrive" placeholder="Ville d'arrivée" required>
                </div>
                <div class="col-12 col-md-6">
                  <label for="type_logement_arrive" class="form-label fw-semibold">Type de logement</label>
                  <input type="text" class="form-control" id="type_logement_arrive" name="type_logement_arrive" placeholder="Ex : Maison individuelle" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Informations techniques -->
          <div class="mb-5">
            <h5 class="fw-bold text-primary mb-4 d-flex align-items-center gap-2">
              <span class="badge bg-primary rounded-circle p-2">4</span>
              Détails du déménagement
            </h5>
            <div class="row g-4">
              <div class="col-12 col-md-4">
                <label for="volume" class="form-label fw-semibold">Volume (m³)</label>
                <input type="number" step="0.1" class="form-control form-control-lg" id="volume" name="volume" placeholder="Ex : 25" required>
                <small class="text-muted">Taille approximative du logement</small>
              </div>
              <div class="col-12 col-md-4">
                <label for="nbr_demenageur" class="form-label fw-semibold">Nombre de déménageurs</label>
                <input type="number" class="form-control form-control-lg" id="nbr_demenageur" name="nbr_demenageur" min="1" max="10" required>
                <small class="text-muted">Personnes souhaitées</small>
              </div>
              <div class="col-12 col-md-4">
                <label for="ascenseur" class="form-label fw-semibold">Ascenseur disponible ?</label>
                <select class="form-select form-select-lg" id="ascenseur" name="ascenseur" required>
                  <option value="1">Oui</option>
                  <option value="0">Non</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Photo -->
          <div class="mb-5">
            <h5 class="fw-bold text-primary mb-4 d-flex align-items-center gap-2">
              <span class="badge bg-primary rounded-circle p-2">5</span>
              Photos (optionnel)
            </h5>
            <div class="card bg-light border-0 p-4">
              <label for="photo" class="form-label fw-semibold">Ajouter des photos</label>
              <input type="file" class="form-control" id="photo" name="photos[]" multiple accept="image/*">
              <small class="text-muted">Vous pouvez sélectionner plusieurs photos</small>
            </div>
          </div>

          <!-- Description -->
          <div class="mb-5">
            <h5 class="fw-bold text-primary mb-4 d-flex align-items-center gap-2">
              <span class="badge bg-primary rounded-circle p-2">6</span>
              Description complémentaire
            </h5>
            <div class="mb-4">
              <label for="description" class="form-label fw-semibold">Informations supplémentaires</label>
              <textarea class="form-control" id="description" name="description" rows="5" placeholder="Détails sur le déménagement (étage, stationnement, objets fragiles, contraintes particulières...)"></textarea>
            </div>
          </div>

          <!-- Bouton -->
          <div class="d-flex justify-content-end gap-3 pt-4 border-top">
            <a href="index.php" class="btn btn-outline-secondary btn-lg px-5">Annuler</a>
            <button class="btn btn-primary btn-lg px-5 shadow-sm" type="submit">
              <span class="me-2">📤</span>Envoyer la demande
            </button>
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
