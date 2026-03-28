<?php
require_once('config.php');

if(!isset($_GET['actiune'])) $_GET['actiune'] = '';
if(!isset($_SESSION['logat'])) $_SESSION['logat'] = 'Nu';

if($_SESSION['logat'] != 'Da') 
{
echo 'Pentru a accesa aceasta pagina, trebuie sa va autentificati. <br>
      Pentru a va autentifica, apasati <a href="autentificare.php">aici</a><br>
	  Pentru a va inregistra, apasati <a href="inregistrare.php">aici</a>';
}
else
{
switch($_GET['actiune'])
{
case '':
echo '<h1>Profilul dumneavoastra</h1>
      Apasati <a href="profil.php?actiune=date_personale">aici</a> pentru a schimba datele personale.<br>
	  Apasati <a href="profil.php?actiune=parola">aici</a> pentru a schimba parola dumneavoastra.<br><br>
	  <a href="pagina.php">Intoarceti`va la pagina principala.</a>';
break;

case 'date_personale':
   $cerereSQL = 'SELECT * FROM `utilizatori` WHERE utilizator="'.$_SESSION['user'].'"'; 
   $rezultat = mysql_query($cerereSQL);
   while($rand = mysql_fetch_array($rezultat))
   {
echo '<table width="347" border="0" cellpadding="0" cellspacing="0">
<form name="formular" action="profil.php?actiune=validare" method="post">
  <tr>
    <td height="50" colspan="4" valign="top"><h1>Modifica date personale</h1></td>
    </tr>
  <tr>
    <td width="80" height="19">&nbsp;</td>
    <td width="15">&nbsp;</td>
    <td width="214">&nbsp;</td>
    <td width="38">&nbsp;</td>
    </tr>

  <tr>
    <td height="10"></td>
    <td></td>
    <td></td>
    </tr>
  <tr>
    <td height="22" align="right" valign="top">Nume:</td>
    <td valign="top"></td>
    <td valign="top"><input type="text" name="nume" value="'.$rand['nume'].'"></td>
    <td></td>
    </tr>
  <tr>
    <td height="9"></td>
    <td valign="top"></td>
    <td></td>
    <td></td>
    </tr>
  <tr>
    <td height="22" align="right" valign="top">Prenume:</td>
    <td valign="top"></td>
    <td valign="top"><input type="text" name="prenume" value="'.$rand['prenume'].'"></td>
    <td></td>
    </tr>
  <tr>
    <td height="9"></td>
    <td valign="top"></td>
    <td></td>
    <td></td>
    </tr>
  <tr>
    <td height="22" align="right" valign="top">Varsta:</td>
    <td valign="top"></td>
    <td valign="top"><input type="text" size="3" maxLength="3" name="varsta" value="'.$rand['varsta'].'"> 
      ani</td>
    <td></td>
    </tr>
  <tr>
    <td height="8"></td>
    <td valign="top"></td>
    <td></td>
    <td></td>
    </tr>
  <tr>
    <td height="22" align="right" valign="top">Localitate:</td>
    <td valign="top"></td>
    <td valign="top"><input type="text" name="localitate" value="'.$rand['localitate'].'"></td>
    <td></td>
    </tr>
  <tr>
    <td height="14"></td>
    <td valign="top"></td>
    <td></td>
    <td></td>
    </tr>
  <tr>
    <td height="24"></td>
    <td valign="top"></td>
    <td valign="top"><input name="Trimite" type="submit" id="Trimite" value="Modifica date">
      <input name="Reseteaza" type="reset" id="Reseteaza" value="Reseteaza"> </td>
    <td></td>
    </tr>
  <tr>
    <td height="19"></td>
    <td valign="top"></td>
    <td>&nbsp;</td>
    <td></td>
    </tr>
  </form>
</table>';
}
break;

case 'parola':

echo '<table width="309" border="0" cellpadding="0" cellspacing="0">
<form name="formular" action="profil.php?actiune=validare" method="post">
  <tr>
    <td height="36" colspan="4" valign="top"><h1>Modifica parola</h1></td>
    </tr>
  <tr>
    <td width="80" height="19" valign="top">&nbsp;</td>
    <td width="15" rowspan="5" valign="top"></td>
    <td width="144" valign="top">&nbsp;</td>
    <td width="70" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" align="right" valign="top">Parola:</td>
    <td colspan="2" valign="top">
      <input type="password" name="parola1" value="">    </td>
    </tr>
  <tr>
    <td height="7"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="top">Reintroduceti parola:</td>
    <td colspan="2" valign="middle"><input type="password" name="parola2" value=""></td>
    </tr>
  <tr>
    <td height="7"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="24">&nbsp;</td>
    <td valign="top"></td>
    <td colspan="2" valign="top"><input name="Trimite" type="submit" id="Trimite" value="Modifica parola">
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

if(!isset($_POST['parola1'])) $_SESSION['parola1'] = '';
else $_SESSION['parola1'] = $_POST['parola1'];

if(!isset($_POST['parola2'])) $_SESSION['parola2'] = '';
else $_SESSION['parola2'] = $_POST['parola2'];

if(!isset($_POST['nume'])) $_SESSION['nume'] = '';
else $_SESSION['nume'] = $_POST['nume'];

if(!isset($_POST['prenume'])) $_SESSION['prenume'] = '';
else $_SESSION['prenume'] = $_POST['prenume'];

if(!isset($_POST['varsta'])) $_SESSION['varsta'] = '';
else $_SESSION['varsta'] = $_POST['varsta'];

if(!isset($_POST['localitate'])) $_SESSION['localitate'] = '';
else $_SESSION['localitate'] = $_POST['localitate'];

if(($_POST['Trimite'] == 'Modifica date') && ($_SESSION['nume'] == '' || $_SESSION['prenume'] == '' || $_SESSION['varsta'] == '' || !is_numeric($_SESSION['varsta']) || strlen($_SESSION['varsta'] < 2) || $_SESSION['localitate'] == ''))
{
echo 'Completeaza campurile.<br>
      Apasa <a href="profil.php?actiune=date_personale">aici</a> pentru a te intoarce.';
}
elseif(($_POST['Trimite'] == 'Modifica date') && ($_SESSION['nume'] != '' || $_SESSION['prenume'] != '' || $_SESSION['varsta'] != '' || is_numeric($_SESSION['varsta']) || strlen($_SESSION['varsta'] >= 2) || $_SESSION['localitate'] != ''))
{
echo 'Datele au fost modificate. <br>
      Apasa <a href="pagina.php">aici</a> pentru a te intoarce la pagina principala.';
$cerereSQL = "UPDATE `utilizatori` SET nume='".addentities($_SESSION['nume'])."', prenume='".addentities($_SESSION['prenume'])."', varsta='".addentities($_SESSION['varsta'])."', localitate='".addentities($_SESSION['localitate'])."' WHERE utilizator='".$_SESSION['user']."'";
mysql_query($cerereSQL);	

$_SESSION['nume'] = '';
$_SESSION['prenume'] = '';
$_SESSION['varsta'] = '';
$_SESSION['localitate'] = '';  
}
elseif(($_POST['Trimite'] == 'Modifica parola') && ($_SESSION['parola1'] == '' || $_SESSION['parola1'] != $_SESSION['parola2']))
{
echo 'Completeaza campurile.<br>
      Apasa <a href="profil.php?actiune=parola">aici</a> pentru a te intoarce.';
}
elseif(($_POST['Trimite'] == 'Modifica parola') && ($_SESSION['parola1'] != '' || $_SESSION['parola1'] == $_SESSION['parola2']))
{
echo 'Parola a fost modificata. <br>
      Apasa <a href="pagina.php">aici</a> pentru a te intoarce la pagina principala.';
$cerereSQL = "UPDATE `utilizatori` SET parola='".md5($_SESSION['parola1'])."' WHERE utilizator='".$_SESSION['user']."'";
mysql_query($cerereSQL);	

$_SESSION['parola1'] = '';
$_SESSION['parola2'] = '';  
}

break;
}
}

?>


