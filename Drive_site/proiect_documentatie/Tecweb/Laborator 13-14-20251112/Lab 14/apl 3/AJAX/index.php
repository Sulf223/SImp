<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cautare Persoana</title>
<style type="text/css">
<!--
body {
	background-color: #9FF;
}
-->
</style></head>
<script language="JavaScript" type="text/JavaScript">

function VerificaCNP() {
var cnp=form1.cnp.value;
var corect=true;
if (cnp.length!=13) {
alert("Codul Numeric Personal nu are 13 caractere!");
corect=false;
}
cnp = cnp.split("");
var suma = cnp[0]*2+cnp[1]*7+cnp[2]*9+cnp[3]*1+cnp[4]*4+cnp[5]*6+cnp[6]*3+cnp[7]*5+cnp[8]*8+cnp[9]*2+cnp[10]*7+cnp[11]*9;
if ((suma%11<10 && suma%11 == cnp[12])||(suma%11==10&&cnp[12]==1))  {
corect=true;
} else {
alert("Codul Numeric Personal este invalid!");
corect=false;
}
return corect;
}
function CautarePersoana()
{ 

	document.form1.action = "index.PHP"
	document.form1.submit();
}
function VerificareInformatii()
{var ok;
 var corect=VerificaCNP();
 if (corect==true) corect=CautarePersoana();
}

function NuExista()
{
	alert("Aceasta persoana nu exista baza de date");
	window.history.go(-1);
}

function SelectieModelAuto()
{
	document.form2.action = "selectiemodelauto.PHP"
	document.form2.submit();
	
}
</script>
<body>
<h1 align="center"> CAUTARE PERSOANA</h1><br />
<form enctype="multipart/form-data" method="post" name="form1" id="form1">
<table align="center" width="625" >
 </tr>
     <tr>
       <td align="center" width="141">CNP:</td>
 <tr>
       <td align="center" width="144"><input name="cnp" type="text" id="cnp" size="17" maxlength="13"></td>
 
  </table>
<br /><br /> <center>
  <input name="cautare" type="button" value="Cautare" onClick="VerificareInformatii()" id="cautare" />
<br /><br /></center>
</form>
<?php
include("CONECTARE.PHP");
if(isset($_POST['cnp'])) 
 {  	 $cnp=($_POST['cnp']);
 
 ?>
<form enctype="multipart/form-data" method="post" name="form2" id="form2">
<input name="cnp2" type="hidden" value="<?php echo '' .$cnp. ''; ?>">
</form>
<?php
 		 
		$query="SELECT * FROM persoane WHERE CNP='$cnp';" ;
		$result=mysqli_query($con,$query);
		$row=mysqli_fetch_array($result);
		$num=mysqli_num_rows($result);		
		if ( $num == 0 )
		
{

echo '<script language="javascript"> NuExista() </script>';
		}
		else {
			


echo '<script language="javascript"> SelectieModelAuto() </script>';	
 }
 }

mysqli_close($con)
?>
</body>
</html>
