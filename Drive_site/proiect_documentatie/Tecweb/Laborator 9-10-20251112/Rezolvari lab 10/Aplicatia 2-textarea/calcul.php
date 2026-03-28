 <?php
$a = $_POST['Note'];
echo $a;
$text = str_replace("\r", "", $_POST['Note']);//pentru a procesa enter-ul
$sirtext = explode("\n", $text);// creaza vectorul de note

echo '<table border="1" width="200" >';
echo '<tr>  <th> Note  </th> </tr> ';
foreach ($sirtext as $v) 
  echo '<tr>  <td>' .$v.' </td> </tr>';
echo '</table>'; 
$media=0;
$nr=0;
foreach ($sirtext as $v) {$media=$media+$v;
	$nr=$nr+1;
}

echo "Media notelor este: ".$media/$nr;
?> 
