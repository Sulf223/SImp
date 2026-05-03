<?php
// Parse the config file
$config = require 'config.php';

$host = $config['host'];
$user = $config['user'];
$pass = $config['pass'];
$db   = $config['db'];

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    // Logăm eroarea reală în logurile serverului (ex: error.log)
    error_log("Eroare conectare MySQL: " . $con->connect_error);
    // Afișăm un mesaj generic utilizatorului
    die("Eroare internă a serverului. Te rugăm să revii mai târziu.");
}

// Forțăm setul de caractere la utf8mb4 pentru a suporta corect diacriticele
$con->set_charset("utf8mb4");
?>
