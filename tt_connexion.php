<?php
  session_start(); // Pour les massages

  // Contenu du formulaire :
  $mail =  htmlentities($_POST['mail']);
  $password = htmlentities($_POST['password']);
 
 
  // Option pour bcrypt (voir le lien du cours vers le site de PHP) :
  $options = [
        'cost' => 10,
  ];
  // On crypte le mot de passe
  //$password_crypt = password_hash($mdp, PASSWORD_BCRYPT, $options);

  // Connexion :
  require_once("param.inc.php");
  $mysqli = new mysqli($host, $login, $passwd, $dbname);
  if ($mysqli->connect_error) {
    $_SESSION['erreur']="Problème de connexion à la base de données ! &#128557;";
      // die('Erreur de connexion (' . $mysqli->connect_errno . ') '
              // . $mysqli->connect_error);
  }

        $sql = "SELECT * FROM utilisateur WHERE mail = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s",$mail);
    $stmt->execute();

$result = $stmt->get_result(); // <-- ici

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); //  Correct
    echo "Utilisateur trouvé : " . $user['Nom'];
    if ($password === $user['password']) 
    {
        // Stocker les infos utilisateur dans la session
        $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['statut'] = $user['statut'];

        // Redirection selon le statut
        switch ($user['statut']) {
            case 'admin':
                header('Location: page_admin.php');
                break;
            case 'client':
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['statut'] = $user['statut']; // client ou demenageur
                $_SESSION['nom'] = $user['nom'];
                header('Location: ../Projet_conception_site_web/client/page_client.php');

                break;
            case 'demenageur':
                 $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['statut'] = $user['statut']; // client ou demenageur
                $_SESSION['nom'] = $user['nom'];
                header('Location: ../Projet_conception_site_web/demenageur/page_demenageur.php');
                break;
               
            default:
                $_SESSION ['erreur']="Statut inconnu, veuillez contacter un administrateur.";
                break;
        }
        exit;
    } 
    else 
    {
        $_SESSION ['erreur']= "Email ou mot de passe incorrect.";
    }

} else {
    echo "Aucun utilisateur trouvé.";
}


  
 header('Location: index.php'); 

?>