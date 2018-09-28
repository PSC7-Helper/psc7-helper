<?php
/**
 * Created by PhpStorm.
 * User: f.wehrhausen
 * Date: 14.06.2018
 * Time: 15:06
 */

session_start();
session_destroy();
unset($_SESSION['userID']);

header('Location: ./?msg=logout');
