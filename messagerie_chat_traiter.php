<?php
  /**
   * messagerie_chat_traiter.php
   * Traite la soumission des messages de chat
   
   */

  session_start();
  require_once("param.inc.php");
  date_default_timezone_set('Europe/Paris');

  // Vérifier que l'utilisateur est connecté
  if (!isset($_SESSION['id_utilisateur'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit;
  }

  // Récupérer les données du formulaire
  $message = isset($_POST['message']) ? trim($_POST['message']) : '';
  $destinataire_id = isset($_POST['destinataire_id']) ? (int)$_POST['destinataire_id'] : 0;
  $id_utilisateur = (int)$_SESSION['id_utilisateur'];
  $expediteur_id = $_SESSION['id_utilisateur'];

  // Validation basique
  if (empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Le message ne peut pas être vide']);
    exit;
  }

  if ($destinataire_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Destinataire invalide']);
    exit;
  }

  // Connexion à la BDD
  $mysqli = new mysqli($host, $login, $passwd, $dbname);
  if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la BDD']);
    exit;
  }

  // Échapper le message pour éviter les injections SQL
  $message_secure = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

  // Adapter le nom et les colonnes selon votre schéma
  $sql = "INSERT INTO messages (client_id, demenageur_id, expediteur_id,message, time) 
          VALUES (?, ?, ?,?,CURRENT_TIMESTAMP())";

  $stmt = $mysqli->prepare($sql);
  if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur de préparation de la requête: ' . $mysqli->error]);
    exit;
  }

  $stmt->bind_param('iiss', $id_utilisateur, $destinataire_id, $expediteur_id, $message_secure);

  if ($stmt->execute()) {

    header("Location: messagerie_chat_modal.inc.php?destinataire_id=" . $destinataire_id);
    exit();

} else {

    echo "Erreur lors de l'envoi du message.";
}


  $stmt->close();
  $mysqli->close();
  exit;
?>
