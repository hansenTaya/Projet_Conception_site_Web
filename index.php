<?php
  session_start();
  $titre = "Service de déménagement";
  include('header.inc.php');
  include('menu.inc.php');
  include('message.inc.php');
?>
  <!-- Section Hero -->
  <section class="hero bg-primary text-white py-5 mb-4">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8 mx-auto text-center">
          <h1 class="display-4 fw-bold mb-4">Votre déménagem Just Move It</h1>
          <p class="lead mb-4">Confiez-nous la planification, l'emballage et le transport de vos biens. Notre équipe expérimentée s'occupe de tout pour que vous profitiez d'un déménagement serein.</p>
          <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a class="btn btn-light btn-lg" href="inscription.php">
              <i class="fas fa-user-plus me-2"></i>S'inscrire
            </a>
            <a class="btn btn-outline-light btn-lg" href="connexion.php">
              <i class="fas fa-sign-in-alt me-2"></i>Se connecter
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section Services --> 
  <section class="services py-5">
    <div class="container">
      <h2 class="text-center mb-5">Nos prestations</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h3 class="card-title">📋 Planification sur mesure</h3>
              <p class="card-text">Un conseiller dédié analyse vos besoins, prépare un planning détaillé et coordonne chaque étape pour respecter vos délais.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h3 class="card-title">📦 Emballage et protection</h3>
              <p class="card-text">Nous fournissons cartons, protections spécifiques et assurons l'emballage sécurisé de vos objets fragiles, meubles et équipements spéciaux.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h3 class="card-title">🚚 Transport sécurisé</h3>
              <p class="card-text">Notre flotte de véhicules capitonnés et assurés garantit un transport sécurisé, que ce soit pour un déménagement local ou longue distance.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section Engagements -->
  <section class="engagements bg-light py-5">
    <div class="container">
      <h2 class="text-center mb-5">Nos engagements</h2>
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <ul class="list-group">
            <li class="list-group-item">✅ Équipe professionnelle formée en continu.</li>
            <li class="list-group-item">✅ Respect des délais et transparence sur les coûts.</li>
            <li class="list-group-item">✅ Assurance complète pour vos biens.</li>
            <li class="list-group-item">✅ Service client réactif 7j/7.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
<?php
  include('footer.inc.php');
?>