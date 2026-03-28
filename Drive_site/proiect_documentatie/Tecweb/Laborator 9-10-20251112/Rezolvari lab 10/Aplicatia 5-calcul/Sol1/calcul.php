 <?php
if(($_POST['n1']=="")|| ($_POST['n2']=="")||($_POST['t']==""))

echo "Nu ati introdus toate informatiile necesare!" ;
else
{
$n1= $_POST['n1'];
$n2= $_POST['n2'];
$teza= $_POST['t'];
$med = ($n1+$n2)/2;
$medgen= (3* $med + $teza)/4;

echo 'Media generala a elevului '.$medgen.'<br>';
if($medgen>=5)
  $rez= 'promovat';
else
  $rez= 'nepromovat';
echo 'Rezultatul ' .$rez.'<br>';   }

?>