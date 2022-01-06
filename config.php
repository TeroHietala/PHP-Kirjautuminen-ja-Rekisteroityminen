<?php
// tietokannan tunnistetiedot
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'n0hite00');
 
// yhteyden luonti
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// yhtyeden tarkastus
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>