<?php
// testsession1.php
session_start();
echo "Variabila setata in pagina pagina.php: " . $_SESSION["numec"] . ".<br>";
echo 'Pagina #1';
$_SESSION['time'] = time();
echo $_SESSION['time'] ;
echo '<br /><a href="testsesiune2.php?' . SID . '">pagina 2
cu sesiune</a>'; 
?>

