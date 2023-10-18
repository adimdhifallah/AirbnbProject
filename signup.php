<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");  
   exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $Name = $_POST["Name"];
    $Familyname = $_POST["Familyname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $errors = [];

    if (empty($username) || empty($Name) || empty($Familyname) || empty($email) || empty($password) || empty($passwordRepeat)) {
        array_push($errors, "All fields are required"); 
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      
      array_push($errors, "Email is not valid");

    }

    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }

    if ($password !== $passwordRepeat) {
        array_push($errors, "Passwords do not match");
    }
    require_once "Airbnb.php";
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $row_count = $stmt->rowCount(); 

    if ($row_count > 0) {
        array_push($errors, "username already exists");
    }


    require_once "Airbnb.php";
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $row_count = $stmt->rowCount(); 

    if ($row_count > 0) {
        array_push($errors, "Email already exists");
    }

    if (count($errors)) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'> $error</div>";
        }
    } else {
        $sql = "INSERT INTO users (username, name, familyname, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $Name, $Familyname, $email, $passwordHash]);
        echo "<div class='alert alert-success'> You are registered successfully.</div>";

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="components/logo2.png">
    <link rel="stylesheet" href="css js/Login&Signup.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <title>Signup</title>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <section class="Creation ">
        <div class="signup" >
            <div class="create">
                <ion-icon  class="return" name="arrow-back-outline"onclick="window.location.href = 'login.php';"></ion-icon>
                <h2>Création de compte</h2>
            </div>
            <form action="signup.php" method="post">
            <div class="field-wrap">
                <input type="text" name="username" placeholder="username">
            </div>
            <div class="field-wrap">
                <input type="text" name="Name" placeholder="Name">
            </div>
            <div class="field-wrap">
                <input type="text" name="Familyname" placeholder="Familyname">
            </div>
            <div class="field-wrap">
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="field-wrap">
                <input  type="password" name="password" placeholder="Password">
            </div>
            <div class="field-wrap">
                <input type="password" name="repeat_password" placeholder="Repeat Password">
            </div><br>

            <div class="remember">
                <input type="checkbox">
                <label for="">Se souvenir de moi</label>
            </div><br>
            
            <button class="button-log " type="submit">S'inscrire</button>

            <br><br>
            </form>
            <div class="barre">            
                <div></div>
                <p>ou</p>
                <div></div>
            </div><br>
            <div>
                <label style="font-size: 18px;">Compte existant</label>
            </div><br>
            <button  class="button-connect-fake " onclick="window.location.href = 'login.php';">Se connecter</button>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>






