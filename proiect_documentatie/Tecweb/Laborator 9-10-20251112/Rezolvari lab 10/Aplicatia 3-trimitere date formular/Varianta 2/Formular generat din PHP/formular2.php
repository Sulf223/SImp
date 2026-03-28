<html>
<head>
<title>Formular de contact</title>
</head>
<body>
<?php
if(!isset($_POST['nume'])) $_POST['nume']='';
if(!isset($_POST['prenume'])) $_POST['prenume']='';
if(!isset($_POST['email'])) $_POST['email']='';
if(!isset($_POST['varsta'])) $_POST['varsta']='';

	
	 if(isset($_POST['Trimite'])) {
           if(($_POST['email'] == '') || ($_POST['nume'] == '') || ($_POST['prenume'] == '') || ($_POST['email'] == '')
               || ($_POST['mesaj'] == ''))	   
                 { echo 'Completati campurile corect, nu se accepta campuri goale <br>'; }
    	   if(!is_numeric($_POST['varsta']))
            { echo 'Completati varsta cu o valoare numerica <br>';}
	   
       }
	   
	    if(isset($_POST['Reseteaza'])){
		$_POST['nume']='';
 $_POST['prenume']='';
$_POST['email']='';
 $_POST['varsta']='';
 }

echo '<form action="formular2.php" method="post">

  <p>
    Nume: 
    <input type="text" name="nume" value="'.$_POST['nume'].'" id="nume" > 
    <br>
    Prenume: 
  <input type="text" name="prenume" value="'.$_POST['prenume'].'" id="prenume" > 
  </p>
  <p>Email 
    <input type="text" name="email" value="'.$_POST['email'].'" id="email" >
    <br>
    Varsta: 
    <input type="text" name="varsta" value="'.$_POST['varsta'].'" id="varsta"> 
    <br>
    Comentariu: 
    <textarea name="mesaj" cols="60" rows="6"></textarea> 
    <br>
    <br>
    
    <input type="submit" name="Trimite" value="Trimite">
    <input type="submit" name="Reseteaza" value="Reseteaza">
    </p>
</form>';

?>

 </body> </html>

