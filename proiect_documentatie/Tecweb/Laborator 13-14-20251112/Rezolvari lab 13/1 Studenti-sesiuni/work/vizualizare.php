<?php
require_once('config.php');
include_once("pas.php");
$cerereSQL = 'SELECT * FROM `an1`';
$rezultat = mysqli_query( $conexiune,$cerereSQL);

if (!$rezultat) {
echo "Eroare la citirea datelor din db."; die();
}
else {

while($rand = mysqli_fetch_row($rezultat))
{
echo '<b>Student:</b> '.$rand[1].' <br>
<b>Data nasterii:</b> '.$rand[2].' <br>
<b>An inscriere:</b> '.$rand[3].'<br>
<b>E-mail:</b> '.$rand[4].' <br>
<b>Telefon:</b> '.$rand[5].' <br>
<b>Mobil:</b> '.$rand[6].' <br>
<b>Observatii:</b> '.$rand[7].' <br><br>';
}
};
?>