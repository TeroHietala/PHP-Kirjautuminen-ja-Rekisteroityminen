<?php
// Alustetaan istunto
session_start();
 
// Tarkistetaan onko käyttäjä kirjautunut sisään, jos ei, ohjaa hänet kirjautumissivulle
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tervetuloa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Tervetuloa <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
    <p>
        <a href="reset-password.php" class="btn btn-dark">Nollaa salasanasi</a>
        <a href="logout.php" class="btn btn-dark ml-3">Kirjaudu ulos</a>
    </p>
</body>
</html>