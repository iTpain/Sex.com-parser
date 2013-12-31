<?php
$dbhost ='localhost';
// username and password to log onto db server
$dbuser ='user';
$dbpass ='password';
// name of database
$dbname='dbname';
// charset
$sqlchar='utf8';
 
 
$db = new PDO ( 'mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass);
$db->query ( 'SET character_set_connection = '.$sqlchar );
$db->query ( 'SET character_set_client = '.$sqlchar );
$db->query ( 'SET character_set_results = '.$sqlchar );
?>