<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
 $nm=$_POST['user'];
 if($nm == "user1") 
 { require_once("index1.html"); }
 else
   if($nm == "user2")
    { require_once('index2.html'); }
	else
	 {
	  echo 'Site inchis, user neautorizat';
	  }
?>
</body>
</html>
