<?php
// Alustetaan istunto
session_start();
 
// Poistetaan kaikki istunnon muuttujat
$_SESSION = array();
 
// Tuhotaan istunto
session_destroy();
 
// Ohjataan kirjautumis sivulle
header("location: login.php");
exit;
?>