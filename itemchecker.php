<?php
/**
 * Created by PhpStorm.
 * User: f.wehrhausen
 * Date: 08.06.2018
 * Time: 15:14
 */

include_once('./inc/header.php');

error_reporting(E_ALL);

if (isset($_POST["uuid"])){
    $uuid = $_POST["uuid"];
}

if (isset($_POST["vvv"])) {
    $vvv = ($_POST["vvv"] == "on") ? true : false;
}
if (isset($_POST["all"])) {
    $all = ($_POST["all"] == "on") ? true : false;
}
if (isset($_POST["backlog"])) {
    $backlog = ($_POST["backlog"] == "on") ? true : false;
}

?>
    <div class="container">
        <div class="row">
            <div class="col-sm-24">
                <h2>Artikelüberprüfung</h2>
            </div>

        </div>
        <div class="row">
        <div class="col-sm-12">
            <form class="form-connector" method="POST">
                <?php
                  echo ShopwareHelper::getArticleSelectionHTMLNoJs();
                ?>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <b>Parameter:</b>
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="vvv" checked>-vvv
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="backlog" checked>--disableBacklog
                    </label>
                </div>


                <br>
                <br>

                <button class="btn btn-connector-blue">Artikel abgleichen</button>

            </form>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">Legende:</div>
                <div class="card-body" style="padding-top: 0px">
                    <table class="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Befehl</th>
                            <th>Bedeutung</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>-vvv</td>
                            <td>alle Detailsanzeigen</td>
                        </tr>
                        <tr>
                            <td style="min-width: 130px;">--disableBacklog</td>
                            <td>Befehl wird direkt ausgeführt (mehr Serverlast)</td>
                        </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <br>
    <hr>
    <br>
        <div class="row">
            <div class="col-sm-24">

                <pre style="background: #434343; color: #fff; padding: 10px; border-radius: 6px; min-height: 100px;"><?php
                    if (isset($uuid)) {
                        echo "Gleiche Artikel mit dem ObjectIdentifier: ".$uuid." ab.<br>";
                        $type = "process Product ".$uuid;
                        $cmd = CliHelper::prepareCommand($type,true,true);
                        CliHelper::liveExecuteCommand($cmd);
                    }
                    else{
                        echo "warte auf Befehl...";
                    }
                    ?>

                </pre>
                <?php

                ?>
            </div>
        </div>
        <!--
        <div class="row">
            <div class="col-sm-24">
                <pre>
                    <?php
                        //var_dump(ShopwareHelper::allArticleWithoutShopwareOrderNumber);
                    ?>
                </pre>
            </div>

        </div>
        -->


    </div> <!-- /container -->
<?php

include_once('./inc/footer.php');