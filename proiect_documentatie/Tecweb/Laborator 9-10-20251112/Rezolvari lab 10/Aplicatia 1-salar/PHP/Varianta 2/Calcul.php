<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php
     if(isset($_POST['button'])) {
      	$sal = $_POST['sal'];
      	$csan= floor(10.5/100 *$sal);
      	$impoz= floor(0.16 * ($sal-$csan));
      	$salnet = $sal - $impoz - $csan;	  }
       else
	  { $impoz= "";
	  $sal ="";
	  $csan= "";
	  $salnet = "";}

	     
echo ' <form id="form1" name="form1" method="post" action="Calcul.php">
  <p>Salariul:
    <label>
    <input type="text" name="sal" value="'.$sal.'" id="sal" />
    </label>
  </p>
  <p>Impozit 
    <label>
    <input type="text" name="impoz" value="'.$impoz.'" id="impoz" />
    </label>
  </p>
  <p>Contributie sanatate
    <label>
    <input type="text" name="csan" value="'.$csan.'" id="csan" />
    </label>
  </p>
  <p>Salariu net 
    <label>
    <input type="text" name="salnet" value="'.$salnet.'" id="salnet" />
    </label>
  </p>
  <p>&nbsp;</p>
  <p>
    <label>
    <input type="submit" name="button" id="button" value="Calcul" />
    </label>
  </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>';
?>

</body>
</html>
