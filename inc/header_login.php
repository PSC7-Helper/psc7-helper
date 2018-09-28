<?php
error_reporting(E_ALL);
session_start();
require_once("./classes/ShopwareHelper.php");
require_once("./classes/Database.php");
require_once("./classes/LoginHelper.php");



?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>PSC7 Helper</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/vnd.microsoft.icon" href="./inc/psc7-helper.ico">

    <!-- CSS
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />-->
    <link rel="stylesheet" href="./css/custom-bootstrap.css">
    <link rel="stylesheet" href="./css/bootstrap-select.css">
    <link rel="stylesheet" href="./css/custom.css">

    <!-- /CSS -->

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!--<script src="./js/bootstrap-select.js"></script>
    <!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <!-- /JS -->

</head>
<body>
<div class="jumbotron text-center" style="margin-bottom:0">
    <h1><img src="./inc/img/psc7-helper.png" style="height:50px">PSC7Helper</h1>
    <p>Ein Plentymarkets-Community Projekt</p>
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark navbar-inverse" style="margin-bottom: 20px;">
    <div class="container">
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a class="nav-link" href="../">Zurück zum Shop</a>
            </li>
        </ul>

</nav>