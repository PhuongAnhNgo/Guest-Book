<?php

//Infos des Datebbanks
$host = "";
$user = "";
$pw = "";
$db = "";

$connect = mysqli_connect($host, $user, $pw, $db);

if(!$connect){
  die("Keine Verbindung zum Datenbankserver!");
}

mysqli_set_charset($connect, "utf8");
?>
