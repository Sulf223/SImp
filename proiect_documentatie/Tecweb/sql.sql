CREATE TABLE `utilizatori` (
  `id` int(11) NOT NULL auto_increment,
  `utilizator` char(60) NOT NULL default '',
  `parola` char(60) NOT NULL default '',
  `nume` char(30) NOT NULL default '',
  `prenume` char(30) NOT NULL default '',
  `varsta` char(3) NOT NULL default '',
  `localitate` char(30) NOT NULL default '',
  PRIMARY KEY  (`id`));