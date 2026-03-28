<?php // seteaza variabilele de acces la serverul MySQL
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$db = "db_masini"; 
// creeaza obiectul mysqli
 // deschide conexiunea
 $con = new mysqli($host, $user, $pass, $db); 
// verifica daca au aparut erori la conectare
 if (mysqli_connect_errno())
 {echo "Failed to connect to MySQL: " . mysqli_connect_error(); 
 die("Nu ma pot conecta la serverul MySQL. Nu se poate deschide BD!"); }

?>



