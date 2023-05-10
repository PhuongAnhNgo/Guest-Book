<?php
session_start();

//Logout
if(isset($_GET['logout']))
{
  // Löschen aller Session-Variablen.
  $_SESSION = array();
  // Session-Cookie löschen
  // Achtung: Damit wird die Session gelöscht, nicht nur die Session-Daten!
  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params["path"],
          $params["domain"], $params["secure"], $params["httponly"]
      );
  }
  //löschen der Session.
  session_destroy();
}



//Login
$fehlermeldung = "";
//var_dump($_POST);
if( isset($_POST['benutzer']) && isset($_POST['passwort']) )
{

  $benutzer = trim($_POST['benutzer']);
  $passwort = trim($_POST['passwort']);

  //Eingabeüberprüfung
  if($benutzer == "")
    $fehlermeldung .= "Bitte geben Sie einen Benutzernamen ein!<br>";

  if($passwort == "")
      $fehlermeldung .= "Bitte geben Sie ein Passwort ein!";


  //Beide Felder ausgefüllt
  if($fehlermeldung == "")
  {
    //var_dump($_POST);
    //HTML-Injection verhindern
    $benutzer = htmlspecialchars($benutzer);
    $passwort_md5 = md5($passwort);

    //1. Datenbankverbindung aufbauen
    require("config.php");

    //SQL-Injection verhindern
    $benutzer = mysqli_real_escape_string($connect, $benutzer);

    //2. SQL-Statement ausführen
    $sql = "SELECT id
            FROM egb_benutzer
            WHERE benutzer = '$benutzer'
            AND passwort = '$passwort_md5'
            AND status = '1'";
    $resultset = mysqli_query($connect, $sql);

    if(!$resultset)   //SQL-Fehler ausgeben
      die("<p style=\"color:red\">" . mysqli_error($connect) . "</p>");

    $anzahl = mysqli_num_rows($resultset);

    if($anzahl < 1)
      $fehlermeldung .= "Benutzername oder Passwort falsch!";
    else {
      // 3. Abfrageergebnis verarbeiten
      $row = mysqli_fetch_assoc($resultset);
      $benutzer_id = $row['id'];

      //benutzer_id in Session-Variable speichern
      $_SESSION['benutzer_id'] = $benutzer_id;
      $_SESSION['benutzer'] = $benutzer;
      //var_dump($_SESSION);
      header("Location:add_post.php");
    }
  }
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
<h1>Login</h1><br><br>
    <div class="row ">
      
      <div class="col-md-7 col-lg-8">
        
        <form action="login.php" method="post" class="needs-validation" novalidate="">
          <div class="row g-3">  
            <div class="col-12">
              <label for="username" class="form-label">Username</label>
              <div class="input-group has-validation">
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" id="username" name="benutzer" placeholder="Username" required="" name="benutzer">
              <div class="invalid-feedback">
                  Your username is required.
                </div>
              </div>
            </div>

                  
          </div>
          <hr>
          <div class="row gy-3">
            <div class="col-12">
              <label for="cc-name" class="form-label">Passwort</label>
              <input type="password" class="form-control" name="passwort" id="cc-name" placeholder="" required="" name="passwort1">
              
              <div class="invalid-feedback">
                Password is required
              </div>
            </div>
          </div>

          <hr class="my-4">

          <button class="w-100 btn btn-primary btn-lg" type="submit" value="Registrieren">Login</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="my-5 pt-5 text-body-secondary text-center text-small">
    
    
  </footer>
</div>


    <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

  

<grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration></body></html>