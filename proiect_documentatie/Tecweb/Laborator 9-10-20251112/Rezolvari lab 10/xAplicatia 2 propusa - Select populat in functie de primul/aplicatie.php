<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head> 

<body>
<?php
if(!isset($_POST['nume']))$nm='';

if (isset($_POST['Submit']))$nm=$_POST['nume'];

$orase=array('Hunedoara'=>array('Deva','Hunedoara','Calan', 'Simeria','Orastie'), 'Alba'=>array('Alba-Iulia','Cugir','Brad','Zlatna','Tebea'), 'Timis'=>array('Timisoara','Lugoj','Savarsin','Remetea','Sincraieni'));


?>
 
<form name="form1" method="post" action="aplicatie.php">
  <p>Nume 
    <input name="nume" type="text" value="<?php echo $nm;?>" id="nume">
  </p>
  <p>Judet
    <label>
    <select name="judete" id="judete">
<?php 

 foreach($orase as $variabila=>$judet)
echo '<option value='.$variabila.'>'.$variabila.'  </option>'; 

?> 
      </select>
    </label>
</p>
  <p>Oras
    <label>
    <select name="oras" id="oras">
    <?php 
	if(($_POST['judete'])=='Hunedoara')
	{foreach($orase['Hunedoara'] as $oras)
echo '<option value='.$oras.'>'.$oras.' </option>';}
else if(($_POST['judete'])=='Alba')
	{foreach($orase['Alba'] as $oras)
echo '<option value='.$oras.'>'.$oras.' </option>';}
else if(($_POST['judete'])=='Timis')
	{foreach($orase['Timis'] as $oras)
echo '<option value='.$oras.'>'.$oras.' </option>';}
?>
    </select>
    </label>
</p>
  <p> 
    <input type="submit" name="Submit" value="Trimite">
    <input type="reset" name="Submit2" value="Reset">
  </p>
  <p>&nbsp;</p>
</form>

</body>
</html>
