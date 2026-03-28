<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head> 

<body>
<form name="form1" method="post" action="aplicatie.php">
<?php
$orase=array('Hunedoara'=>array('Deva','Hunedoara','Calan', 'Simeria','Orastie'), 'Alba'=>array('Alba-Iulia','Cugir','Brad','Zlatna','Tebea'), 'Timis'=>array('Timisoara','Lugoj','Savarsin','Remetea','Sincraieni'));

 echo ' Nume 
    <input name="nume" type="text" id="nume">';
 echo '
    Judet
    <select name="judete" id="judete">';
	
	foreach($orase as $variabila=>$judet)
echo '<option value='.$variabila.'>'.$variabila.' </option>';

  echo '  </select>';
  

   echo ' Oras
    <select name="oras" id="oras">';
	foreach($orase['Hunedoara'] as $oras)
echo '<option value='.$oras.'>'.$oras.' </option>';
   echo ' </select>';
 
   ?>
    <input type="submit" name="Submit" value="Trimite">
    <input type="reset" name="Submit2" value="Reset">
  </p>
  <p>&nbsp; </p>
</form>

</body>
</html>
