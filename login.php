<?php
/**
 * Created by PhpStorm.
 * User: f.wehrhausen
 * Date: 08.06.2018
 * Time: 15:14
 */
include_once('./inc/header.php');

  $login_fehlermeldung  = NULL;
  $login_ergebnis       = FALSE;

  if (isset($_REQUEST['login'])) {
    $login_ergebnis = LoginHelper::check_login($_REQUEST['user'],$_REQUEST['pw']);
    if (!$login_ergebnis) {
      $login_fehlermeldung  = "<strong>Fehler:</strong> Login-Daten falsch.";
    } else {
      // weiterleiten zur dashboard.php
      header('Location: ./dashboard.php');
    }
  }

?>
    <div class="container" style="margin-top: 2rem;">
      <div class="row">
        <div class="col-xs-12">
          <div class="panel panel-default">
              <div class="panel-heading">Login</div>
              <div class="panel-body">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="login-form">
                      <?php
                        if ($login_fehlermeldung != NULL) { ?>
                        <div class="form-group" style="margin-top: 1rem;">
                          <div class="alert alert-danger"><?php echo $login_fehlermeldung; ?></div>
                        </div>
                      <?php } ?>
                      <div class="form-group">
                          <label for="user">Benutzername:</label>
                          <input type="text" class="form-control" id="user" name="user" />
                      </div>
                      <div class="form-group">
                          <label for="pwd">Passwort:</label>
                          <input type="password" class="form-control" id="pwd" name="pw" />
                      </div>
                      <button type="submit" class="btn btn-default" name="login">Anmelden</button>
                  </form>
              </div>
          </div>
        </div>
      </div>
    </div>
<?php

include_once('./inc/footer.php');