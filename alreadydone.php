<!DOCTYPE html>
<html>
<head>
    <title>Mes réservations</title>
</head>
<body>
    <h1>Mes réservations</h1>

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
        die("Veuillez vous connecter pour afficher vos réservations.");
    }

    $utilisateurConnecte = $_SESSION['user']['id'];

    $reservationsSql = "SELECT * FROM Réservations WHERE ID_utilisateur = '$utilisateurConnecte'";
    $reservationsResult = $conn->query($reservationsSql);

    if ($reservationsResult->num_rows > 0) {
        while ($reservationRow = $reservationsResult->fetch_assoc()) {
            $logementId = $reservationRow['ID_hébergement'];
            $logementSql = "SELECT * FROM Hébergements WHERE ID = '$logementId'";
            $logementResult = $conn->query($logementSql);

            if ($logementResult->num_rows > 0) {
                $logementRow = $logementResult->fetch_assoc();
                echo "<h3>Réservation ID : " . $reservationRow['ID'] . "</h3>";
                echo "<p>Logement : " . $logementRow['Titre'] . "</p>";
                echo "<p>Date d'arrivée : " . $reservationRow['Date_depart'] . "</p>";
                echo "<p>Date de fin : " . $reservationRow['Date_arrivée'] . "</p>";
                echo "<p>Nombre de personnes : " . $reservationRow['Nombre_personnes'] . "</p>";
                echo "<hr>";
            }
        }
    } else {
        echo "<p>Aucune réservation trouvée.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
