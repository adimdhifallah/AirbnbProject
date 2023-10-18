<!DOCTYPE html>
<html>
<head>
    <title>Recherche de logements</title>
</head>
<body>
    <form method="post" action="logements.php">
        <input type="text" name="localisation" placeholder="Arrondissement" value="<?php echo isset($_POST['localisation']) ? $_POST['localisation'] : ''; ?>">
        <input type="number" name="capacite" placeholder="Places" value="<?php echo isset($_POST['capacite']) ? $_POST['capacite'] : ''; ?>">
        <input type="date" name="date_debut" placeholder="Date de début" value="<?php echo isset($_POST['date_debut']) ? $_POST['date_debut'] : ''; ?>">
        <input type="date" name="date_fin" placeholder="Date de fin" value="<?php echo isset($_POST['date_fin']) ? $_POST['date_fin'] : ''; ?>">
        <input type="submit" value="Rechercher">
    </form>
</body>
</html>








