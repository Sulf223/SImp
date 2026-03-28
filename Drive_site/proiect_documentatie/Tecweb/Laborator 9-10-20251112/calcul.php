<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
 $si= $_POST['sal'];
 $va= $_POST['v'];
 if($va <3) 
     $sv=0;
	 else
	   if($va <10)
	       $sv=3/100 *$si;
		else
		   if($va <25)
	          $sv=10/100 *$si;  
			    else
				  $sv= 25/100 *$si;
	$sb = $si +$sv;
	echo 'Salariul brut <b>'.$sb ;			   
?>
</body>
</html>
