
<?php
 $nm=$_POST['nume'];
 if($nm == "ionela") 
 { require_once("index1.html"); }
 else
   if($nm == "maria")
    { require_once('index2.html'); }
	else
	 {
	  echo 'Site inchis, user neautorizat';
	  }
?>
