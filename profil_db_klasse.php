<?php
class ProfilDB {
  private $connect;
  private $benutzer_id;

  function __construct($benutzer_id) {
    require("config.php"); //DB-Verbindung mit mysqli_connect herstellen um $connect- Attribute zu holen
    $this->connect = $connect;
    $this->benutzer_id = $benutzer_id;
  }

  function getProfil() {
    //Alle notwendige Informationen aus der Tabelle egb_benutzer lesen
    $sql = "SELECT benutzer, email, image, zeitstempel
            FROM egb_benutzer
            WHERE id = $this->benutzer_id";
    $result = mysqli_query($this->connect, $sql); //SQL_Statement zur DB abschicken

    #Bei SQL-Fehler: Fehlerausgabe
    if($sql_fehler = mysqli_error($this->connect)){
      echo $sql_fehler;
    }

    $row = mysqli_fetch_assoc($result); //Result ist ein Spalten-Array

    return $row;
  }

  function getNumArticles(){
    //Ermittelt die Anzahl der Beiträge, die der Benutzer bereits geschrieben hat aus egb_gaestebuch und gibt die Zahl zurück
    $sql = "SELECT beitrag
            FROM egb_gaestebuch
            WHERE benutzer_id = $this->benutzer_id";
    $result = mysqli_query($this->connect, $sql);

    #Fehlerausgabe
    if($sql_fehler = mysqli_error($this->connect)){
      echo $sql_fehler;
    }

    $anzahl = mysqli_num_rows($result);

    return $anzahl;
  }


}


?>
