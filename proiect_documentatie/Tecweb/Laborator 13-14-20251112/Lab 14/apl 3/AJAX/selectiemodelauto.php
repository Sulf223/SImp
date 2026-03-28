<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Persoana</title>
<style type="text/css">
<!--
body {
	background-color: #9FF;
}
-->
</style>
<script>
function CautaModel(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","cautamodel.php?q="+str,true);
xmlhttp.send();
}
function ArataAuto(str)
{
if (str=="")
  {
  document.getElementById("txtHint2").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","arata_auto.php?q="+str,true);
xmlhttp.send();
}
function validare()
{
	//validare checkbox
var chks = document.getElementsByName('checkbox[]');
var hasChecked = false;
var nr=0;
for (var i = 0; i < chks.length; i++)
{
if (chks[i].checked)
{
	nr=nr+1;
}
}
if (nr == 0)
alert("Selecteaza Modelul!");

else { 
				document.form1.action = "contractvanzare.PHP"
				document.form1.submit();             // Submit the page

}
}

</script>
</head>

<body>
<h1 align="center">PERSOANA SELECTATA</h1><br />
<?php
$cnp=$_POST['cnp2'];
include("CONECTARE.PHP");
$result=mysqli_query($con,"SELECT * FROM persoane WHERE persoane.CNP='$cnp' ");
$row=mysqli_fetch_array($result);
?>
<form enctype="multipart/form-data" method="post" name="form1" id="form1">
<table align="center" width="625" >
 </tr>
     <tr>
       <td width="141">Nume Prenume</td>
    <td width="180"><label for="numeprenume"></label>
      <input name="numeprenume" type="text" id="numeprenume" value="<?php echo '' .$row['Nume']. ''; ?>" size="30" readonly="readonly"></td>
    <td width="140">CNP:</td>
    <td width="144"><input name="cnp" type="text" readonly id="cnp" value="<?php echo '' .$row['CNP']. ''; ?>" size="13" maxlength="13"></td>
       <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
</table>
<input name="idpersoana" type="hidden" value="<?php echo '' .$row['IdPersoana']. ''; ?>">
<h1 align="center">SELECTIE MODEL</h1><br />
<center>
<table width="461">
  <tr>
    <td width="190" align="right">SELECTEAZA MARCA</td>
    <td width="255"><select onClick="CautaModel(this.value)" name="marca" >
  <?php
echo $cnp;
$sql="SELECT * FROM  marca ORDER BY ID_MARCA ASC";
$resursa=mysqli_query($con,$sql);
$IdMarca='';

while($row=mysqli_fetch_array($resursa))
{
	echo '<option  ' . ($IdMarca==$row['ID_MARCA'] ? 'selected' : '') . ' value="'.$row['ID_MARCA'].'">'.$row['MARCA'].'</option>'; 
 
}

?>
</select></td>
    </tr>
  <tr>
  <td align="right">SELECTEAZA MODELUL</td>
  <td><select onClick="ArataAuto(this.value)" name="model" id="txtHint">
  </select></td>
  </tr>
</table>
<br>
<div align="center" id="txtHint2"></div><br>
Serie Factura: <input name="seriefactura" type="text" size="5">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Nr. Factura: <input name="nrfactura" type="text" size="10">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Garantie(ani): <input name="garantie" type="text" size="3"><br><br>
<input name="finalizare" type="button" value="Finalizare Contract" onClick="validare()">
</center>


</form>
</body>
</html>
