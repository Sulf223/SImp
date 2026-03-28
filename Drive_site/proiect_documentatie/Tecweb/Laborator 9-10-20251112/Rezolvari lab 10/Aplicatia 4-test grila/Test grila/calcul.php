 <?php 
$P=1;
 if(isset($_POST['Rg1']))
{$R1=$_POST['Rg1'];
 if ($R1==2)
  $P=$P+4.5;}
 
if(isset($_POST['cb2'])){
$R2=$_POST['cb2'];
 if ($R2==true)
  $P=$P + 2.25;
  }
echo "Punctajul obtinut este " .$P.'<br>Apasati <a href="Grila.html">aici</a> pentru a va intoarce la pagina principala.';
 
?>