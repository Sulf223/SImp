<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
require_once('conectare.php'); 
$cod=trim($_POST['codcarte']);
$query="DELETE FROM carti WHERE codc='".$cod."';" ;
$result=mysqli_query($mysqli,$query);

$query="SELECT * FROM Carti";
$result=mysqli_query($mysqli,$query);
$num=mysqli_num_rows($result);

echo "<b><center>Lista cartilor </center></b><br><br>";
echo '<table border="1" width="600" >';
echo '<tr> <th> Cod carte</th> <th> Titlu </th> <th> Autor </th>  <th>Editura</th> <th>Data aparitiei </th> <th>Pret </th></tr>';

while ($row = mysqli_fetch_row($result)) {
$codp=$row[0];
$titlu=$row[1];
$autor=$row[2];
$editura=$row[3];
$da=$row[4];
$pr=$row[5];
echo '<tr> <td>'.$codp.'</td> <td>'.$titlu.' </td> <td>'.$autor.' </td><td>'.$editura.'</td><td>'.$da.'</td> <td>'.$pr.'</td></tr>';


}
mysqli_close($mysqli);
?> 

</body>
</html>
