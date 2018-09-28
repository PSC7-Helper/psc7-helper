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


$timestamp = time();

?>
    <div class="container">
        <div class="row">
            <div class="col-sm-24">
                <h2>Bestellüberprüfung</h2>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-24">

                <pre style="background: #434343; color: #fff; padding: 10px; border-radius: 6px; min-height: 100px;"><?php
                    if (isset($uuid)) {
                        echo "Gleiche Bestellung mit dem ObjectIdentifier: ".$uuid." ab.<br>";
                        $type = "process Order ".$uuid;
                        $cmd = CliHelper::prepareCommand($type,true,true);
                        CliHelper::liveExecuteCommand($cmd);
                        $type = "process Payment ".$uuid;
                        $cmd = CliHelper::prepareCommand($type,true,true);
                        CliHelper::liveExecuteCommand($cmd);

                        echo "Die OrderID aus Plenty lautet: ".ShopwareHelper::getNewPlentyOrderID($uuid)["adapterIdentifier"];
                    }
                    else{
                        echo "warte auf Befehl...";
                    }
                    ?>

                </pre>
                <?php
                    echo ShopwareHelper::getMappedOrdersHTML();
                ?>
            </div>
        </div>


    </div> <!-- /container -->

<?php

include_once('./inc/footer.php');