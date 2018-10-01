<?php
/**
 * Created by PhpStorm.
 * User: f.wehrhausen
 * Date: 08.06.2018
 * Time: 15:14
 */
include_once('./inc/header.php');

if (isset($_POST["uuid"])){
    $uuid = $_POST["uuid"];
}


?>
    <div class="container">
        <div class="row">
            <div class="col-sm-24">
                <h2>Herstellerüberprüfung</h2>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-24">

                <pre style="background: #434343; color: #fff; padding: 10px; border-radius: 6px; min-height: 100px;"><?php
                    if (isset($uuid)) {
                        echo "Gleiche Hersteller mit dem ObjectIdentifier: ".$uuid." ab.<br>";
                        $type = "process Manufacturer ".$uuid;
                        $cmd = CliHelper::prepareCommand($type,true,true);
                        echo "Befehl: ".$cmd."<br><br>";
                        CliHelper::liveExecuteCommand($cmd);
                    }
                    else{
                        echo "warte auf Befehl...";
                    }
                    ?>

                </pre>
                <?php
                echo ShopwareHelper::getSupplierHTML();
                ?>
            </div>
        </div>


    </div> <!-- /container -->

<?php

include_once('./inc/footer.php');