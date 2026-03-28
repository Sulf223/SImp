<?php
$host = "localhost";
$user = "root";
$pass = 'Parola_imposibila223$';  // parola 
$db   = "dbsortari";

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Eroare conectare MySQL: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");
?>
