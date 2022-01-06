<?php
// Istunnon alustaminen
session_start();
 
// Tarkistaa, onko käyttäjä jo kirjautunut sisään, jos on, ohjaa hänet tervetulosivulle
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Sisällytä asetustiedosto
require_once "config.php";
 
// Määritellään muuttujat ja sanitoidaan
$username =  filter_var($password = "", FILTER_SANITIZE_STRING);
$username_err = filter_var($password_err = $login_err = "", FILTER_SANITIZE_STRING);
 
// Lomaketietojen käsittely
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Tarkastetaan onko käyttäjänimi tyhjä
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Tarkastetaan onko salasana tyhjä
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    
    if(empty($username_err) && empty($password_err)){
        // Lomaketietojen käsittely lomaketta lähetettäessä
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Sidotaan muuttujat valmisteltuun lauseeseen parametreina
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Asetetaan parametrit
            $param_username = $username;
            
            // Yritetään suorittaa valmis lausunto
            if(mysqli_stmt_execute($stmt)){
                // Tallennetaan  tulos
                mysqli_stmt_store_result($stmt);
                
                // Tarkista onko käyttäjätunnus olemassa, jos on, vahvista salasana
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Sidotaan tulosmuuttujat
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Salasana oikein joten aloitetaan uusi istunto
                            session_start();
                            
                            // Tallennetaan tiedot istuntomuuttujiin
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Ohjataan käyttäjä tervetuloa sivulle
                            header("location: welcome.php");
                        } else{
                            // Jos salasana ei kelpaa, näytetään virheilmoitus
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Jos käyttäjänimeä ei löydy, näytetään virheilmoitus
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Suljetaan
            mysqli_stmt_close($stmt);
        }
    }
    
    // Suljetaan yhteys
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kirjautuminen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Kirjaudu sisään</h2>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Käyttäjänimi</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Salasana</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Eikö sinulla ole käyttäjää? <a href="register.php">Rekisteröidy nyt</a>.</p>
        </form>
    </div>
</body>
</html>