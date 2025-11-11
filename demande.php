<?php
  $titre = "Demande de Déménagement";
  include('header.inc.php');
  include('menu_client.inc.php');
?>

<div class="d-flex flex-column flex-md-row">

  
  <!-- Contenu principal (formulaire) -->
  <div class="flex-grow-1 p-4" style="min-width:0;">
    <h1 class="mb-4">Demande de Déménagement</h1>

    <form method="POST" action="tt_demande.php" enctype="multipart/form-data">
      <!-- Informations principales -->
      <div class="row g-3">
          <div class="col-12 col-md-6">
              <label for="titre" class="form-label">Titre de la demande</label>
              <input type="text" class="form-control" id="titre" name="titre" placeholder="Ex : Déménagement appartement vers maison" required>
          </div>
          <div class="col-12 col-md-6">
              <label for="date" class="form-label">Date prévue</label>
              <input type="date" class="form-control" id="date" name="date" required>
          </div>
      </div>

      <!-- Adresse de départ -->
      <h4 class="mt-4">Adresse de départ</h4>
      <div class="row g-3">
          <div class="col-12 col-md-6">
              <label for="adresse_depart" class="form-label">Adresse</label>
              <input type="text" class="form-control" id="adresse_depart" name="adresse_depart" placeholder="Ex : 10 rue de Paris" required>
          </div>
          <div class="col-12 col-md-6">
              <label for="ville_depart" class="form-label">Ville</label>
              <input type="text" class="form-control" id="ville_depart" name="ville_depart" placeholder="Ville de départ" required>
          </div>
          <div class="col-12 col-md-6">
              <label for="type_logement_depart" class="form-label">Type de logement</label>
              <input type="text" class="form-control" id="type_logement_depart" name="type_logement_depart" placeholder="Ex : Appartement T3" required>
          </div>
      </div>

      <!-- Adresse d'arrivée -->
      <h4 class="mt-4">Adresse d'arrivée</h4>
      <div class="row g-3">
          <div class="col-12 col-md-6">
              <label for="adresse_arrive" class="form-label">Adresse</label>
              <input type="text" class="form-control" id="adresse_arrive" name="adresse_arrive" placeholder="Ex : 15 avenue Victor Hugo" required>
          </div>
          <div class="col-12 col-md-6">
              <label for="ville_arrive" class="form-label">Ville</label>
              <input type="text" class="form-control" id="ville_arrive" name="ville_arrive" placeholder="Ville d'arrivée" required>
          </div>
          <div class="col-12 col-md-6">
              <label for="type_logement_arrive" class="form-label">Type de logement</label>
              <input type="text" class="form-control" id="type_logement_arrive" name="type_logement_arrive" placeholder="Ex : Maison individuelle" required>
          </div>
      </div>

      <!-- Informations techniques -->
      <h4 class="mt-4">Détails du déménagement</h4>
      <div class="row g-3">
          <div class="col-12 col-md-4">
              <label for="volume" class="form-label">Taille du Logement </label>
              <input type="number" step="0.1" class="form-control" id="volume" name="volume" placeholder="Ex : 25" required>
          </div>
          <div class="col-12 col-md-4">
              <label for="nbr_demenageur" class="form-label">Nombre de déménageurs souhaités</label>
              <input type="number" class="form-control" id="nbr_demenageur" name="nbr_demenageur" min="1" max="10" required>
          </div>
          <div class="col-12 col-md-4">
              <label for="ascenseur" class="form-label">Ascenseur disponible ?</label>
              <select class="form-select" id="ascenseur" name="ascenseur" required>
                  <option value="1">Oui</option>
                  <option value="0">Non</option>
              </select>
          </div>
      </div>

      <!-- Photo -->
        <div class="row g-3 mt-3">
            <div class="col-12">
                <label for="photo" class="form-label">Photo (optionnelle)</label>
                <input type="file" class="form-control" id="photo" name="photo">
            </div>
        </div>
      <!-- Description -->
      <div class="row g-3">
          <div class="col-12">
              <label for="description" class="form-label">Description / Informations complémentaires</label>
              <textarea class="form-control w-100" id="description" name="description" rows="4" placeholder="Détails sur le déménagement (étage, stationnement, objets fragiles...)"></textarea>
          </div>
      </div>

      <!-- Bouton -->
      <div class="row g-3 mt-3">
          <div class="col-12">
              <button class="btn btn-outline-primary w-100 w-md-auto" type="submit">Envoyer la demande</button>
          </div>   
      </div>

    </form>
  </div>

</div>

<?php
  include('footer.inc.php');
?>
