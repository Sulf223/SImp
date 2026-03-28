<html>
<head>
</head>
<body>


<?php
$a = array("Ianuarie","Februarie","Martie","Aprilie", "Mai", "Iunie","Iulie"); 
$zile = array("31","28","31","30", "31", "30","31"); 
echo '<table border="1" width="200" >';
echo '<tr>  <th> Nr crt </th> <th> Denumire luna </th> <th> Nr zile </th> </tr> ';
$nr=1;
while ($nr<=7) 
  {
   echo '<tr>  <td>' .$nr.' </td> <td>' .$a[$nr-1].' </td> <td>' .$zile[$nr-1].' </td>  </tr>';
$nr++;
}
echo '</table>'; 
?>








</body>
</html>

