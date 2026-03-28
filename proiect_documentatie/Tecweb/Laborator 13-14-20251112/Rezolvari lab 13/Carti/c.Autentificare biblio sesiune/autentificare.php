<?php	
require_once('config.php');

if(!isset($_SESSION['user'])) $_SESSION['user'] = '';

if(!isset($_SESSION['parola'])) $_SESSION['parola'] = '';

if(($_POST['user'] == '') || ($_POST['parola'] == '')  )
{

echo 'Nu ai introdus date in formular sau cele introduse nu sunt corecte. <br>
Apasa <a href="javascript: history.go(-1)">aici</a> pentru a te intoarce la pagina anterioara.';
}
else
{
//echo md5($_POST['parola']);
$cerereSQL = "SELECT * FROM useri WHERE trim(user)='".htmlentities($_POST['user'])."' and trim(parola)='".md5($_POST['parola'])."'";
//$cerereSQL = "SELECT * FROM useri WHERE trim(user)='".$_POST['user'])."' and trim(parola)='".md5($_POST['parola'])."'";
$rezultat = mysqli_query($conexiune,$cerereSQL);
if(mysqli_num_rows($rezultat) == 1)
{
  while($rand = mysqli_fetch_row($rezultat))
  {
    $_SESSION['logat'] = 'Da';
    echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=formularactualizare.php">';
  }
}
else
{
echo 'Date incorecte. <Br> 
      Apasati <a href="index.htm">aici</a> pentru a va intoarce la pagina precedenta.';
}

}

?>