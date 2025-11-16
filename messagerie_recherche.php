<?php

    session_start();
    require_once("param.inc.php"); // si vos paramètres BDD sont ici
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $nom_utilisateur=$_SESSION['nom'];
    $premon_utilisateur=$_SESSION['prenom'];
    $statut_utilisateur=$_SESSION['statut'];
    
    $search = htmlentities($_GET['q']);


    $mysqli = new mysqli($host, $login, $passwd, $dbname);
    // Vérification de la connexion
    if ($mysqli->connect_error) {
        die("Problème de connexion à la base de données : " . $mysqli->connect_error);
    }
    //On recherche le nom des utilisateurs pour l'affichage dans la messagerie
   
    $sql = "SELECT * FROM utilisateur 
        WHERE (nom LIKE ? OR prenom LIKE ?)
          AND id_utilisateur != ? 
          AND statut != ? AND statut != 'administrateur'";

$stmt = $mysqli->prepare($sql);
$like = "%{$search}%";
$stmt->bind_param('ssis', $like, $like, $id_utilisateur, $statut_utilisateur);
$stmt->execute();
$result = $stmt->get_result();
    // query permet d'affecter le résultat de la requête à une variable

    
    if ($result->num_rows > 0) {
    // On récupère l'utilisateur trouvé
    $row = $result->fetch_assoc();
    
    echo "<a href='messagerie_chat_modal.inc.php?destinataire_id=" . $row['id_utilisateur'] . "' 
        class='list-group-item list-group-item-action'>
        <strong>" . htmlspecialchars($row['nom']) . " " . htmlspecialchars($row['prenom']) . "</strong><br>
        <small class='text-muted'>Cliquez pour ouvrir la discussion</small>
      </a>";


} else {
    echo "<p>Aucun utilisateur trouvé.</p>";
}


