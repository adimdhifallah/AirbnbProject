<!DOCTYPE html>
<html>
<head>
    <title>Détails et réservation du logement</title>
</head>
<body>
    <h1>Détails et réservation du logement</h1>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Airbnb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }

    session_start();

    if (!isset($_SESSION['user'])) {
        die("Veuillez vous connecter pour effectuer une réservation ou ajouter un avis.");
    }

    if (isset($_POST['reserver'])) {
        $dateDépart = $_POST['Date_depart'];
        $dateFin = $_POST['Date_arrivée'];
        $nombrePersonnes = $_POST['Nombre_personnes'];
        $utilisateurConnecte = $_SESSION['user']['id'];
        $logementId = $_GET['id'];

        // Vérification des disponibilités
        $dispoSql = "SELECT * FROM Hébergements WHERE ID = '$logementId' AND Date_depart <= '$dateDépart' AND Date_arrivée >= '$dateFin'";
        $dispoResult = $conn->query($dispoSql);

        if ($dispoResult->num_rows > 0) {
            $sql = "INSERT INTO Réservations (ID_utilisateur, Date_depart, Date_arrivée, Nombre_personnes, ID_hébergement)
                VALUES ('$utilisateurConnecte', '$dateDépart', '$dateFin', '$nombrePersonnes', '$logementId')";

            if ($conn->query($sql) === TRUE) {
                ?>
                <p>Réservation effectuée avec succès.</p>
                <?php
            } else {
                ?>
                <p>Erreur lors de la réservation : <?php echo $conn->error; ?></p>
                <?php
            }
        } else {
            ?>
            <p>Le logement n'est pas disponible aux dates sélectionnées.</p>
            <?php
        }
    }

    if (isset($_POST['commenter'])) {
        $commentaire = $_POST['commentaire'];
        $note = $_POST['note'];
        $logementId = $_GET['id'];
        $utilisateurConnecte = $_SESSION['user']['id'];

        // Vérification de la réservation
        $reservationSql = "SELECT * FROM Réservations WHERE ID_utilisateur = '$utilisateurConnecte' AND ID_hébergement = '$logementId'";
        $reservationResult = $conn->query($reservationSql);

        if ($reservationResult->num_rows > 0) {
            $commentaireSql = "INSERT INTO Commentaires (ID_utilisateur, ID_hébergement, Contenu_commentaire, Note)
                VALUES ('$utilisateurConnecte', '$logementId', '$commentaire', '$note')";

            if ($conn->query($commentaireSql) === TRUE) {
                ?>
                <p>Commentaire ajouté avec succès.</p>
                <?php
            } else {
                ?>
                <p>Erreur lors de l'ajout du commentaire : <?php echo $conn->error; ?></p>
                <?php
            }
        } else {
            ?>
            <p>Vous devez d'abord réserver ce logement pour laisser un commentaire.</p>
            <?php
        }
    }

    if (isset($_GET['id'])) {
        $logementId = $_GET['id'];

        $sql = "SELECT * FROM Hébergements WHERE ID = '$logementId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <h2>Nom : <?php echo $row["Titre"]; ?></h2>
            <img src="<?php echo $row["Image"]; ?>" alt="Image du logement"><br>
            <img src="<?php echo $row["Image"]; ?>" alt="Image du logement"><br>
            <img src="<?php echo $row["Image"]; ?>" alt="Image du logement"><br>
            <p>Description : <?php echo $row["Description"]; ?></p>
            <p>Commodités : <?php echo $row["Commodités"]; ?></p>
            <p>Règles de la maison : <?php echo $row["Règles_de_la_maison"]; ?></p>
            <p>Début : <?php echo $row["Date_depart"]; ?></p>
            <p>Fin : <?php echo $row["Date_arrivée"]; ?></p>
            <p>Arrondissement : <?php echo $row["localisation"]; ?></p>
            <p>Places : <?php echo $row["capacite"]; ?></p>
            <?php

            // Vérification de la réservation
            $reservationSql = "SELECT * FROM Réservations WHERE ID_hébergement = '$logementId'";
            $reservationResult = $conn->query($reservationSql);

            if ($reservationResult->num_rows > 0) {
                ?>
                <p>Logement déjà réservé.</p>
                <?php
            } else {
                ?>
                <form method="POST" action="">
                    <input type="hidden" name="reserver" value="true">
                    <label for="Date_depart">Date d'arrivée :</label>
                    <input type="date" id="Date_depart" name="Date_depart" required><br>
                    <label for="Date_arrivée">Date de fin :</label>
                    <input type="date" id="Date_arrivée" name="Date_arrivée" required><br>
                    <label for="Nombre_personnes">Nombre de personnes :</label>
                    <input type="number" id="Nombre_personnes" name="Nombre_personnes" required><br>
                    <button type="submit">Réserver</button>
                </form>
                
                <?php if (isset($_POST['supprimer'])) {
                $logementId = $_GET['id'];

                // Supprimer le logement de la base de données
                $suppressionSql = "DELETE FROM Hébergements WHERE ID = '$logementId'";
                if ($conn->query($suppressionSql) === TRUE) {
                // Rediriger vers une page de confirmation ou autre
                header("Location: confirmation.php");
                    exit();
                } else {
                echo "Erreur lors de la suppression du logement : " . $conn->error;
                 }
                }
                ?>
                <?php if ($_SESSION['hebe']['role'] === 'Admin'): ?>
                    <form method="POST" action="">
                    <input type="hidden" name="supprimer" value="true">
                    <button type="submit">Supprimer</button>
                    </form>
                <?php endif; ?>
                <?php
            }

            // Formulaire de commentaire et de note
            ?>
            <h3>Ajouter un avis :</h3>
            <form method="POST" action="">
                <input type="hidden" name="commenter" value="true">
                <label for="commentaire">Commentaire :</label><br>
                <textarea id="commentaire" name="commentaire" rows="4" cols="50" required></textarea><br>
                <label for="note">Note :</label>
                <select id="note" name="note" required>
                    <option value="1">1 étoile</option>
                    <option value="2">2 étoiles</option>
                    <option value="3">3 étoiles</option>
                    <option value="4">4 étoiles</option>
                    <option value="5">5 étoiles</option>
                </select><br>
                <button type="submit">Envoyer</button>
            </form>
            <?php

            // Affichage des commentaires
            $commentairesSql = "SELECT c.Contenu_commentaire, c.Note, u.username FROM Commentaires c INNER JOIN users u ON c.ID_utilisateur = u.id WHERE c.ID_hébergement = '$logementId'";
            $commentairesResult = $conn->query($commentairesSql);

            if ($commentairesResult->num_rows > 0) {
                ?>
                <h3>Commentaires :</h3>
                <?php
                while ($commentaireRow = $commentairesResult->fetch_assoc()) {
                    ?>
                    <p>Auteur : <?php echo $commentaireRow['username']; ?></p>
                    <p>Commentaire : <?php echo $commentaireRow['Contenu_commentaire']; ?></p>
                    <p>Note : <?php echo $commentaireRow['Note']; ?></p>
                    <hr>
                    <?php
                }
            } else {
                ?>
                <p>Aucun commentaire pour le moment.</p>
                <?php
            }
        } else {
            echo "Logement introuvable.";
        }
    }

    $conn->close();
    ?>

</body>
</html>

















