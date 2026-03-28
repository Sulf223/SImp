<?
$user="root";
$password="";
$database="db_vizitatori";
mysql_connect(localhost,$user,$password);
@mysql_select_db($database) or die( "Nu se poate deschide baza de date");
$query="CREATE TABLE `utilizatori` (
  `id` int(11) NOT NULL auto_increment,
  `utilizator` char(60) NOT NULL default '',
  `parola` char(60) NOT NULL default '',
  `nume` char(30) NOT NULL default '',
  `prenume` char(30) NOT NULL default '',
  `varsta` char(3) NOT NULL default '',
  `localitate` char(30) NOT NULL default '',
  PRIMARY KEY  (`id`))";
mysql_query($query);
mysql_close();
?>

