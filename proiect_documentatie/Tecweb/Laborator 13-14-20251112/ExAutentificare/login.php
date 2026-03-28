
<?php	
require_once('conectare.php');

$_SESSION['user'] = $_POST['user'];
$nm= $_POST['user'];
$pr=$_POST['parola'];

if(($_POST['user'] == '') || ($_POST['parola'] == '') )
{
echo 'Completeaza casutele. <Br> 
      Apasati <a href="index.html">aici</a> pentru a va intoarce la pagina precedenta.';
}
else
{
 if ( $nm == 'admin' and  $pr == 'admin' )
  {
    $_SESSION['logat'] = 'admin';
	echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=admin.php">';
  }
  else
  if ( $nm == 'user' and  $pr == 'user' )
  {
    $_SESSION['logat'] = 'user';
	echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=user.php">';
  }
  else
{
echo 'Date incorecte. <Br> 
      Apasati <a href="index.html">aici</a> pentru a va intoarce la pagina precedenta.';
}

}

?>