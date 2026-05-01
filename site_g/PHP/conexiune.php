<?php
// Parse the config file
$config = require 'config.php';

$host = $config['host'];
$user = $config['user'];
$pass = $config['pass'];
$db   = $config['db'];

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    // Logăm eroarea reală în logurile serverului (ex: error.log)
    error_log("Eroare conectare MySQL: " . mysqli_connect_error());
    // Afișăm un mesaj generic utilizatorului
    die("Eroare internă a serverului. Te rugăm să revii mai târziu.");
}

// Forțăm setul de caractere la utf8mb4 pentru a suporta corect diacriticele
mysqli_set_charset($con, "utf8mb4");
?>
