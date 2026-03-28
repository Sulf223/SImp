
<?php
echo $q=$_GET["q"];

include("CONECTARE.php");

$sql="SELECT * FROM  model  Where ID_MARCA='$q' ORDER BY MODEL ASC";
$resursa=mysqli_query($con,$sql);
$IdModel='';
while($row=mysqli_fetch_array($resursa))
{
	echo '<option ' . ($IdModel==$row['ID_MODEL'] ? 'selected' : '') . ' value="'.$row['ID_MODEL'].'">'.$row['MODEL'].'</option>'; 
 
}
mysqli_close($con);
?>
