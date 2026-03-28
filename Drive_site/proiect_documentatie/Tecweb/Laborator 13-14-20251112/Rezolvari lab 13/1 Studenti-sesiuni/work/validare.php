<?php
require_once('config.php');
if(!isset($_SESSION['student'])) $_SESSION['student'] = '';
if(!isset($_SESSION['datan'])) $_SESSION['datan'] = '';
if(!isset($_SESSION['an_inscriere'])) $_SESSION['an_inscriere'] = '';
if(!isset($_SESSION['e-mail'])) $_SESSION['e-mail'] = '';
if(!isset($_SESSION['telefon'])) $_SESSION['telefon'] = '';
if(!isset($_SESSION['mobil'])) $_SESSION['mobil'] = '';
if(!isset($_SESSION['obs'])) $_SESSION['obs'] = '';

$_SESSION['student'] = addentities($_POST['student']);
$_SESSION['data_n'] = addentities($_POST['data_n']);
$_SESSION['an_inscriere'] = addentities($_POST['an_inscriere']);
$_SESSION['email'] = addentities($_POST['email']);
$_SESSION['telefon'] = addentities($_POST['telefon']);
$_SESSION['mobil'] = addentities($_POST['mobil']);
$_SESSION['obs'] = addentities($_POST['obs']);
include_once("pas.php");
echo 'Student: '.$_SESSION['student'].'<br>
Data nasterii: '.$_SESSION['data_n'].'<br>
An inscriere: '.$_SESSION['an_inscriere'].'<br>
Email: '.$_SESSION['email'].'<br>
Telefon: '.$_SESSION['telefon'].'<br>
Mobil: '.$_SESSION['mobil'].'<br>
Comentariu: '.$_SESSION['obs'].'<br><br>
Daca datele sunt corecte, apasati <a href="prelucrare.php">aici</a>
pentru a le valida <br> si a le introduce in baza de date.';
?>