<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$sal = $_POST['sal'];
echo $sal.'<br>';
$csan= floor(10.5/100 *$sal);
$impoz= floor(0.16 * ($sal-$csan));
$salnet = $sal - $impoz - $csan;
echo "Persoana primeste un salariu de ".$salnet. " si da un impozit de ". $impoz. "<BR>" ;
echo "Contributia de sananate ".$csan. "<BR>" ;
?>


</body>
</html>
