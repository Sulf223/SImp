 <?php
$nm= $_POST['Nume'];
$pr= $_POST['Pren'];
$ad= $_POST['Adresa'];
echo '
<table width="200" border="1">
  <tr>
    <th>Nume</th>
    <th>Prenume</th>
    <th>Adresa email</th>
  </tr>
  <tr>
    <td>'.$nm.'</td>
    <td>'.$pr.'</td>
    <td>'.$ad.'</td>
  </tr>
</table> ';
?> 
