<?php
/**
 * Created by PhpStorm.
 * User: f.wehrhausen
 * Date: 15.06.2018
 * Time: 08:47
 */
error_reporting(E_ALL);
include_once('./inc/header.php');

//Logik
if (isset($_GET["p"])) {
    $page = $_GET["p"];
}
if (isset($_POST["cmd"])) {
    $action = $_POST["cmd"];
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


    <div class="container" style="margin-top:30px">
        <div class="row">
            <div class="col-sm-12">
                <h2>Connector-Befehle:</h2>
            </div>
        </div>
        <hr>
        <br>
        <div class="row">

            <div class="col-sm-12">
                <form class="form-connector" method="POST" action="?p=cmd">
                    <?php
                    echo ShopwareHelper::getCatorieSelectionHTML();
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

                    <button class="btn btn-connector-blue">Alle Artikel dieser Kategorie Abgleichen</button>

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
                <h3 style="text-align: center">Bitte nur benutzen, wennn man die Auswirkungen kennt:</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="backlog:info">
                    <button class="btn btn-connector-blue">
                        <a data-toggle="tooltip" data-placement="bottom"
                           title="Wie viele Einträge sind in der Backlog Tabelle?">BACKLOG:INFO</a>
                    </button>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="backlog:process">
                    <button class="btn btn-connector-blue">
                        <a data-toggle="tooltip" data-placement="bottom"
                           title="Eintäge in der Backlog Tabelle abarbeiten">BACKLOG:PROCESS</a>
                    </button>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="process product">
                    <button class="btn btn-connector-blue">
                        <a data-toggle="tooltip" data-placement="bottom" title="Alle Produkte abgleichen">PROCESS:PRODUCT</a>
                    </button>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="process stock">
                    <button class="btn btn-connector-blue">
                        <a data-toggle="tooltip" data-placement="bottom" title="Lagerbestände aus Plenty abgleichen">PROCESS:STOCK</a>
                    </button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form class="form-connector last-form" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="process order">
                    <button class="btn btn-connector-blue">
                        <a data-toggle="tooltip" data-placement="bottom" title="Aktuelle Bestellungen abgleichen">PROCESS:ORDER</a>
                    </button>
                </form>
            </div>

            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="sw:clear:cache">
                    <button class="btn btn-connector-blue">
                        <a data-toggle="tooltip" data-placement="bottom"
                           title="Shopware Cache leeren">SW:CACHE:CLEAR</a>
                    </button>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="sw:warm:http:cache">
                    <button class="btn btn-connector-blue">
                        <a data-toggle="tooltip" data-placement="bottom"
                           title="Shopware Cache leeren">SW:WARM:HTTP:CACHE</a>
                    </button>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="sw:media:cleanup">
                    <button class="btn btn-connector-blue">
                        <a data-toggle="tooltip" data-placement="bottom"
                           title="Shopware Media Cleanup">SW:MEDIA:CLEANUP</a>
                    </button>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="process order --all">
                    <button class="btn btn-connector-red">
                        <a data-toggle="tooltip" data-placement="bottom"
                           title="alle Bestellungen abgleichen (Zeitstempel wird ignoriert)">PROCESS:ORDER --all</a>
                    </button>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-connector" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="process category --all">
                    <button class="btn btn-connector-red">
                        <a data-toggle="tooltip" data-placement="bottom" title="alle Kategorien abgleichen">CATEGORY
                            --all</a>
                    </button>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-connector last-form" method="POST" action="?page=connector">
                    <input type="hidden" name="cmd" value="cleanup -vvv">
                    <button class="btn btn-connector-red">
                        <a data-toggle="tooltip" data-placement="bottom" title="Cleanup durchführen">CLEANUP -vvv</a>
                    </button>
                </form>
            </div>

        </div>


        <div class="clear"></div>

        <br>
        <hr>
        <?php
        /*
        if (!empty($action)) {
            $cmd = CliHelper::prepareCommand($action);
            echo $cmd;
        }
        */
        ?>
        <br>
        <pre style="background: #434343; color: #fff; padding: 10px; border-radius: 6px; min-height: 400px;"><?php
            if (isset($_POST["s_categoryID"])) {
                $allUuidPerSelectedCategory = ShopwareHelper::getAllProductObjectIdentifierByCategoreyID($_POST["s_categoryID"]);
                $cmds = CliHelper::matchAllProductByObjectidentifier($allUuidPerSelectedCategory, $backlog, $vvv);
                CliHelper::liveExecuteCommands($cmds);
            } else if(empty($action)){
                echo "warte auf Befehl ...";
            }
            if (!empty($action)) {
                $cmd = CliHelper::prepareCommand($action);
                echo $cmd."<br>";
                CliHelper::liveExecuteCommand($cmd);
            }else{
                echo "warte auf Befehl ...";
            }
            ?>

        </pre>

        <div class="colClear"></div>


    </div>


<?php

include_once('./inc/footer.php');