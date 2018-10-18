<?php
/**
 * Created by PhpStorm.
 * User: f.wehrhausen
 * Date: 08.06.2018
 * Time: 15:14
 */
include_once('./inc/header.php');


$timestamp = time();

?>
    <div class="container" onload="window.setInterval(updateDiv, 5);">

        <?php

        echo "<h2>Connector-Status:</h2>";

        $data = ShopwareHelper::getBacklogCount()["count"];
        if ($data > 10000) {
            $css_klasse = "danger";
        } else if ($data > 1000) {
            $css_klasse = "warning";
        } else {
            $css_klasse = "success";
        }
        echo "<div class='alert alert-" . $css_klasse . "'>";
        echo "Aktuelle Anzahl der Einträge in der plenty_backlog: " . $data;
        echo "</div>";

        $cron_sync = ShopwareHelper::getCronjobsInfo("PlentyConnector Synchronize");
        $cron_backlog = ShopwareHelper::getCronjobsInfo("PlentyConnector ProcessBacklog");
        $cron_cleanup = ShopwareHelper::getCronjobsInfo("PlentyConnector Cleanup");

        $timedif_sync = ($timestamp - strtotime($cron_sync['next']));

        if ($timedif_sync < 0) {
            echo "<div class='alert alert-success' id='backlogCounter'>'PlentyConnector Synchronize' ";
            echo "startet wieder in T " . $timedif_sync . " Sekunden.<br/></div>";
        } else {
            echo "<div class='alert alert-info' id='backlogCounter'>'PlentyConnector Synchronize' ";
            if ($timedif_sync > 3600) {
                $einheit = "Stunden";
                $timedif_sync = round(($timedif_sync / 3600), 2);
            } elseif ($timedif_sync > 60) {
                $einheit = "Minuten";
                $timedif_sync = round(($timedif_sync / 60), 2);
            } else {
                $einheit = "Sekunden";
            }
            echo " ist überfällig seit " . $timedif_sync . " " . $einheit . " (oder er läuft gerade).<br/></div>";
        }
        $script_backlog = exec("ps aux | grep backlog | grep -v grep");
        if (strlen($script_backlog) > 0) {
            // script läuft
            echo "<div class='alert alert-success'><b>Backlog-Script läuft!</b> (Die Zwischentabelle Plenty <--> Shopware wird abgearbeitet)</div>";
        } else {
            // script läuft nicht
            echo "<div class='alert alert-danger'><b>ACHTUNG: Backlog-Script läuft nicht!</b></div>";
            $script_backlog = "Prozess nicht gefunden.<br/>\r\n";
        }


        if ($timedif_sync > 90) {
            // der job lief seit 90 sekunden nicht mehr obwohl er es hätte tun müssen...
            echo "<div class='alert alert-warning'>ACHTUNG: Der Cronjob 'PlentyConnector Synchronize' lief seit 90 Sekunden nicht mehr obwohl er es hätte tun müssen. Wenn sich die Anzahl der Einträge in der Backlog aber stetig ändert, dann ist es okay.</div>";
        }
        ?>

    <div class="row">
        <div class="col-sm-24">
            <div class="card">
                <div class="card-header">PSC7-Cronjobs-Verwaltung:</div>
                <div class="card-body">
                    <form class="form-inline" method="POST" action="?p=status" style="margin-bottom: 10px;">

                        <label class="form-check-label" style="min-width: 250px; justify-content: left;"><b><?php echo $cron_sync["name"] ?></b> </label>

                            <?php

                            $check = ($cron_sync["active"] == 1) ? 'checked' : '';
                            $intervall = $cron_sync["interval"];
                            echo "
                                 <label class=\"form-check-label\" style='margin-left: 40px;'>
                                   <input type=\"checkbox\" class=\"form-check-input\" name=\"sync_active\" " . $check . "> aktiv
                                 </label>";
                            ?>



                        <label class="form-check-label">
                            Intervall in Sekunden:
                        </label>
                        <input type="text" class="form-control" value="<?php echo $intervall ?>" style="margin-left: 10px;">

                        <button class="btn btn-secondary" style="margin-left: 10px;">speichern</button>
                    </form>

                    <form class="form-inline" method="POST" action="?p=status" style="margin-bottom: 10px;">

                        <label class="form-check-label" style="min-width: 250px; justify-content: left;"><b><?php echo $cron_backlog["name"] ?></b> </label>

                        <?php
                        $check = ($cron_backlog["active"] == 1) ? 'checked' : '';
                        $intervall = $cron_backlog["interval"];
                        echo "
                            <label class=\"form-check-label\" style='margin-left: 40px;'> 
                               <input type=\"checkbox\" class=\"form-check-input\" name=\"sync_active\" " . $check . "> aktiv
                            </label>";
                        ?>
                        <label class="form-check-label">
                            Intervall in Sekunden:
                        </label>
                        <input type="text" class="form-control" value="<?php echo $intervall ?>" style="margin-left: 10px;">

                        <button class="btn btn-secondary" style="margin-left: 10px;">speichern</button>
                    </form>

                    <form class="form-inline" method="POST" action="?p=status" style="margin-bottom: 10px;">
                        <label class="form-check-label" style="min-width: 250px; justify-content: left;"><b><?php echo $cron_cleanup["name"] ?></b> </label>

                            <?php

                            $check = ($cron_cleanup["active"] == 1) ? 'checked' : '';
                            $intervall = $cron_cleanup["interval"];
                            echo "
                                 <label class=\"form-check-label\" style='margin-left: 40px;'>
                                    <input type=\"checkbox\" class=\"form-check-input\" name=\"sync_active\" " . $check . "> aktiv
                                 </label>";
                            ?>


                        <label class="form-check-label">
                            Intervall in Sekunden:
                        </label>
                        <input type="text" class="form-control" value="<?php echo $intervall ?>" style="margin-left: 10px;">

                        <button class="btn btn-secondary" style="margin-left: 10px;">speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <br><br>

        <?php
        /*

    <div>
        <?php // überprüfung der order-states:
        $order_status = Checkjobs::checkjobs_get_unmapped_orderstates_in_orders();
        if ($order_status !== false) {
            if ($order_status > 0) {


                echo "<div class='alert alert-danger'>Mindestens eine Bestellung hat einen ungemappten Zahlungsstatus von Plenty zugewiesen bekommen. Bitte in Shopware den Zahlungsstatus dieser Bestellung(en) korrigieren, sonst werden keine neuen Bestellungen zu Plenty übertragen.</div>";
                $fehlerhafte_bestellungen = Checkjobs::checkjobs_get_fehlerhafte_bestellungen();
                if (count($fehlerhafte_bestellungen) > 0) { ?>
                    <div class="row">
                        <?php foreach ($fehlerhafte_bestellungen as &$fehlerhafte_bestellung) { ?>
                            <div class="col-xs-12 col-sm-8 col-md-6">
                                <div class="panel panel-danger">
                                    <div class="panel-heading">Fehlerhafte Bestellung</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-6"><?php echo "Bestell-ID: " . $fehlerhafte_bestellung['id']; ?></div>
                                            <div class="col-xs-6 text-right"><?php echo "Bestell-Nr: " . $fehlerhafte_bestellung['ordernumber']; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">Bestelldatum:</div>
                                            <div class="col-xs-8 text-right"><?php echo Tools::date_mysql2german_time($fehlerhafte_bestellung['ordertime']); ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">Kundendaten:</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12"><?php echo $fehlerhafte_bestellung['firstname'] . " " . $fehlerhafte_bestellung['lastname']; ?></div>
                                            <div class="col-xs-12"><?php echo $fehlerhafte_bestellung['street'] . "<br/>" . $fehlerhafte_bestellung['zipcode'] . " " . $fehlerhafte_bestellung['city']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='alert alert-success'>Alle Shopware-Bestellungen haben einen gemappten Zahlungsstatus.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Konnte keine Payment-States aus der Shopware-Datenbank abrufen oder Abfrage-Fehler.</div>";
        }
        ?>
    </div>

    <div>
        <?php
        $ohne_hausnummer_billing = Checkjobs::checkjobs_get_billingaddress_ohne_hausnummer();
        if ($ohne_hausnummer_billing !== false) {
            if (count($ohne_hausnummer_billing) > 0) { ?>
                <div class="row">
                    <?php foreach ($ohne_hausnummer_billing as &$oh_billing) { ?>
                        <?php
                        $empfaenger = "fwehrhausen@teleropa.de";
                        $betreff = "Bestell-Ueberpruefung - by FlorianSkript";
                        $from = "Von: Connector-Ueberpruefer";
                        $text = "Fehlerhafte Rechnungsadresse\n\n
                           Bestell-ID:" . $oh_billing['orderID'] . "\n
                           Bestell-Nr:" . $oh_billing['ordernumber'] . "\n\n

                           Kundendaten:\n" .
                            $oh_billing['firstname'] . " " . $oh_billing['lastname'] . "\n" .
                            $oh_billing['street'] . "\n" .
                            $oh_billing['zipcode'] . " " . $oh_billing['city'] . "\n\n bitte im Shopwarebackend (https://www.teleropa.de/backend/) anpassen --> Kunden --> Bestellungen --> nach der Bestellnummer (" . $oh_billing['ordernumber'] . ")suchen und unter Details anpassen.";


                        //mail($empfaenger, $betreff, $text, $from);
                        ?>


                        <div class="col-xs-12 col-sm-8 col-md-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">Fehlerhafte Rechnungsadresse</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-6"><?php echo "Bestell-ID: " . $oh_billing['orderID']; ?></div>
                                        <div class="col-xs-6 text-right"><?php echo "Bestell-Nr: " . $oh_billing['ordernumber']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">Kundendaten:</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12"><?php echo $oh_billing['firstname'] . " " . $oh_billing['lastname']; ?></div>
                                        <div class="col-xs-12"><?php echo $oh_billing['street'] . "<br/>" . $oh_billing['zipcode'] . " " . $oh_billing['city']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else {
                echo "<div class='alert alert-success'>Alle Shopware-Bestellungen haben eine Zahl im Straßen-Feld der Rechnungsadresse.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Konnte keine Rechnungsadressen aus der Shopware-Datenbank abrufen oder Abfrage-Fehler.</div>";
        }
        ?>
    </div>

    <div>
        <?php
        $ohne_hausnummer_shipping = Checkjobs::checkjobs_get_shippinggaddress_ohne_hausnummer();
        if ($ohne_hausnummer_shipping !== false) {
            if (count($ohne_hausnummer_shipping) > 0) { ?>
                <div class="row">
                    <?php foreach ($ohne_hausnummer_shipping as &$oh_shipping) { ?>
                        <?php
                        $empfaenger = "fwehrhausen@teleropa.de";
                        $betreff = "Bestell-Ueberpruefung - by FlorianSkript";
                        $from = "Von: Connector-Ueberpruefer";
                        $text = "Fehlerhafte Lieferadresse\n\n
                           Bestell-ID:" . $oh_shipping['orderID'] . "\n
                           Bestell-Nr:" . $oh_shipping['ordernumber'] . "\n
                           Kundendaten:" . "\n\n" .
                            $oh_shipping['firstname'] . " " . $oh_shipping['lastname'] . "\n " .
                            $oh_shipping['street'] . "\n " .
                            $oh_shipping['zipcode'] . " " . $oh_shipping['city'] . "\n\n bitte im Shopwarebackend (https://www.teleropa.de/backend/) anpassen --> Kunden --> Bestellungen --> nach der Bestellnummer (" . $oh_shipping['ordernumber'] . ")suchen und unter Details anpassen.";


                        //mail($empfaenger, $betreff, $text, $from);

                        ?>
                        <div class="col-xs-12 col-sm-8 col-md-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">Fehlerhafte Lieferadresse</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-6"><?php echo "Bestell-ID: " . $oh_shipping['orderID']; ?></div>
                                        <div class="col-xs-6 text-right"><?php echo "Bestell-Nr: " . $oh_shipping['ordernumber']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">Kundendaten:</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12"><?php echo $oh_shipping['firstname'] . " " . $oh_shipping['lastname']; ?></div>
                                        <div class="col-xs-12"><?php echo $oh_shipping['street'] . "<br/>" . $oh_shipping['zipcode'] . " " . $oh_shipping['city']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else {
                echo "<div class='alert alert-success'>Alle Shopware-Bestellungen haben eine Zahl im Straßen-Feld der Lieferadresse.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Konnte keine Rechnungsadressen aus der Shopware-Datenbank abrufen oder Abfrage-Fehler.</div>";
        }
        ?>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Info-Leiste</h3>
        </div>
        <div class="panel-body">
            <?php
            echo "<b>'PlentyConnector Synchronize':</b><br/>";
            if ($cron_sync != false) {
                echo "Nächstes Mal: " . $cron_sync['next'] . "<br/>";
                echo "Letztes Mal:&nbsp;&nbsp;&nbsp;&nbsp; " . $cron_sync['start'] . "<br/>";
            } else {
                echo "Fehler beim holen der 'PlentyConnector Synchronize'<br/>";
            }
            ?>
        </div>
        <div class="panel-body">
            <?php
            echo "<b>'PlentyConnector ProcessBacklog':</b><br/>";
            if ($cron_backlog != false) {
                echo "Nächstes Mal: " . $cron_backlog['next'] . "<br/>";
                echo "Letztes Mal:&nbsp;&nbsp;&nbsp;&nbsp; " . $cron_backlog['start'] . "<br/>";
            } else {
                echo "Fehler beim holen der Daten des Cronjobs 'PlentyConnector ProcessBacklog'<br/>";
            }
            ?>
        </div>
        <div class="panel-body">
            <?php
            echo "<b>'PlentyConnector Cleanup':</b><br/>";
            if ($cron_cleanup != false) {
                echo "Nächstes Mal: " . $cron_cleanup['next'] . "<br/>";
                echo "Letztes Mal:&nbsp;&nbsp;&nbsp;&nbsp; " . $cron_cleanup['start'] . "<br/>";
            } else {
                echo "Fehler beim holen der Daten des Cronjobs 'PlentyConnector Cleanup'<br/>";
            }
            ?>
        </div>
        <div class="panel-body">
            <?php
            echo "<b>Backlog-Script Prozess-Daten</b>:<br/>";
            echo "<pre>";
            var_export($script_backlog);
            echo "</pre>";
            ?>
        </div>
    </div>

    </div> <!-- /container -->
    <script>
        var myTimer = window.setInterval(backlogCounter, 5000);
    </script>
      */

/****************************************************************/
        ?>


    </div>
</div>
<?php

include_once('./inc/footer.php');
