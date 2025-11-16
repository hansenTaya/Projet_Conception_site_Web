<?php
  session_start();
  $titre = "Service de demenagement";
  include('header.inc.php');
  
  include('message.inc.php');

  require_once("param.inc.php");
  $mysqli = new mysqli($host, $login, $passwd, $dbname);

  if ($mysqli->connect_error) {
      die("Erreur DB : " . $mysqli->connect_error);
  }

  $sql = "
      SELECT d.*, u.nom, u.prenom 
      FROM demande d
      JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
      ORDER BY d.date DESC 
      LIMIT 4
  ";
  $annonces = $mysqli->query($sql);
?>
<main>
  <section class="py-5 bg-primary text-white">
    <div class="container py-5">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <p class="text-uppercase text-white-50 mb-2">Just Move It</p>
          <h1 class="display-4 fw-bold mb-3">Votre demenagement sans stress</h1>
          <p class="lead mb-4">
            Confiez-nous la planification, l'emballage et le transport de vos biens. Notre equipe
            coordonne chaque etape pour un demenagement serein.
          </p>
          <div class="d-flex flex-wrap gap-3">
            <?php
            if (!isset($_SESSION['id_utilisateur'])) {
             echo " <a class='btn btn-light btn-lg fw-semibold' href='inscription.php'>S'inscrire</a> ";
             echo  "<a class='btn btn-outline-light btn-lg fw-semibold' href='connexion.php'>Se connecter</a>  ";

            }
            ?>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="bg-white text-dark rounded-4 p-4 shadow-lg h-100">
            <div class="row text-center">
              <div class="col">
                <p class="fw-bold fs-3 mb-0">250+</p>
                <small class="text-muted text-uppercase">Demandes</small>
              </div>
              <div class="col">
                <p class="fw-bold fs-3 mb-0">80</p>
                <small class="text-muted text-uppercase">Demenageurs</small>
              </div>
              <div class="col">
                <p class="fw-bold fs-3 mb-0">98%</p>
                <small class="text-muted text-uppercase">Satisfaction</small>
              </div>
            </div>
            <hr>
            <p class="text-muted mb-1">Suivi 7j/7 et accompagnement personnalise.</p>
            <p class="text-muted mb-0">Des offres adaptees a chaque besoin.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <span class="badge bg-primary-subtle text-primary fw-semibold text-uppercase">Nos prestations</span>
        <h2 class="mt-3 fw-bold">Un accompagnement complet</h2>
        <p class="text-muted">Planification, protection et transport : tout est inclus.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 border-0 shadow">
            <div class="card-body">
              <span class="badge bg-primary text-white mb-3">1</span>
              <h4 class="fw-semibold">Planification sur mesure</h4>
              <p class="text-muted mb-0">Analyse de vos besoins et coordination detaillee pour respecter vos delais.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 border-0 shadow">
            <div class="card-body">
              <span class="badge bg-primary text-white mb-3">2</span>
              <h4 class="fw-semibold">Emballage et protection</h4>
              <p class="text-muted mb-0">Cartons renforces, protections specifiques et equipe experte pour vos objets.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 border-0 shadow">
            <div class="card-body">
              <span class="badge bg-primary text-white mb-3">3</span>
              <h4 class="fw-semibold">Transport securise</h4>
              <p class="text-muted mb-0">Flotte de camions assures, suivi en temps reel et livraison controlee.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <h2 class="fw-bold mb-4">Comment ca fonctionne ?</h2>
          <p class="text-muted mb-4">
            Quelques clics suffisent pour entrer en contact avec des demenageurs verifies et planifier votre projet.
          </p>
          <ul class="list-group list-group-flush">
            <li class="list-group-item px-0">
              <h5 class="fw-semibold mb-1">1. Publiez votre demande</h5>
              <p class="text-muted mb-0">Ajoutez les adresses, volumes et contraintes particulieres.</p>
            </li>
            <li class="list-group-item px-0">
              <h5 class="fw-semibold mb-1">2. Comparez les propositions</h5>
              <p class="text-muted mb-0">Recevez des devis clairs et choisissez la meilleure equipe.</p>
            </li>
            <li class="list-group-item px-0">
              <h5 class="fw-semibold mb-1">3. Suivez la mission</h5>
              <p class="text-muted mb-0">Restez informe en temps reel depuis votre espace client.</p>
            </li>
          </ul>
        </div>
        <div class="col-lg-6">
          <div class="card border-0 shadow h-100">
            <div class="card-body">
              <h5 class="text-uppercase text-muted mb-3">Temoignage</h5>
              <blockquote class="blockquote">
                <p class="mb-4">
                  “Service impeccable ! L'equipe a tout emballe avec soin et nous avons emmenage sans stress.”
                </p>
                <footer class="blockquote-footer">Camille, demenagement Paris -> Bordeaux</footer>
              </blockquote>
              <div class="row text-center text-muted">
                <div class="col">
                  <p class="fw-bold fs-4 mb-0">700+</p>
                  <small>Clients accompagnes</small>
                </div>
                <div class="col">
                  <p class="fw-bold fs-4 mb-0">4.9/5</p>
                  <small>Note moyenne</small>
                </div>
                <div class="col">
                  <p class="fw-bold fs-4 mb-0">24h</p>
                  <small>Delai de reponse</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <span class="badge bg-secondary text-white fw-semibold">Annonces recentes</span>
        <h2 class="mt-3 fw-bold">Demandes publiees recemment</h2>
        <p class="text-muted">Ces clients recherchent actuellement un demenageur.</p>
      </div>
      <div class="row g-4">
        <?php 
        if ($annonces && $annonces->num_rows > 0) {
          while ($a = $annonces->fetch_assoc()) { ?>
            <div class="col-md-3">
              <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                  <h5 class="fw-semibold"><?= htmlspecialchars($a['ville_depart']); ?> -> <?= htmlspecialchars($a['ville_arrive']); ?></h5>
                  <p class="text-muted small mb-1">Client : <?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']); ?></p>
                  <p class="small mb-1">Volume : <strong><?= htmlspecialchars($a['volume']); ?> m3</strong></p>
                  <p class="small mb-1">Date : <?= htmlspecialchars($a['date']); ?></p>
                  <p class="small text-muted mb-0"><?= htmlspecialchars(substr($a['description'], 0, 60)); ?>...</p>
                </div>
              </div>
            </div>
        <?php }
        } else {
          echo "<p class='text-center text-muted'>Aucune annonce pour le moment.</p>";
        }
        ?>
      </div>
    </div>
  </section>

  <section class="py-5 bg-secondary bg-opacity-10">
    <div class="container">
      <div class="text-center mb-5">
        <span class="badge bg-secondary text-white fw-semibold">Nos engagements</span>
        <h2 class="mt-3 fw-bold">Votre tranquillite avant tout</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <h4 class="fw-semibold">Equipe experte</h4>
              <p class="text-muted mb-0">Professionnels verifies et assures.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <h4 class="fw-semibold">Transparence</h4>
              <p class="text-muted mb-0">Devis clairs et suivi continu.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <h4 class="fw-semibold">Protection totale</h4>
              <p class="text-muted mb-0">Assurance incluse pour vos biens.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <h4 class="fw-semibold">Support 7j/7</h4>
              <p class="text-muted mb-0">Conseillers disponibles a chaque etape.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
  include('footer.inc.php');
?>
