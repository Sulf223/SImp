<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<h1>Stergere angajat</h1>
<form action="sterge.php" method="POST">
<table>
  <th colspan="2">Angajat</th>
<tr><td>
<select name="codcarte">
<?php
require_once('conectare.php'); 
$sql="SELECT * FROM  Carti ORDER BY Titlu ASC";
$resursa=mysqli_query($mysqli,$sql);
while($row=mysqli_fetch_row($resursa))
{
    print '<option value="'.$row[0].'">'.$row[1].' '.$row[2].'</option>';
}
?>
</select></td>
<td valign="top" align="center"> <INPUT type="submit" name="sterge_angajat" value="Sterge">
</td></tr>
</form>
</body>
</html>
