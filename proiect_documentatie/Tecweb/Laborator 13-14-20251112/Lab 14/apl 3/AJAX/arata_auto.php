
<?php
$q=$_GET["q"];

include("CONECTARE.php");

$sql="SELECT informatii_masini.ID_MASINA, marca.MARCA, model.MODEL, informatii_masini.COD_MASINA,  informatii_masini.COMBUSTIBIL,  informatii_masini.PRET
FROM informatii_masini, model, marca
WHERE informatii_masini.ID_MODEL='$q' AND informatii_masini.ID_MODEL=model.ID_MODEL AND model.ID_MARCA=marca.ID_MARCA ";
$resursa=mysqli_query($con,$sql);
$num=mysqli_num_rows($resursa);

if (!$resursa) {
    die('Invalid query: ' . mysqli_error());
}

if($num<>0){
echo "<table border='1'>
<tr>
<th>Select</th>
<th>Marca</th>
<th>Model</th>
<th>Combustibil</th>
<th>Pret</th>
</tr>";

while($row = mysqli_fetch_array($resursa))
  {
$id=$row['ID_MASINA'];
  
  echo "<tr>";
  echo "</td><td>";
  echo "<input type='checkbox' name='checkbox[]'  value='$id' size='7'>";
  echo "<td>" . $row['MARCA'] . "</td>";
  echo "<td>" . $row['MODEL'] . "</td>";
  echo "<td>" . $row['COMBUSTIBIL'] . "</td>";
  echo "<td>" . $row['PRET'] . "</td>";
  }
echo "</table>";
}
else echo "Aceasta masina nu are inregistrari introduse in baza de date!";
?>
