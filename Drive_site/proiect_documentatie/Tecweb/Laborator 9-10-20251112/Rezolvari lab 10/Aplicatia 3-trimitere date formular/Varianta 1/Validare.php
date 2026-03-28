 <?php 
if(($_POST['email'] == '') || ($_POST['nume'] == '') || ($_POST['prenume'] == '') || ($_POST['email'] == '')
|| ($_POST['mesaj'] == '') ){
   echo 'Completati campurile corect, nu se accepta campuri goale <br>';
   echo 'Apasati <a href="formular.html"> aici </a> pentru a va intoarce la pagina principala.';
}
else
 if(!is_numeric($_POST['varsta'])){
   echo 'Completati varsta cu o valoare numerica <br>';
   echo 'Apasati <a href="formular.html"> aici </a> pentru a va intoarce la pagina principala.';
   }
  else {
      echo 'Nume: '.$_POST['nume'].' <br>
      Prenume: '.$_POST['prenume'].' <br>
      Varsta: '.$_POST['varsta'].' <br>
      Email : '.$_POST['email'].' <br>';
	  }
?> 

 
