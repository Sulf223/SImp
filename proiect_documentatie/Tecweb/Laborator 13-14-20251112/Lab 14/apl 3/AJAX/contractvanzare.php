<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contract Vanzare</title>
</head>
<?php
$cnp=$_POST['cnp'];
$pieces=$_POST['checkbox'];
$idmasina="$pieces[0]";
$nrfactura=$_POST['nrfactura'];
$seriefactura=$_POST['seriefactura'];
$datacurenta=date("d-m-Y");
$data=date("Y-m-d");
$garantie=$_POST['garantie'];

include("CONECTARE.php");
$result_persoana=mysqli_query($con,"SELECT * FROM persoane WHERE persoane.CNP='$cnp'");
if (!$result_persoana) {
    die('Invalid query: ' . mysqli_error());
}

$row=mysqli_fetch_array($result_persoana);
$idpersoana=$row['IdPersoana'];



$result_auto=mysqli_query($con,"SELECT informatii_masini.ID_MASINA, marca.MARCA, model.MODEL, informatii_masini.COD_MASINA, informatii_masini.COMBUSTIBIL,  informatii_masini.PRET
FROM informatii_masini, model, marca
WHERE informatii_masini.ID_MASINA='$idmasina' AND informatii_masini.ID_MODEL=model.ID_MODEL AND model.ID_MARCA=marca.ID_MARCA ");
if (!$result_auto) {
    die('Invalid query: ' . mysqli_error());
}
$row2=mysqli_fetch_array($result_auto);

$sql=mysqli_query($con,"INSERT INTO iesiri (ID_MASINA, ID_PERSOANA, SERIE_FACTURA, NR_FACTURA, DATA_IESIRE, GARANTIE)
VALUES
('$idmasina','$idpersoana','$seriefactura','$nrfactura','$data','$garantie')");
if (!$sql) {
    die('Invalid query: ' . mysqli_error());
}
?>
<center>
<div align="center" style="width:650px;height:950px;border:2px solid white;">
<body>

<h2>CONTRACT DE VANZARE-CUMPARARE</h2>
<table width="650" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><b>1. VANZATOR:</b><br /><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PERSOANA FIZICA: ...................................................................<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PERSOANA JURIDICA: <B>SC PARC-AUTO INTERNATIONAL</B><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nr.Registrul Comertului: 12345678, reprezentata prin: <b> POPESCU MARIAN</b><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Domiciliu/Sediu: Hunedoara, str. Independentei, nr.1, bloc - , scara - , ap. - ,<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;judet Hunedoara, cod: 3356478
<h5 align="right">(Stampila, in cazul persoanelor juridice)</h5></td>
</tr>
  <tr>
    <td><b>2. CUMPARATOR:</b><br />
      <br />

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PERSOANA FIZICA:<b> <?php echo '' .$row['Nume']. ''; ?></b><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PERSOANA JURIDICA:...............................................................<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nr.Registrul Comertului:................ , reprezentata prin:..........................................................<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Domiciliu/Sediu: <?php echo '' .$row['Adresa']. ''; ?>
<h5 align="right">(Stampila, in cazul persoanelor juridice)</h5></td>
  <tr>
    <td><b>3. OBIECTUL CONTRACTULUI:</b><br />
      <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VEHICUL MARCA: <B><?php echo '' .$row2['MARCA']. ''; ?></B>, MODEL: <B><?php echo '' .$row2['MODEL']. ''; ?></B>, 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NR.CARTE IDENT: ...................................<BR /><br />

</td>
</tr>
<tr>
<td><b>4. PRET:</b>
&nbsp;&nbsp;<B><?php echo '' .$row2['PRET']. ''; ?> LEI<BR /><BR />
<br />
<br />
<center>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SEMNATURA VANZATOR:......................
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />SEMNATURA CUMPARATOR:...................... <BR />
<BR />
Locul incheierii contractului......................................................................
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data: <?php echo '' .$datacurenta. ''; ?></td>
</tr><tr>
  <td>
</tr>
<tr>
</div>
</body>
</html>