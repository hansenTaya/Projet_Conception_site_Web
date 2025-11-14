<?php
session_start();
$titre = "Messagerie";
include('biblio.inc.php');

if (!isset($_GET['destinataire_id'])) {
    exit("Erreur : aucun destinataire");
}

require_once("param.inc.php");
$mysqli = new mysqli($host, $login, $passwd, $dbname);
if ($mysqli->connect_error) {
    die("Problème de connexion à la base de données : " . $mysqli->connect_error);
}

$mon_id = $_SESSION['id_utilisateur'];
$destinataire_id = $_GET['destinataire_id'];

// Récupérer l'utilisateur contact
$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = $destinataire_id";
$user = $mysqli->query($sql)->fetch_assoc();
?>

<div class="container mt-4">

    <h3 class="mb-4">
        💬 Discussion avec 
        <?= htmlspecialchars($user['nom']) . " " . htmlspecialchars($user['prenom']) ?>
    </h3>

    <div class="card shadow-sm">

        <!-- 🔵 AFFICHAGE DES MESSAGES -->
        <div class="card-body" style="height: 350px; overflow-y: auto; background:#f5f5f5;">

            <?php
            // Requête pour afficher la conversation entre les deux personnes
            $sql_messages = "
                SELECT *
                FROM messages
                WHERE 
                    (client_id = $mon_id AND demenageur_id = $destinataire_id)
                    OR
                    (client_id = $destinataire_id AND demenageur_id = $mon_id)
                ORDER BY id ASC
            ";

            $result_messages = $mysqli->query($sql_messages);

            if ($result_messages && $result_messages->num_rows > 0) {

                while ($msg = $result_messages->fetch_assoc()) {

                   
                    $moi = ($msg['expediteur_id'] == $mon_id);

// Détecter automatiquement si c’est moi qui ai envoyé
                        

                        if ($moi) {
                            echo "<div class='text-end mb-3'>
                                    <span class='badge bg-primary p-2' style='font-size:1rem; border-radius:20px;'>
                                        {$msg['message']}
                                    </span><br>
                                    <small class='text-muted'>{$msg['time']}</small>
                                  </div>";
                        } else {
                            echo "<div class='text-start mb-3'>
                                    <span class='badge bg-light text-dark p-2' style='font-size:1rem; border-radius:20px;'>
                                        {$msg['message']}
                                    </span><br>
                                    <small class='text-muted'>{$msg['time']}</small>
                                  </div>";
}

                }

            } else {
                echo "
                <p class='text-center text-muted mt-5'>
                    Aucun message pour le moment.<br>
                    Commencez la conversation !
                </p>";
            }
            ?>

        </div>

        <!-- 🟢 FORMULAIRE D'ENVOI -->
        <div class="card-footer">
            <form method="POST" action="messagerie_chat_traiter.php">
                <input type="hidden" name="destinataire_id" value="<?= $destinataire_id ?>">

                <div class="input-group">
                    <input type="text" name="message" class="form-control" placeholder="Votre message..." required>
                    <button class="btn btn-primary">📤 Envoyer</button>
                </div>
            </form>
        </div>

    </div>

</div>

<?php include('footer.inc.php'); ?>
