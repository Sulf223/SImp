<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
require_once('conectare.php'); 

$t=$_POST['t'];
$a=$_POST['a'];
$ed=$_POST['ed'];
$da=$_POST['da'];
$codv=$_POST['codv'];
$p= $_POST['p'];
echo $codv.'<br>';

$query = "UPDATE carti SET titlu='".$t."',autor='".$a. "',editura='".$ed."',dataa='".$da."',pret=".$p. " WHERE codc=".$codv.";" ;
mysqli_query($mysqli,$query);
$query="SELECT * FROM carti";
$result=mysqli_query($mysqli,$query);
$num=mysqli_num_rows($result);

echo "<b><center>Lista cartilor  </center></b><br><br>";
echo '<table border="1" width="600" >';
echo '<tr> <th> Cod carte</th> <th> Titlu </th> <th> Autor </th>  <th>Editura</th> <th>Data apartitie</th>  <th>Pret</th></tr>';


while ($row = mysqli_fetch_row($result)) {
$cd=$row[0];
$t=$row[1];
$a=$row[2];

$ed=$row[3];
$da=$row[4];
$pr=$row[5];

echo '<tr> <td>'.$cd.'</td> <td>'.$t.' </td> <td>'.$a.' </td><td>'.$ed.'</td><td>'.$da.'</td> 
<td>' .$pr.'</td></tr>';


}
mysqli_close($mysqli);
?> 

</body>
</html>
