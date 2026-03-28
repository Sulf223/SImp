<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Iesiri</title>
<style type="text/css">
<!--
body {
	background-color: #9FF;
}
-->
</style>
</head>
<body>
<h1 align="center">JURNAL VANZARI</h1>
<?php
include ("CONECTARE.PHP");

$resursa=mysqli_query($con,"SELECT iesiri.DATA_IESIRE, iesiri.SERIE_FACTURA, iesiri.NR_FACTURA, marca.MARCA, model.MODEL, informatii_masini.COMBUSTIBIL, informatii_masini.PRET, persoane.Nume, persoane.CNP
FROM iesiri, informatii_masini, model, marca, persoane 
WHERE iesiri.ID_MASINA=informatii_masini.ID_MASINA AND informatii_masini.ID_MODEL=model.ID_MODEL AND model.ID_MARCA=marca.ID_MARCA  AND iesiri.ID_PERSOANA=persoane.IdPersoana");
if (!$resursa) {
    die('Invalid query: ' . mysqli_error());
}

echo "<table border='1'>
<tr>
<th>Data<br> Tranzactie</th>
<th>Nume prenume</th>
<th>CNP</th>
<th>Marca</th>
<th>Model</th>
<th>Combustibil</th>
<th>Pret</th>
<th>Nr<br> factura</th>
<th>Serie<br> factura</th>
</tr>";

while($row = mysqli_fetch_array($resursa))
  {
$dataiesire=date("d-m-Y",strtotime($row['DATA_IESIRE']));  
  echo "<tr>";
  echo "<td>" . $dataiesire . "</td>";
  echo "<td>" . $row['Nume'] . "</td>";
  echo "<td>" . $row['CNP'] . "</td>";
  echo "<td>" . $row['MARCA'] . "</td>";
  echo "<td>" . $row['MODEL'] . "</td>";
  echo "<td>" . $row['COMBUSTIBIL'] . "</td>";
  echo "<td>" . $row['PRET'] . "</td>";
  echo "<td>" . $row['NR_FACTURA'] . "</td>";
  echo "<td>" . $row['SERIE_FACTURA'] . "</td>";
  
  }
echo "</table>";


?>

</body>
</html>