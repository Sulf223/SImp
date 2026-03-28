<?php 
require_once('config.php');

if(!isset($_GET['actiune'])) $_GET['actiune'] = '';
if(!isset($_SESSION['user'])) $_SESSION['user'] = '';
if(!isset($_SESSION['parola1'])) $_SESSION['parola1'] = '';
if(!isset($_SESSION['parola2'])) $_SESSION['parola2'] = '';
if(!isset($_SESSION['nume'])) $_SESSION['nume'] = '';
if(!isset($_SESSION['prenume'])) $_SESSION['prenume'] = '';
if(!isset($_SESSION['varsta'])) $_SESSION['varsta'] = '';
if(!isset($_SESSION['localitate'])) $_SESSION['localitate'] = '';

switch($_GET['actiune'])
{
case '':
echo '<table width="309" border="0" cellpadding="0" cellspacing="0">
<form name="formular" action="inregistrare.php?actiune=validare" method="post">
  <tr>
    <td height="36" colspan="4" valign="top"><h1>Formular inregistrare </h1></td>
    </tr>
  <tr>
    <td width="80" height="19" valign="top">&nbsp;</td>
    <td width="15" rowspan="5" valign="top"></td>
    <td width="144" valign="top">&nbsp;</td>
    <td width="70" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" align="right" valign="top">Utilizator:</td>
    <td colspan="2" valign="top">
      <input type="text" name="user" value="'.$_SESSION['user'].'">    </td>
    </tr>
  <tr>
    <td height="7"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="top">Parola:</td>
    <td colspan="2" valign="top"><input type="password" name="parola1" value="'.$_SESSION['parola1'].'"></td>
    </tr>
  <tr>
    <td height="7"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="38" align="right" valign="top">Reintroduceti<br> Parola:</td>
    <td>&nbsp;</td>
    <td align="middle" valign="middle"><input type="password" name="parola2" value="'.$_SESSION['parola2'].'"></td>
  <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="7"></td>
    <td valign="top"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="19" align="right">Nume:</td>
    <td>&nbsp;</td>
    <td colspan="2" valign="top"><input type="text" name="nume" value="'.$_SESSION['nume'].'"></td>
    </tr>
  <tr>
    <td height="7"></td>
    <td valign="top"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="22" align="right">Prenume:</td>
    <td valign="top"></td>
    <td colspan="2" valign="top"><input type="text" name="prenume" value="'.$_SESSION['prenume'].'"></td>
  </tr>
  <tr>
    <td height="8"></td>
    <td valign="top"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="top">Varsta:</td>
    <td valign="top"></td>
    <td colspan="2" valign="top"><input type="text" size="3" maxLength="3" name="varsta" value="'.$_SESSION['varsta'].'"> 
      ani</td>
    </tr>
  <tr>
    <td height="15"></td>
    <td valign="top"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="top">Localitate:</td>
    <td valign="top"></td>
    <td colspan="2" valign="top"><input type="text" name="localitate" value="'.$_SESSION['localitate'].'"></td>
    </tr>
  <tr>
    <td height="19">&nbsp;</td>
    <td valign="top"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="24">&nbsp;</td>
    <td valign="top"></td>
    <td colspan="2" valign="top"><input name="Trimite" type="submit" id="Trimite" value="Trimite">
      <input name="Reseteaza" type="reset" id="Reseteaza" value="Reseteaza"> </td>
    </tr>
  <tr>
    <td height="24">&nbsp;</td>
    <td valign="top"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  </form>
</table>';
break;

case 'validare':

$_SESSION['user'] = $_POST['user'];
$_SESSION['parola1'] = $_POST['parola1'];
$_SESSION['parola2'] = $_POST['parola2'];
$_SESSION['nume'] = $_POST['nume'];
$_SESSION['prenume'] = $_POST['prenume'];
$_SESSION['varsta'] = $_POST['varsta'];
$_SESSION['localitate'] = $_POST['localitate'];


if(($_SESSION['user'] == '') || ($_SESSION['parola1'] == '') || ($_SESSION['parola2'] != $_SESSION['parola1']) || ($_SESSION['nume'] == '') || ($_SESSION['prenume'] == '') || ($_SESSION['varsta'] == '') || (!is_numeric($_SESSION['varsta'])) || (strlen($_SESSION['varsta']) < 2) || ($_SESSION['localitate'] == ''))
{
echo 'Nu ai introdus date in formular sau cele introduse nu sunt corecte. <br>
      Apasa <a href="inregistrare.php">aici</a> pentru a te intoarce la pagina anterioara.';
} 
else 
{
echo 'Va multumim. <br> 
      Datele au fost introduse cu succes in baza de date. <br>
	  Pentru a va autentifica apasati <a href="autentificare.php">aici</a>.';

$cerereSQL = "INSERT INTO `utilizatori` (`utilizator`, `parola`, `nume`, `prenume`, `varsta`, `localitate`)
	          VALUES ('".addentities($_SESSION['user'])."', '".md5($_SESSION['parola1'])."', '".addentities($_SESSION['nume'])."', '".addentities($_SESSION['prenume'])."', '".addentities($_SESSION['varsta'])."', '".addentities($_SESSION['localitate'])."')";
mysql_query($cerereSQL);

$_SESSION['user'] = '';
$_SESSION['parola1'] = '';
$_SESSION['parola2'] = '';
$_SESSION['nume'] = '';
$_SESSION['prenume'] = '';
$_SESSION['varsta'] = '';
$_SESSION['localitate'] = '';

}

break;

}

?>