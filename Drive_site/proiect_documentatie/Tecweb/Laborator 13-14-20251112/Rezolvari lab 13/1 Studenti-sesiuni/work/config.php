<?php
session_start();
set_time_limit(0);
error_reporting(E_ALL);

// Informatii baza de date

 $AdresaBazaDate = "localhost";
 $UtilizatorBazaDate = "root";
 $ParolaBazaDate = "";
 $NumeBazaDate = "studenti";

 $conexiune = new mysqli($AdresaBazaDate,$UtilizatorBazaDate,$ParolaBazaDate) or die("Nu ma pot conecta la MySQL!");
 mysqli_select_db($conexiune,$NumeBazaDate ) or die("Nu gasesc baza de date");
 
function addentities($data){
   if(trim($data) != ''){
   $data = htmlentities($data, ENT_QUOTES);
   return str_replace('\\', '&#92;', $data);
   } else return $data;
} // End addentities() --------------

?>