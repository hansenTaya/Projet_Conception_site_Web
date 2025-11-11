<?php
  session_start(); // Pour les massages

  // Contenu du formulaire :
  $Id_utilisateur = (int) filter_var($_POST['Id_utilisateur'], FILTER_SANITIZE_NUMBER_INT);
  $nom =  htmlentities($_POST['nom']);
  $prenom = htmlentities($_POST['prenom']);
  $mail =  htmlentities($_POST['mail']);
  $password = htmlentities($_POST['password']);
  $telephone = (int) filter_var($_POST['telephone'], FILTER_SANITIZE_NUMBER_INT);
  $statut = htmlentities($_POST['statut']); //le statut si l'utilisateur est un admin ou client ou demenageur
 
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

  // À faire : vérifier si l'email existe déjà !
  $sql_check = "SELECT * FROM utilisateur WHERE mail = ?";
  $stmt = $mysqli->prepare($sql_check);
  $stmt->bind_param("s",$mail);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0)
  {
    $_SESSION['message'] = "utilisateur exixte deja";
  } 
  else 
  {
    $_SESSION['message'] = "Email disponible, vous pouvez l’utiliser.";
  }

  // Modifier la requête en fonction de la table et/ou des attributs :
  if ($stmt = $mysqli->prepare("INSERT INTO utilisateur(id_utilisateur, nom, prenom, mail, password, telephone, statut) VALUES (?, ?, ?, ?, ?, ?, ?)")) {

    $stmt->bind_param("issssis",$id_utilisateur, $nom, $prenom, $mail, $password, $telephone, $statut);
    // Le message est mis dans la session, il est préférable de séparer message normal et message d'erreur.
    if($stmt->execute()) {
        // Requête exécutée correctement 
        $_SESSION['message'] = "Enregistrement réussi";

    } else {
        // Il y a eu une erreur
        $_SESSION['erreur'] =  "Impossible d'enregistrer";
    }
  }

 header('Location: index.php');

?>