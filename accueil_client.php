<?php
session_start();
$titre = "Espace Client";
if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['erreur'] = "Veuillez vous connecter.";
    header("Location: connexion.php");
    exit(); // 🔥 OBLIGATOIRE
}
include('header.inc.php');
include('menu_client.inc.php');
?>

  <!-- Contenu principal -->
  </div>
</div>

<?php
include('footer.inc.php');
?>

