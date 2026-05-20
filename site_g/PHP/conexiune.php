<?php
// Parse the config file relative to this directory, indiferent de scriptul care include conexiunea.
$config = require __DIR__ . '/config.php';

// În PHP 8.x, mysqli poate arunca excepții implicit; păstrăm fluxul aplicației bazat pe verificări de retur.
mysqli_report(MYSQLI_REPORT_OFF);

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
