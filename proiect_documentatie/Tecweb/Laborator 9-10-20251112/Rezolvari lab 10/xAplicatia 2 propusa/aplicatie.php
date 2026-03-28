<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head> 

<body>
<?php
if(!isset($_POST['nume']))$nm='';
if(!isset($_POST['judet']))$jud='';
if(!isset($_POST['oras']))$or='';

if(isset($_POST['Submit']))
if(($_POST['nume']=='') && ($_POST['judet']=='') && ($_POST['oras']==''))
{
echo "Nu ati introdus informatii in toate campurile";
$nm='';
$jud='';
$or='';
}
else 

{
$nm= $_POST['nume'];
$jud= $_POST['judet'];
$or= $_POST['oras'];

echo '
<table width="200" border="1">
  <tr>
    <th>Nume</th>
    <th>Prenume</th>
    <th>Adresa email</th>
  </tr>
  <tr>
    <td>'.$nm.'</td>
    <td>'.$jud.'</td>
    <td>'.$or.'</td>
  </tr>
</table> ';

}



?>
 
<form name="form1" method="post" action="aplicatie.php">
  <p>Nume 
    <input name="nume" type="text" value="<?php echo $nm;?>" id="nume">
  </p>
  <p>Judet
    <input name="judet" type="text" value="<?php echo $jud; ?>" id="judet">
  </p>
  <p>Oras
    <input name="oras" type="text" value="<?php echo $or; ?>" id="oras">
  </p>
  <p> 
    <input type="submit" name="Submit" value="Trimite">
    <input type="reset" name="Submit2" value="Reset">
  </p>
  <p>&nbsp;</p>
</form>

</body>
</html>
