<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$titre = "Espace administrateur";
require_once("param.inc.php");
$mysqli= new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {

    die("Problème de connexion à la base de données : " . $mysqli->connect_error);
}
if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['erreur'] = "Veuillez vous connecter.";
    header("Location: connexion.php");
    exit(); // 🔥 OBLIGATOIRE
}


include('header.inc.php');

?>

<div class="container my-4">
  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Gestion des utilisateurs</h5>
          <p class="text-muted mb-3">Supprimer un client ou un demenageur en un clic.</p>

          <ul class="nav nav-pills mb-3" id="user-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="client-tab" data-bs-toggle="pill" data-bs-target="#clients" type="button" role="tab">Clients</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="demenageur-tab" data-bs-toggle="pill" data-bs-target="#demenageurs" type="button" role="tab">Demenageurs</button>
            </li>
          </ul>

          <div class="tab-content" id="user-tabContent">
            <div class="tab-pane fade show active" id="clients" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Nom</th>
                      <th>Email</th>
                      <th>Telephone</th>
                      <th class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <?php
                      $sql="SELECT * FROM utilisateur WHERE statut='client'";
                      $result=$mysqli->query($sql);
                      while($row=$result->fetch_assoc()){
                        echo "<tr>
                        <td>".htmlspecialchars($row['nom'])." ".htmlspecialchars($row['prenom'])."</td>
                        <td>".htmlspecialchars($row['mail'])."</td>
                        <td>".htmlspecialchars($row['telephone'])."</td>
                        <td class='text-end'>
                          <form action='tt_supprimer_client.php' method='post' class='d-inline'>
                            <input type='hidden' name='id_client' value='".htmlspecialchars($row['id_utilisateur'])."' />
                            <button type='submit' class='btn btn-sm btn-outline-danger'>Supprimer</button>
                          </form>
                        </td>
                      </tr>";

                      }
                      ?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="tab-pane fade" id="demenageurs" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Nom</th>
                      <th>Entreprise</th>
                      <th>Telephone</th>
                      <th class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                      <?php
                      $sql="SELECT * FROM utilisateur WHERE statut='demenageur'";
                      $result=$mysqli->query($sql);
                      while($row=$result->fetch_assoc()){
                        
                        echo "<tr>
                        <td>".htmlspecialchars($row['nom'])." ".htmlspecialchars($row['prenom'])."</td>
                        <td>".htmlspecialchars($row['mail'])."</td>
                        <td>".htmlspecialchars($row['telephone'])."</td>
                        <td class='text-end'>
                          <form action='tt_supprimer_demenageur.php' method='post' class='d-inline'>
                            <input type='hidden' name='id_demenageur' value='".htmlspecialchars($row['id_utilisateur'])."' />
                            <button type='submit' class='btn btn-sm btn-outline-danger'>Supprimer</button>
                          </form>
                        </td>
                      </tr>";

                      }
                      ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
  <div class="card shadow-sm h-100">
    <div class="card-body">
      <h5 class="card-title">Demandes reçues</h5>
      <p class="text-muted mb-3">Consultez l'état des demandes de déménagement.</p>

      <div class="list-group list-group-flush">

        <?php
        $sql = "
          SELECT d.*, u.nom, u.prenom
          FROM demande d
          JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
          ORDER BY d.date_prevue DESC LIMIT 5
        ";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
          while ($d = $result->fetch_assoc()) {

            // Badge simple : accepte / refuse / en attente
            $etat = "En attente";
            $badge = "bg-warning text-dark";

            if (!empty($d['statut'])) {
              if ($d['statut'] === "acceptee") { $etat = "Acceptee"; $badge = "bg-success"; }
              if ($d['statut'] === "refusee") { $etat = "Refusee"; $badge = "bg-danger"; }
            }
            ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <strong><?= htmlspecialchars($d['ville_depart']); ?> -> <?= htmlspecialchars($d['ville_arrive']); ?></strong>
                <div class="small text-muted">Client : <?= htmlspecialchars($d['nom'] . ' ' . $d['prenom']); ?> - Volume <?= htmlspecialchars($d['volume']); ?> m3</div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <form action="tt_supprimer_demande.php" method="post" class="d-inline">
                  <input type="hidden" name="id_demande" value="<?= htmlspecialchars($d['id_demande']); ?>" />
                  <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                </form>
                <span class="badge <?= $badge ?> rounded-pill"><?= $etat ?></span>
              </div>
            </div>
            <?php
          }
        } else {
          echo "<p class='text-muted ps-3'>Aucune demande.</p>";
        }
        ?>

      </div>
    </div>
  </div>
</div>


 <?php
// Clients supprimés cette semaine
$clients = $mysqli->query("SELECT COUNT(*) AS total FROM utilisateur WHERE statut='client'
")->fetch_assoc()['total'];
// Déménageurs supprimés cette semaine
$demenageurs = $mysqli->query(" SELECT COUNT(*) AS total FROM utilisateur WHERE statut='demenageur'
")->fetch_assoc()['total'];

// Demandes traitées (acceptées ou refusées)
$demandes_traitees = $mysqli->query("SELECT COUNT(*) AS total 
  FROM demande 
  WHERE statut IN ('acceptee', 'refusee')
")->fetch_assoc()['total'];
?>

<div class="card shadow-sm mt-4">
  <div class="card-body">
    <h5 class="card-title mb-3">Dernières activités</h5>
    <div class="row g-3">

      <div class="col-md-4">
        <div class="border rounded p-3 h-100">
          <p class="text-uppercase text-muted small mb-1">Clients</p>
          <h3 class="mb-0"><?= $clients ?></h3>
          <small class="text-muted">Cette semaine</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="border rounded p-3 h-100">
          <p class="text-uppercase text-muted small mb-1">Déménageurs </p>
          <h3 class="mb-0"><?= $demenageurs ?></h3>
          <small class="text-muted">Cette semaine</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="border rounded p-3 h-100">
          <p class="text-uppercase text-muted small mb-1">Demandes traitées</p>
          <h3 class="mb-0"><?= $demandes_traitees ?></h3>
          <small class="text-muted">Acceptées ou refusées</small>
        </div>
      </div>

    </div>
  </div>
</div>


<?php
include('footer.inc.php');
?>

