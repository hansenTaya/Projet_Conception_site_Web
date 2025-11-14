<?php
  session_start(); // Pour les messages

  require_once("param.inc.php"); // si vos paramètres BDD sont ici
 
  $mysqli = new mysqli($host, $login, $passwd, $dbname);


  $id_utilisateur = $_SESSION['id_utilisateur'];

  //$sql_client="SELECT  id_utilisateur FROM UTILISATEUR WHERE statut='client';"
  // Contenu du formulaire :
  $id_demande = (int) filter_var($_POST['id_demande'], FILTER_SANITIZE_NUMBER_INT);
  $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
  $titre = htmlentities($_POST['titre']);
  $adresse_depart =  htmlentities($_POST['adresse_depart']);
  $ville_depart = htmlentities($_POST['ville_depart']);
  $adresse_arrive =  htmlentities($_POST['adresse_arrive']);
  $ville_arrive = htmlentities($_POST['ville_arrive']);
  $type_logement_depart = htmlentities($_POST['type_logement_depart']);
  $type_logement_arrive = htmlentities($_POST['type_logement_arrive']);
  $volume = (float) filter_var($_POST['volume'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $nbr_demenageur = htmlentities($_POST['nbr_demenageur']); 
  $ascenseur = filter_var($_POST['ascenseur'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
  $description = htmlentities($_POST['description']);

  //gestion de la photo
    $photo_path = null;
 if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

    // Chemin serveur pour stocker
    $upload_dir = __DIR__ . '/../uploads/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $tmp_name = $_FILES['photo']['tmp_name'];
    $original_name = $_FILES['photo']['name'];
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extension, $allowed_extensions)) {

        $new_name = uniqid('photo_', true) . '.' . $extension;

        // emplacement serveur
        $destination = $upload_dir . $new_name;

        if (move_uploaded_file($tmp_name, $destination)) {

            // ce qu’on stocke en BDD = URL affichable
            $photo_path = 'uploads/' . $new_name;

        } else {
            $_SESSION['erreur'] = "Erreur lors du transfert de la photo.";
        }

    } else {
        $_SESSION['erreur'] = "Format non autorisé.";
    }

} else {
    $_SESSION['erreur'] = "Aucune photo envoyée.";
}


  // Option pour bcrypt (voir le lien du cours vers le site de PHP) :
  $options = [
        'cost' => 10,
  ];
  // On crypte le mot de passe
  //$password_crypt = password_hash($password, PASSWORD_BCRYPT, $options);

  // Connexion :
  require_once("param.inc.php");
  $mysqli = new mysqli($host, $login, $passwd, $dbname);
  if ($mysqli->connect_error) {
    $_SESSION['erreur']="Problème de connexion à la base de données ! &#128557;";
      // die('Erreur de connexion (' . $mysqli->connect_errno . ') '
              // . $mysqli->connect_error);
  }

 
  // Modifier la requête en fonction de la table et/ou des attributs :
 if ($stmt = $mysqli->prepare("INSERT INTO demande( id_demande, date, titre, adresse_depart, ville_depart, adresse_arrive, ville_arrive, type_logement_depart, type_logement_arrive, volume, photo_path, nbr_demenageur, ascenseur, description, id_utilisateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);") ) {
    $stmt->bind_param("issssssssdssssi", $id_demande, $date, $titre, $adresse_depart, $ville_depart, $adresse_arrive, $ville_arrive, $type_logement_depart, $type_logement_arrive, $volume, $photo_path, $nbr_demenageur, $ascenseur, $description, $id_utilisateur);

    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Enregistrement réussi";
    } else {
        $_SESSION['erreur'] = "❌ Impossible d'enregistrer : " . $stmt->error;
    }

   
} else {
    $_SESSION['erreur'] = "Erreur dans la préparation de la requête.";
}


 header('Location: accueil_client.php');

?>