<!DOCTYPE html>
<html>
<head>
    <title>Upload d'image et affichage</title>
</head>
<body>
    <h1>Upload d'image et affichage</h1>
    <?php
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Airbnb";

    $conn = new mysqli($servername, $username, $password, $dbname);
    session_start();
    if (!isset($_SESSION['user']))

    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }
    ?>
    
    <?php if ($_SESSION['users']['role'] === 'Admin'): ?>
          <form action="" method="POST" enctype="multipart/form-data">
          <label for="image">Sélectionnez une image :</label>
          <input type="file" name="image" id="image">
        
           <input type="submit" value="Télécharger">
         </form>

                <?php endif; ?>
                <?php
    
   
    $conn = mysqli_connect("localhost", "root", "", "airbnb");
    if (!$conn) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Vérifier si une image a été téléchargée
    if (isset($_FILES['image'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageBase64 = base64_encode($imageData);
        $imgType = $_FILES['image']['type'];

        // Enregistrer l'image en base 64 dans la base de données
        $query = "INSERT INTO images (image_data, image_type) VALUES ('" . mysqli_real_escape_string($conn, $imageBase64) . "', '" . mysqli_real_escape_string($conn, $imgType) . "')";
        if (mysqli_query($conn, $query)) {
            echo "L'image a été enregistrée avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement de l'image : " . mysqli_error($conn);
        }
    }

    // Récupérer l'image encodée en base 64 et le type depuis la base de données
    $result = mysqli_query($conn, "SELECT image_data, image_type FROM images ORDER BY img_id DESC");
    if ($row = mysqli_fetch_assoc($result)) {
        $imageBase64 = $row['image_data'];
        $imageType = $row['image_type'];

        echo "<h2>Image récemment téléchargée :</h2>";
        echo "<p>Type: $imageType</p>";
        echo "<img src='data:image/$imageType;base64," . $imageBase64 . "' alt='Image'>";
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
    ?>
</body>
</html>
