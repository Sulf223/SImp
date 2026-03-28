<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php	
require_once('conectare.php');
if ($_SESSION['logat'] =='user') 
{
echo '<p>Alege o optiune</p>
<p><a >Introducere Angajati </a></p>
<p><a href="afisare.php">Afisare Angajati </a></p>';
}
else
echo 'Date incorecte. <Br> 
      Apasati <a href="index.html">aici</a> pentru a va intoarce la pagina precedenta.';
?>

<p><a href="afisare.php">  Afisare Angajati</a> </p>
</body>
</html>
