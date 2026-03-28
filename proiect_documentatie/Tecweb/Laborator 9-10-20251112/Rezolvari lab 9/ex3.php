<html>
<head>
</head>
<body>


<?php
$azi = getdate(); 
$numeLuna = $azi['month']; 
$lunaNumeric = $azi['mon']; 
$ziua = $azi['mday']; 
$anul = $azi['year'];
echo "Data sistemului: $ziua $numeLuna $anul " ."<br>";
echo "Alt format data sistemului: $ziua.$lunaNumeric.$anul <br>";
$dataNasterii ="23 November 1990";
echo "Data nasterii persoanei: ".$dataNasterii ."<br>";
$timestamp = strtotime($dataNasterii); 
$dataNasteriiPers = getdate($timestamp);
$anDataNasteriiPers = $dataNasteriiPers['year']; 
$lunaDataNasteriiPers= $dataNasteriiPers['mon']; 
$ziDataNasteriiPers= $dataNasteriiPers['mday'];
echo "Data preluata de variabile: $ziDataNasteriiPers $lunaDataNasteriiPers $anDataNasteriiPers".'<br>';
echo "Anul curent: $anul".'<br>'; 
echo "Anul nasterii persoanei: $anDataNasteriiPers".'<br>' ;
if($lunaDataNasteriiPers>$lunaNumeric) $v=$anul-$anDataNasteriiPers-1;

else if($lunaDataNasteriiPers==$lunaNumeric) if($ziDataNasteriiPers>$ziua) $v=$anul-$anDataNasteriiPers-1;									
							else $v=$anul-$anDataNasteriiPers;
else $v=$anul-$anDataNasteriiPers;

echo "Varsta pe care o are implinita persoana:" .$v.'<br>';
?>



</body>
</html>

