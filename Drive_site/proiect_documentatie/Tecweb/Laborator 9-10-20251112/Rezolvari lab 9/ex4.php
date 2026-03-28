<html>
<head>
</head>
<body>


<?php
$azi = getdate(); 
$ora = $azi['hours']+2; 
$minute = $azi['minutes']; 
echo "Este ora $ora:$minute".'<br>';
if($ora <=10)
{
echo '<b> Meniu de dimineata  </b> <br>' ;
echo '<a href="home.php"> Home</a> <br>';
echo '<a href="listap.php"> Lista persoane</a> <br>';
}
else
{
echo '<b> Meniu de restul zilei </b> <br>' ;
echo '<a href="home.php"> Home</a> <br>';
echo '<a href="listap.php"> Lista persoane</a> <br>';
echo '<a href="preluared.php"> Preluare date</a> <br>';
echo '<a href="mailto:nume@yahoo.com"> Contact</a> <br>';

}
?>




</body>
</html>

