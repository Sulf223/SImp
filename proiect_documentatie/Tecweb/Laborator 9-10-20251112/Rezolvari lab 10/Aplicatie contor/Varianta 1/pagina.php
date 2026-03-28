<html>
<head><title>Test 5</title></head>
<body>
<?php
$filename ="counter.txt";
//Deschidere fisier citire , fisier de tip text
$fp= fopen($filename, "r");
//Citire din fisier 
//string fread ( resource $handle , int $length )   
//handle  O resursa de tip indicator în sistemul de fi?iere care este în mod tipic creata utilizând fopen().

//length Up to length number of bytes read.

$nr=fread($fp,filesize($filename));
//Marire contor
$nr = (int) $nr + 1;
//Deschidere fisier in scriere
$fp= fopen($filename, "w");
$a= fwrite($fp,$nr);
//Inchidere fisier
fclose($fp);
?>

<form name="form1" method="post"  >
  <p>Nr de viz
    <input type="text" name="cont" value="<?php echo $nr ?>">
    <br>
    <br>
    <input type="submit" name="Submit" value="Submit">
    <input name="Cancel" type="reset" id="Cancel" value="Cancel">
  </p>
</form>
</body>
</html>
