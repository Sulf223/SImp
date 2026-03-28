<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Verificare CNP</title>
</head>

<body>

    <?php

$numar="2910713452680";
      for ($i = 0; $i <=12; $i++) // imparte fiecare cifra a cnp-ului intr-un vector
      {
         $cnp[] = intval($numar[$i]);
      }
      
      $suma = $cnp[0] * 2 + $cnp[1] * 7 + $cnp[2] * 9 + $cnp[3] * 1 + $cnp[4] * 4 + $cnp[5] * 6 + $cnp[6] * 3 + $cnp[7] * 5 + $cnp[8] * 8 + $cnp[9] * 2 + $cnp[10] * 7 + $cnp[11] * 9; //caluleaza o suma (face parte din algoritm)
      
      $rest = $suma % 11; // scoate restul din suma
      
      if (($rest < 10 && $rest == $cnp[12]) || ($rest == 10 && $cnp[12]==1)) // valideaza
        echo "CNP-ul $numar este corect";
      else 
         echo "CNP-ul $numar nu este corect";
   
   ?>
 </body>
</html>
