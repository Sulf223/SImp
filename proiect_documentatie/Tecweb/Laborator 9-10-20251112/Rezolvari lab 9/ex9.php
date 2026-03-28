<html>
<head>
</head>
<body>


<?php
echo '<form name="form1" method="post" action="">';
echo 'Nume  candidat';
echo '<input name="nm" type="text" ><br />';
echo 'Prenume  candidat';
echo '<input name="pr" type="text" ><br />';
echo 'Alege cursul<select name="select" id="select">
    <option>Mate</option>
    <option>Fizica</option>
    <option>Info</option>
  </select> <br>' ;
echo 'Alege sexul <label>
        <input type="radio" name="RadioGroup1" value="1" id="RadioGroup1_0" />
        Fem</label>';
echo '<label>
        <input type="radio" name="RadioGroup1" value="2" id="RadioGroup1_1" />
        Masc</label> <br>';		  
echo 'Apasa <input type="submit" name="button" value="Inscriere" />';
echo '</form>'; 
?>










</body>
</html>

