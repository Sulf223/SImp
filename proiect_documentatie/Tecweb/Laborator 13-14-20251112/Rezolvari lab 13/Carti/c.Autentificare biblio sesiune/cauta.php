<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
require_once('config.php'); 
$cd=trim($_POST['coda']);
$query="SELECT * FROM carti WHERE codc='".$cd."';" ;
$result=mysqli_query($conexiune,$query);
$num=mysqli_num_rows($result);
if ( $num <> 0 )
{
  $row=mysqli_fetch_row($result);
  print '<form action="update.php" method="post">';
  
  print 'Titlu : <input name="t" type="text" value="'.$row[1].'" > <br>';
  print 'Autor : <input name="a" type="text" value="'.$row[2].'" > <br>';
  print 'Editura : <input name="ed" type="text" value="'.$row[3].'" > <br>';
  print 'Data apartie <input name="da" type="text" value="'.$row[4].'" > <br> ';
  print 'Pret <input name="p" type="text" value="'.$row[5].'" > <br> ';
  print  '<input name="codv" type="hidden" value="'. $cd .'"><br';
  print '<br>  <br> ';
  print ' <input type="Submit"  value="Update" > ';
  print '</form>'; 
}  
else
 echo 'Nu avem o astfel de inregistrare';
mysqli_close($conexiune);
?> 

</body>
</html>
