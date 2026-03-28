<?php
// Vector static cu nume de baieti
$a = array("Abel","Achim","Adam","Adelin","Adrian","Andrei",
"Albert","Bogdan","Caius","Ciprian","Codrin","Constantin",
"Cosmin","Costin","Cristian","Calin","Catalin","Damian",
"Darius","David","Denis","Dragos","Eduard","Eric","Fabian",
"Filip","Flavius","Florin","Horia","Iuliu","Laurentiu",
"Luca","Lucian","Matei","Mihai","Mihnea","Narcis","Natanael",
"Nichita","Nicolae","Nicolas","Noah","Octavian","Ovidiu",
"Pavel","Rares","Raul","Robert","Razvan","Samuel",
"Sebastian","Sorin","Stefan","Teofil","Victor","Vlad",
"Vladimir","Yanis", "Zian");

// se obtine parametrul q de la URL
$q = $_REQUEST["q"];

$num = "";
$gasit=0;
// lookup all hints from array if $q is different from ""
if ($q !== "") {
  $q = strtolower($q);
  $len=strlen($q);
  foreach($a as $nume) {
    if (stristr($q, substr($nume, 0, $len))) {
 
	  echo $nume."\n";
	  $gasit=1;	  
    }
  }
}

// Output "no suggestion" if no hint was found or output correct values
if ($gasit ==0) echo "Nicio sugestie!";

?>