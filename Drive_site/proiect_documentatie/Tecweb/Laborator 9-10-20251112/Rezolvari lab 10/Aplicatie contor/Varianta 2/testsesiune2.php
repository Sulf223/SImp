 <?php
// testsession2.php
session_start();
echo 'Pagina #2<br />

Tipul actual: '.date('Y m d H:i:s').
'<br />Timpul initial (din sesiune): '.
date('Y m d H:i:s', $_SESSION['time']); 

?>


