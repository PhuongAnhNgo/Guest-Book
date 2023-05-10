<?php
  //Zugriffsschutz für neue Gästebuchbeitrag
  session_start();
  if(!isset( $_SESSION['benutzer_id'])) //== nicht eingeloggt
    header("Location:login.php");

  //var_dump($_POST)  //Formulartest
  $fehlermeldung = "";
  if( isset($_POST['beitrag'])) //Check if variables are set or NULL
  {
    $beitrag  = trim($_POST['beitrag']);  //trim xoa dau cach truoc va sau di

    //Eingabeüberprüfung
    if($beitrag == ""){
      $fehlermeldung = $fehlermeldung."<br>Bitte geben Sie einen Beitrag ein!"; //Die Punkt "." <=> stringconcat
    }

    //Wenn keine Fehler, Daten in Datei schreiben
    if($fehlermeldung == ""){
      $zeitstempel = date("d.m.Y H:i:s");
      //To Do: Check
      $betrag = preg_replace("/(\r\n|\n|\r)/","<br>", $beitrag);

      //1. Datenbankverbindung
      require("config.php");  //-> $connect

      //Benutzer festgelegt auf den eingeloggten Benutzer 
      $benutzer_id = $_SESSION['benutzer_id'];

      //2. SQL-Statement: INSERT
      $sql = "INSERT egb_gaestebuch
              SET benutzer_id = '$benutzer_id',
              beitrag = '$beitrag' "; //muss ' haben auch wenn $beitrag eine Zeichenkette ist'

      mysqli_query($connect, $sql);

      #Fehlerausgabe
      if($sql_fehler = mysqli_error($connect)){
        echo $sql_fehler;
      }

      $anzahl = mysqli_affected_rows($connect);
      echo "<p> Anzahl der eingefügten Datensätze: $anzahl </p>";
      //file_put_contents("gaestebuch.txt","$benutzer::$beitrag::$zeitstempel\n", FILE_APPEND);

      //Weiterleitung
      header("location:gaestebuch_db.php");
    }
  } //end isset
?>

<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Posts</title>  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- Favicons -->
<link rel="apple-touch-icon" href="/docs/5.3/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="/docs/5.3/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="/docs/5.3/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
<link rel="icon" href="/docs/5.3/assets/img/favicons/favicon.ico">
<meta name="theme-color" content="#712cf9">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }
      .bd-mode-toggle {
        z-index: 1500;
      }
    </style>    
    <!-- Custom styles for this template -->
  
  </head>
  <body class="bg-body-tertiary" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed="">
  <?php
    include("new_menu.php");
   //wenn nicht leer bzw. Fehler aufgetreten ist
    if(!empty($fehlermeldung))
      echo "<p style=\"color:red\">$fehlermeldung</p>";
   ?>
<div class="container">
<main>
    <br><br>
<h1>Add New Post</h1><br><br>
    <div class="row ">
      <div class="col-md-7 col-lg-8">        
        <form action="add_post.php" method="post" class="needs-validation" novalidate="">
       
          <div class="row gy-3">
            <div class="col-12">
            <label for="content" class="form-label">Write your content</label>
            <textarea class="form-control" name = "beitrag" id="content" maxlength = "500" rows="4"></textarea><br>
            </div>
          </div>
          <hr class="my-4">
          <button class="w-100 btn btn-primary btn-lg" type="submit" value = "Eintragen">Post</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="my-5 pt-5 text-body-secondary text-center text-small">
    
    
  </footer>
</div>


    <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

      <script src="js/ex.js"></script>
  

<grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration></body></html>