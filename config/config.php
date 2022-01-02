<?php

//Andmebaasi ühendused 
define('HOST', 'localhost'); // Serveri nimi
define('USERNAME', 'root'); // Kasutajanimi
define('PASSWORD', ''); // Parool
define('DBNAME', 'laurakellylumi_phppoll'); // Andmebaasi fail

$dsn = 'mysql:host='.HOST.';dbname='.DBNAME;
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

define('MAXPERPAGE', 8); // Mitu kirjet küsimute tabelis ühel näidatakse
define('MAX_BUTTONS', 5); // Tabeli lehtede sirvimise jaoks max nuppude arv
?>