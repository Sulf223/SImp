<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
require_once('conectare.php');
if(!isset($_SESSION['student'])) 
   $_SESSION['student'] ='student'.rand(10,100);
if(!isset($_SESSION['data_n'])) 
   $_SESSION['data_n'] =''.rand(10,100);
if(!isset($_SESSION['an_inscriere']))
  $_SESSION['an_inscriere'] = ''.rand(10,100);
if(!isset($_SESSION['email'])) 
  $_SESSION['email'] =''.rand(10,100);
if(!isset($_SESSION['telefon'])) 
   $_SESSION['telefon'] =''.rand(10,100);
if(!isset($_SESSION['mobil'])) 
   $_SESSION['mobil'] =''.rand(10,100);
if(!isset($_SESSION['obs'])) 
   $_SESSION['obs'] =''.rand(10,100);
include_once("index.html");
echo '<table width="380" border="0" cellpadding="0"
cellspacing="2">
<form name="stud" action="validare.php" method="post">
<tr>
<td height="30" colspan="2" valign="top"><h2>Introducere
Studenti</h2> </td>
</tr>
<tr>
<td height="22" align="right" valign="top">Student:</td>
<td valign="top">
<input type="text" name="student"
value="'.$_SESSION['student'].'"> </td>
</tr>
<tr>
<td height="22" align="right" valign="top">Data
Nasterii:</td>
<td valign="top"><input type="text" name="data_n"
value="'.$_SESSION['data_n'].'"><i>(aaaa-ll-zz)</td>
</tr>
<tr>
<td height="22" align="right" valign="top">An inscriere:
</td>

<td valign="top"><input type="text" size="4" maxLength="4"
name="an_inscriere"
value="'.$_SESSION['an_inscriere'].'"></td>
</tr>
<tr>
<td height="22" align="right" valign="top">E-mail:</td>
<td valign="top"><input type="text" name="email"
value="'.$_SESSION['email'].'"></td>
</tr>
<tr>
<td height="22" align="right" valign="top">Telefon:</td>
<td valign="top"><input type="text" name="telefon"
value="'.$_SESSION['telefon'].'"></td>
</tr>
<tr>
<td height="22" align="right" valign="top">Mobil:</td>
<td valign="top"><input type="text" name="mobil"
value="'.$_SESSION['mobil'].'"></td>
</tr>
<tr>
<td height="19" align="right" valign="top">Observatii:</td>
<td rowspan="2" valign="top"><textarea name="obs" cols="30"
rows="5"
value="'.$_SESSION['obs'].'">'.$_SESSION['obs'].'</textarea>
</td>
</tr>
<tr>
<td </td>
<td </td>
</tr>
<tr>
<td colspan="2" valign="top"><input name="Trimite"
type="submit" id="Trimite" value="Trimite">
<input name="Reseteaza" type="reset" id="Reseteaza"
value="Reseteaza"> </td>
</tr>
</form> </table>';
?>

</body>
</html>