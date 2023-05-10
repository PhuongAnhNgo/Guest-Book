<?php
$fehlermeldung = "";

//Verarbeitung des Bestätigungslinks (GET-Parameter)
if(isset($_GET['benutzer'],$_GET['confirmation']))
{
  $benutzer = $_GET['benutzer'];
  $confirmation = $_GET['confirmation'];

  //DB-Verbindung öffnen
  require("config.php");
  $benutzer = mysqli_real_escape_string($connect, $benutzer);
  $confirmation = mysqli_real_escape_string($connect, $confirmation);

  $sql = "UPDATE egb_benutzer
          SET status = 1
          WHERE benutzer = '$benutzer'
          AND confirmationcode = '$confirmation'";

  mysqli_query($connect, $sql);
  if($error = mysqli_error($connect))  //SQL-Fehler
    die("<p style=\"color:red\">$error</p>");

  //header("Location:gaestebuch_login_db.php");
}

if( isset($_POST['benutzer'],$_POST['email'],$_POST['passwort1'],$_POST['passwort2']) )
{
  $benutzer = trim($_POST['benutzer']);
  $email = trim($_POST['email']);
  $passwort1 = trim($_POST['passwort1']);
  $passwort2 = trim($_POST['passwort2']);

  if($benutzer == "")
    $fehlermeldung .= "Bitte geben Sie einen Benutzernamen ein!<br>\n";
  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    $fehlermeldung .= "Bitte geben Sie eine gültige Emailadresse ein!<br>\n";
  if(strlen($passwort1) < 6)
    $fehlermeldung .= "Bitte geben Sie ein Passwort mit mindestens sechs Zeichen ein!<br>\n";
  //if($passwort1 != $passwort2)
  if(strcmp($passwort1, $passwort2) != 0)
        $fehlermeldung .= "Die eigegebenen Passwörter müssen übereinstimmen!<br>\n";
  if($fehlermeldung == "")
  {
    $hash = md5($passwort1); //alt
    //$hash = password_hash($passwort1, PASSWORD_BCRYPT);//neu

    $ip = $_SERVER['REMOTE_ADDR'];//IP des Benutzers
    $confirmation = md5($benutzer.$ip);//Eindeutiger Code zur Aktivierung
    $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $confirmation_link = "https://$url?benutzer=$benutzer&confirmation=$confirmation";

    //DB-Verbindung öffnen
    require("config.php");
    //SQL-Injection verhindern
    $benutzer = mysqli_real_escape_string($connect, $benutzer);
    $sql = "INSERT egb_benutzer
            SET benutzer = '$benutzer',
                passwort = '$hash',
                email = '$email',
                confirmationcode = '$confirmation'";

    $res = mysqli_query($connect, $sql);//debug
    if(!$res)   //SQL-Fehler ausgeben: Benutzer schon vorhanden?
      die("<p style=\"color:red\">" . mysqli_error($connect) . "</p>");

    //Bestätigungsemail generieren und abschicken
    $mailtext = "You have successfuly registierted in our Guest Book service. Please click this link to confirm your Email: $confirmation_link";
    $header = "Content-type: text/plain; charset=utf8\r\n";
    $b = mail($email,"Gästebuch Registrierung", $mailtext, $header);

    //$fehlermeldung = "<a href=\"$confirmation_link\">$confirmation_link</a>";//debug
    //echo "<p>$mailtext</p>";//debug
  }
}
?>
<!DOCTYPE html>
<html  lang="en"><head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registierung</title>

    

    
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
<h1>Registrieren</h1><br><br>
    <div class="row ">
      
      <div class="col-md-7 col-lg-8">
        
        <form action="registierung.php" method="post" class="needs-validation" novalidate="">
          <div class="row g-3">  
            <div class="col-12">
              <label for="username" class="form-label">Username</label>
              <div class="input-group has-validation">
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" id="username" placeholder="Username" required="" name="benutzer">
              <div class="invalid-feedback">
                  Your username is required.
                </div>
              </div>
            </div>

            <div class="col-12">
              <label for="email" class="form-label">Email </label>
              <input type="email" class="form-control" id="email" placeholder="you@example.com" name="email">
              <div class="invalid-feedback">
                Please enter a valid email address.
              </div>
            </div>      
          </div>
          <hr>
          <div class="row gy-3">
            <div class="col-12">
              <label for="cc-name" class="form-label">Passwort</label>
              <input type="password" class="form-control" id="cc-name" placeholder="" required="" name="passwort1" >
              
              <div class="invalid-feedback">
                Password is required
              </div>
            </div>

            <div>
              <label for="cc-expiration" class="form-label">Password confirmation</label>
              <input type="password" class="form-control" id="cc-expiration" placeholder="" required="" name="passwort2">
              <div class="invalid-feedback">
              Password confirmation is required
              </div>
            </div>

            
          </div>

          <hr class="my-4">

          <button class="w-100 btn btn-primary btn-lg" type="submit" value="Registrieren">Registieren</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="my-5 pt-5 text-body-secondary text-center text-small">
    
    
  </footer>
</div>


    <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

      <script src="js/ex.js"></script>
  

</body><grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration></html>