<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body> 
<?php
	if(!isset($_POST['n1'])){$n1='';
		$medgen='';
		$rez='';}
	if(!isset($_POST['n2'])){ $n2='';
		$medgen='';
		$rez='';}
	if(!isset($_POST['t'])) {$teza='';
		$medgen='';
		$rez='';}
	
	
	
	 if(isset($_POST['Calcul'])) 
	
	
	 
	 if(($_POST['n1']=="")|| ($_POST['n2']=="")||($_POST['t']==""))
{
echo "Nu ati introdus toate informatiile necesare!" ;
$n1='';
$n2='';
$teza='';
		$medgen='';
		$rez='';

}
else if(isset($_POST['n1'])&& isset($_POST['n2'])&& isset($_POST['t']))
{
		$n1= $_POST['n1'];
		$n2= $_POST['n2'];
		$teza= $_POST['t'];
		$med = ($n1+$n2)/2;
		$medgen= (3* $med + $teza)/4;
		if($medgen>=5)
		  $rez= 'promovat';
		else
		  $rez= 'nepromovat';
	}	  
?>


<form id="form1" name="form1" method="post" action="formular.php">
  <p>
    <label>
    Nota 1 
    <input type="text" name="n1" id="n1"  value="<?php echo $n1; ?>" />
    </label>
  </p>
  <p>Nota 2 
    <label>
    <input type="text" name="n2" id="n2" value="<?php echo $n2; ?>"/>
    </label>
  </p>
  <p>
    <label>
    Teza
    <input type="text" name="t" id="t"  value="<?php echo $teza; ?>"/>
</label>
  </p>
  <p>Medie calculata 
    <label>
    <input type="text" name="medie" id="medie"  value="<?php echo $medgen; ?>"   />
    </label>
  </p>
  <p>Rezultat 
    <label>
    <input type="text" name="rez" id="rez" value="<?php echo $rez; ?>"  />
    </label>
  </p>
  <p>
    <label>
    <input type="submit" name="Calcul" id="Calcul" value="Calcul" />
    </label><label>
    <input type="reset" name="Reset" id="button" value="Reset" />
    </label>
  </p>
  <p>&nbsp;</p>
</form>
</body>
</html>
