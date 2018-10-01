<?php
  session_start();
  require_once("./classes/ShopwareHelper.php");
  require_once("./classes/PlentyHelper.php");
  require_once("./classes/LoginHelper.php");

  LoginHelper::set_logged_out_status();

  header('Location: ./login.php');
