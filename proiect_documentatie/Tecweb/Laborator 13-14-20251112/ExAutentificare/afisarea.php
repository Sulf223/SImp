<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
require_once('conectare.php'); 
$cod=trim($_POST['codd']);
$query="SELECT * FROM departamente WHERE codd='".$cod."';" ;
$result=mysqli_query($conexiune,$query);
$row = mysqli_fetch_row($result);
$den=$row[1];

$query="SELECT * FROM angajati WHERE codd='".$cod."';" ;
$result=mysqli_query($conexiune,$query);
$num=mysqli_num_rows($result);
echo "<b><center>Lista angajatilor din departamentul <i>". $den. "</i></center></b><br><br>";
echo '<table border="1" width="600" >';
echo '<tr> <th> Cod pers</th> <th> Nume </th> <th> Prenume </th>   <th>Data nasterii</th></tr>';

while ($row = mysqli_fetch_row($result)) {
 $codp=$row[0];
 $nume=$row[1];
 $prenume=$row[2];
 $dn=$row[3];
 echo '<tr> <td>'.$codp.'</td> <td>'.$nume.' </td> <td>'.$prenume.' </td><td>'.$dn.'</td></tr>';
}
mysqli_close($conexiune);
?> 

</body>
</html>
