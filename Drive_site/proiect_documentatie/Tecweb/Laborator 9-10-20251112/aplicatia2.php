<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$nume = array("Pop","Dop","Ionescu","Georgescu", "Mihai");
$varsta = array("31","28","31","30", "31");
$vechime = array("31","28","31","30", "31");
echo '<table border="1" width="200" >';
echo '<tr> <th> Nr crt </th> <th> Nume persoana </th> <th> Varsta </th> <th> Vechime </th> </tr> ';
$nr=1;
foreach ($nume as $nm)
{
echo '<tr> <td>' .$nr.' </td> <td>' .$nm.' </td> <td>' .$varsta[$nr-1].' </td> <td>' .$vechime[$nr-1].' </td></tr>';
$nr++;
}
echo '</table>';
?>
</body>
</html>
