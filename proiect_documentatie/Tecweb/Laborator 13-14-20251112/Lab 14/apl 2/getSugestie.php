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
$gasit=0;

if ($q !== "") {
  $q = strtolower($q);
  $len=strlen($q);
  foreach($a as $nume) {
    if (stristr($q, substr($nume, 0, $len))) {
	    $vectorNume[] = $nume;
		$gasit=1;
		
      	  
    }
  }
}

// Output "no suggestion" if no hint was found or output correct values
if ($gasit !=0) {
echo "<table border='1' align='left'>
<tr>
<th>Nr crt</th>
<th>Nume</th>
</tr>";
$i=1;
foreach($vectorNume as $nume)
  {
  
  echo "<tr>";
  echo "<td>" . $i. "</td>";
  echo "<td>" . $nume . "</td>";
  echo "</tr>";
  $i++;

  }
echo "</table>";
}
else {
echo "<table border='1' align='left'>
<tr>
<th>Nume</th>
</tr>";
 echo "<tr>";
 echo "<td>Nicio sugestie!</td>";
 echo "</tr>";  
echo "</table>";
}



?>