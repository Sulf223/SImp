<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
require_once('conectare.php');
include_once("index.html");
if(($_SESSION['student'] == "") || ($_SESSION['data_n'] ==
"") || ($_SESSION['an_inscriere'] == "") ||
($_SESSION['email'] == "") || ($_SESSION['telefon'] == "")||
($_SESSION['mobil'] == "") || ($_SESSION['obs'] == "") ||
(strlen($_SESSION['obs']) > 255) )
{
echo 'Nu ai introdus date in formular sau cele introduse nu
sunt corecte. <br>
Apasa <a href="javascript: history.go(-2)">aici</a> pentru a
te intoarce la pagina anterioara.';
}
else
{
$cerereSQL = "INSERT INTO `an1` (`Student`, `Data_n`,
`An_inscriere`, `Email`,`telefon`,`telmobil`, `observatii`)
VALUES ('".$_SESSION['student']."',
'".$_SESSION['data_n']."', '".$_SESSION['an_inscriere']."',
'".$_SESSION['email']."',
'".$_SESSION['telefon']."','".$_SESSION['mobil']."',
'".$_SESSION['obs']."');";
$result=mysqli_query( $conexiune,$cerereSQL);
if ($result) {
echo 'Va multumim. <br>
Datele au fost introduse cu succes in baza de date. <br>
Pentru vizualizare apasati <a
href="vizualizare.php">aici</a>.';
} else {
echo "Eroare la adaugarea in baza de date.";};
$_SESSION['student'] = '';
$_SESSION['data_n'] = '';
$_SESSION['an_inscriere'] = '';
$_SESSION['email'] = '';
$_SESSION['telefon'] = '';
$_SESSION['mobil'] = '';
$_SESSION['obs'] = '';}
?>

</body>
</html>
