<?php
// Sisällytetään asetustiedosto
require_once "config.php";
 
// Määrittellään muuttujat, sanitoidaan ja alustetaan tyhjillä arvoilla
$username = $password = filter_var($confirm_password = "",FILTER_SANITIZE_STRING);
$username_err = $password_err = filter_var($confirm_password_err = "",FILTER_SANITIZE_STRING);
 
// Lomaketietojen käsittely lomaketta lähetettäessä
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Vahvista käyttäjätunnus
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Valmistellaan valintalause
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Sidotaan muuttujat valmisteltuun lauseeseen parametreina
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Asetetaan parametrit
            $param_username = trim($_POST["username"]);
            
            // Yritetään suorittaa valmis lausunto
            if(mysqli_stmt_execute($stmt)){
                /* varastoidaan tulos */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Suljetaan
            mysqli_stmt_close($stmt);
        }
    }
    
    // Vahvistetaan salasana
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Vahvistetaan salasanan vahvistus
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Tarkistetaan syöttövirheet ennen kuin lisätään tietokantaan
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Valmistellaan lisäyslause
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Sidotaan muuttujat valmisteltuun lauseeseen parametreina
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Asetetaan parametrit
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Salasanan hashaus
            
            // Yritetään suorittaa valmis lausunto
            if(mysqli_stmt_execute($stmt)){
                // Ohjataan kirjautumis sivulle
                header("location: login.php");
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
    <title>Rekisteröityminen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Rekisteröityminen</h2>
        <p>Täytä tiedot luodaksesi käyttäjän.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Käyttäjänimi</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Salasana</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Vahvista salasana</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Onko sinulla jo käyttäjä? <a href="login.php">Kirjaudu tästä</a>.</p>
        </form>
    </div>    
</body>
</html>