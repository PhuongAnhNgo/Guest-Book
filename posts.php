<?php
  $ausgabe = "";

  //Datenbankverbindung
  require("config.php");  //Wenn config.php nicht gibt, programmsabruch + error!

  //2. SQL-Statement formulieren und abschicken
  #Statement
  $sql = "SELECT benutzer, beitrag,
          DATE_FORMAT(g.zeitstempel, '%d.%m.%Y') AS datum,
          DATE_FORMAT(g.zeitstempel, '%H:%i:%s') AS uhrzeit
          FROM egb_benutzer b, egb_gaestebuch g
          WHERE b.id = g.benutzer_id
          ORDER BY g.zeitstempel DESC";

  //SQL Statement zu DB abschicken
  $res = mysqli_query($connect, $sql);

  #Fehlerausgabe
  if(!$res){
    echo mysqli_error($connect);
  }

  $num_rows = mysqli_num_rows($res);
  if($num_rows == 0){
    $ausgabe = "<p>Noch kein Eintrag im Gästebuch!</p>";
  }


  //3. Abfrage-Ergebnis ausgeben
  while ($row = mysqli_fetch_assoc($res)){    #Jeder Durchlauf eine Zeile ablesen
    //und in jedem Schleifendurchlauf die Zeile in:
    // benutzer, beitrag und zeitstempel zerschneiden

    $benutzer = $row['benutzer'];
    $beitrag = $row['beitrag'];
    $datum = $row['datum'];
    $uhrzeit = $row['uhrzeit'];

    $benutzer = htmlspecialchars($benutzer);
    $beitrag = htmlspecialchars($beitrag);
    $beitrag = preg_replace("/&lt;br&gt;/","<br>",$beitrag);

    $ausgabe .=
    "<div id=\"beitrag\">
          <div id=\"head\">$benutzer schrieb am $datum um $uhrzeit</div>
          <div id=\"body\">$beitrag</div>
    </div>\n";

  }

?>

<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>  
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
    <link rel="stylesheet" href="style.css">
  </head>
  <body class="bg-body-tertiary" data-new-gr-c-s-check-loaded="8.906.0" data-gr-ext-installed="">
  <?php
    include("new_menu.php");
   //wenn nicht leer bzw. Fehler aufgetreten ist
   if(!empty($ausgabe))
   echo "<p style=\"color:red\">$ausgabe</p>\n";
   ?>

    <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

<grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration></body></html>