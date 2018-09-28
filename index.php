<?php
/**
 * Include database information (pdo)
 */
require_once("./inc/header_login.php");
$error = "";

if (isset($_GET["login"])) {
    if (isset($_POST["user"])){
        $user = $_POST["user"];
    }else{
        $error .= "bitte Benutzername eingeben! ";
    }
    if (isset($_POST["pw"])){
        $pw = $_POST["pw"];
    }else{
        $error .= "bitte Passwort eingeben! ";
    }

    //Eigentlicher Login danach weiterleitung zum Dashboard
    if (LoginHelper::check_login($user, $pw)) {
        //$_SESSION['userID'] = $dbUserID;
        header('Location: ./dashboard.php');
    }else{
        $error .= "Login nicht erfolgreich! ";
    }
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-sm-0"></div>
        <div class="col-md-16 col-sm-24">
            <?php
            $msg = "";
            $class = "alert-warning";
            $showAlert = false;

            if (!empty($error) ){
                $msg = $error;
                $class = "alert-warning";
                $showAlert = true;

            }else if (isset($_GET["msg"])){
                $status = $_GET["msg"];
                $showAlert = true;
                if($status == "notLoggedIn"){
                    $msg = "Sie müssen sich zuerst einloggen";
                    $class = "alert-warning";

                }elseif ($status == "logout"){
                    $msg = "Sie wurden erfolgreich ausgeloggt";
                }
            }

            if ($showAlert){
                echo "
                    <div class=\"alert ".$class." alert-dismissible\">
                      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>.
                      $msg.
                    </div>
                ";
            }

            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-0 col-md-6">

        </div>
        <div class="col-sm-24 col-md-12">
            <h1>Anmelden</h1>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="/psc7-helper/index.php?login=1" method="post">
                        <div class="form-group">
                            <label for="email">Benutzername:</label>
                            <input type="text" class="form-control" id="user" name="user">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Passwort:</label>
                            <input type="password" class="form-control" id="pwd" name="pw">
                        </div>

                        <button type="submit" class="btn btn-default">Anmelden</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>