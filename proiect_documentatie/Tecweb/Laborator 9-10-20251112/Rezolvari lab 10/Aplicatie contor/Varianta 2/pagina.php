<html>
<head><title>Test 5</title></head>
<body>
<?php
//Sesiunile, reprezinta o functionalitate prin care anumite informatii sunt mentinute de la o pagina la alta. O sesiune dureaza atat timp cat //utilizatorul acceseaza un site si se incheie odata cu inchiderea browserului.
if(!isset($_SESSION['numec']))
{
 session_start();
 $_SESSION['numec'] = "POPESCU MARIA";
 $filename ="counter.txt";
//Deschidere fisier citire , fisier de tip text
 $fp= fopen($filename, "r");
//Citire din fisier
 $nr=fread($fp,26); 
//Marire contor
 $nr = (int) $nr + 1;
//Deschidere fisier in scriere
 $fp= fopen($filename, "w");
 $a= fwrite($fp,$nr, 26);
//Inchidere fisier
 fclose($fp);
 }
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
