<?php
  session_start();
  require_once("./classes/ShopwareHelper.php");
  require_once("./classes/PlentyHelper.php");
  require_once("./classes/LoginHelper.php");
  require_once("./classes/CliHelper.php");
  require_once("./classes/Database.php");
  require_once("./config.php");

  /* header für alle files ... muss als aller erste geladen werden */
  $path     = $_SERVER['REQUEST_URI'];
  $filename = basename($path);
  $filename = substr($filename, 0, strpos($filename, '.'));

  if (!LoginHelper::check_logged_in() && $filename != "login") {
    // wenn nicht eingeloggt -> redirect zur login.php
    header('Location: ./login.php');
  }

?>
<!DOCTYPE html>
<html lang="de">
  <head>
      <title>PSC7 Helper</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" type="image/vnd.microsoft.icon" href="./inc/psc7-helper.ico">

      <!-- CSS -->
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
      <link rel="stylesheet" href="./css/custom-bootstrap.css">
      <link rel="stylesheet" href="./css/custom.css">
      <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
      <!-- /CSS -->

      <!-- JS -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
      <!-- /JS -->

  </head>
  <body>
    <!--
    <div class="jumbotron text-center" style="margin-bottom:0">
      <h1><img src="./inc/img/psc7-helper.png" style="height:50px">PSC7Helper</h1>
      <p>Ein Plentymarkets-Community Projekt</p>
    </div>
    -->
    <div style="text-align: center;">
      <nav class="navbar navbar-expand-sm bg-dark navbar-dark navbar-inverse" style="margin-bottom: 0px;">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./dashboard.php"><img src="./inc/img/psc7-helper.png" style="height: 100%; margin-bottom: 5px;">PSC7Helper</a>
          </div>
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <?php if (LoginHelper::check_logged_in()) { ?>
                <!-- ist eingeloggt -->
                <li <?php if ($filename == "dashboard") { echo ' class="active"'; } ?>>
                  <a href="./dashboard.php">Dashboard</a>
                </li>
                <li <?php if ($filename == "connector-page") { echo ' class="active"'; } ?>><a href="./connector-page.php">Connector-Befehle</a></li>
                <li class="dropdown<?php if ($filename == "ordercheck" || $filename == "itemcheck" || $filename == "suppliercheck") { echo ' active'; } ?>">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Überprüfung</a>
                  <ul class="dropdown-menu">
                    <li <?php if ($filename == "ordercheck")    { echo ' class="active"'; } ?>><a href="./ordercheck.php">Bestellungen</a></li>
                    <li <?php if ($filename == "itemcheck")     { echo ' class="active"'; } ?>><a href="./itemcheck.php">Artikel</a></li>
                      <li <?php if ($filename == "itemchecker")     { echo ' class="active"'; } ?>><a href="./itemchecker.php">Artikel (ohne JS)</a></li>
                    <li <?php if ($filename == "suppliercheck") { echo ' class="active"'; } ?>><a href="./suppliercheck.php">Hersteller</a></li>
                  </ul>
                </li>
                <li <?php if ($filename == "settings") { echo ' class="active"'; } ?>><a href="./settings.php">Einstellungen</a></li>
                <li <?php if ($filename == "logout") { echo ' class="active"'; } ?>><a href="./logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>



              <?php } else { ?>
                <!-- nicht eingeloggt -->
                <li <?php if ($filename == "dashboard") { echo ' class="active"'; } ?>>
                  <a href="./login.php">Login</a>
                </li>
              <?php } ?>
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>
    </div>
