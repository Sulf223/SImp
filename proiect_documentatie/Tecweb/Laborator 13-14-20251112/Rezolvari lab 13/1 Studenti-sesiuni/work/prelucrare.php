<?php
require_once('config.php');
include_once("pas.php");
if(($_SESSION['student'] == "") || ($_SESSION['data_n'] == "") || ($_SESSION['an_inscriere'] == "") || ($_SESSION['email'] == "") || ($_SESSION['telefon'] == "")|| ($_SESSION['mobil'] == "") || ($_SESSION['obs'] == "") || (strlen($_SESSION['obs']) > 255) )
{
echo 'Nu ai introdus date in formular sau cele introduse nu sunt corecte. <br>
Apasa <a href="javascript: history.go(-2)">aici</a> pentru a te intoarce la pagina anterioara.';
}
else
{
$cerereSQL = "INSERT INTO `an1` (`student`, `data_n`, `an_inscriere`, `email`,`telefon`,`mobil`, `obs`)
VALUES ('".$_SESSION['student']."', '".$_SESSION['data_n']."', '".$_SESSION['an_inscriere']."', '".$_SESSION['email']."', '".$_SESSION['telefon']."','".$_SESSION['mobil']."','".$_SESSION['obs']."');";
$result=mysqli_query( $conexiune,$cerereSQL);
if ($result) {
echo 'Va multumim. <br>
Datele au fost introduse cu succes in baza de date. <br>
Pentru vizualizare apasati <a href="vizualizare.php">aici</a>.';
} else {
echo "Eroare la adaugarea in baza de date.";};
$_SESSION['student'] = '';
$_SESSION['data_n'] = '';
$_SESSION['an_inscriere'] = '';
$_SESSION['email'] = '';
$_SESSION['telefon'] = '';
$_SESSION['mobil'] = '';
$_SESSION['obs'] = '';
}
?>