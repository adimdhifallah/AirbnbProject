<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: welcome.php");
   exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once "database/database.php";
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user"] = $user;
        header("Location: welcome.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Invalid email or password</div>";
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
    <title>Login</title>
</head>
<body>

    <section class="Connexion ">
        <div class="login">
            <div class="create">
                <ion-icon class="return" name="arrow-back-outline"></ion-icon>
                <h2>Connexion</h2>
            </div>
            <form action="login.php" method="post">

            <div class="field-wrap">
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="field-wrap">
                <input  type="password" name="password" placeholder="Password">
            </div><br><br>
            <div class="remember-parent">
                <div class="remember">
                    <input type="checkbox">
                    <span>Se souvenir de moi</span>
                </div>
                <a href="#">Mot de passe oublier?</a>
            </div>
            <br><br>
            
            <input class="button-connect" value="Login" type="submit">Se connecter</input>
            </form>

            <br><br>
            <div class="barre">
                <div></div>
                <p>ou</p>
                <div></div>
            </div><br>
            <div>
                <label style="font-size: 18px;">Compte existant</label>
            </div><br>
            <button class="button-log-fake" onclick="window.location.href = 'signup.php';">S'inscrire</button>
        </div>
    </section>


    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>
