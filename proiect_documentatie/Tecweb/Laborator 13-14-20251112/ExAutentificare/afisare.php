<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<h1>Afisare  angajati pe departamente </h1>
<p>Selecteaza departamentul</p>
<form action="afisarea.php" method="POST">
<table>
  <th colspan="2">Lista departamentelor</th>
<tr><td>
<select name="codd">
<?php
require_once('conectare.php'); 
$sql="SELECT * FROM  Departamente ORDER BY denumire ASC";
$resursa=mysqli_query($conexiune,$sql);
while($row=mysqli_fetch_row($resursa))
{
    print '<option value="'.$row[0].'">'.$row[1].'</option>';
}
?>
</select></td>
<td valign="top" align="center"> <INPUT type="submit" name="Afiseaza angajati" value="Afisare">
</td></tr>
</form>
</body>
</html>

