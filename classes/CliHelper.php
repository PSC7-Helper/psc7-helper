<?php
/**
 * Created by PhpStorm.
 * User: f.wehrhausen
 * Date: 07.08.2018
 * Time: 13:38
 */

class CliHelper
{
    public static function executeCommand($cmd)
    {

        system($cmd, $r);
        return $r;
    }

    public static function matchAllProductByObjectidentifier(array $allObjectidentifier, $disableBacklog = true, $showComments = true)
    {

        $cmds = array();
        if ($disableBacklog === true && $showComments) {
            $add = " --disableBacklog -vvv";
        } else {
            if ($disableBacklog === true) {
                $add = " --disableBacklog";
            } elseif ($showComments === true) {
                $add = " -vvv";
            } else {
                $add = "";
            }
        }
        foreach ($allObjectidentifier as $objectidentifier) {

            $cmd = Config::$cmdPath . Config::$plentyconnectorBaseCommand . ":process Product " . $add . " " . $objectidentifier["objectIdentifier"];
            $cmds[] = $cmd;
        }

        return $cmds;
    }

    public static function liveExecuteCommands($cmds)
    {

        $complete_output = "";
        foreach ($cmds as $cmd) {


            while (@ ob_end_flush()) ; // end all output buffers if any

            $proc = popen($cmd, 'r');

            $live_output = "";

            while (!feof($proc)) {
                $live_output = fread($proc, 4096);
                $complete_output = $complete_output . $live_output;
                echo $live_output;
                @ flush();
            }
            echo "\n";
        }

        //pclose($proc);

        echo "-- Erfolgreich abgeschlossen! --<br><br>";
    }

    public static function liveExecuteCommand($cmd)
    {

        $complete_output = "";


        while (@ ob_end_flush()) ; // end all output buffers if any

        $proc = popen($cmd, 'r');

        $live_output = "";

        while (!feof($proc)) {
            $live_output = fread($proc, 4096);
            $complete_output = $complete_output . $live_output;
            echo $live_output;
            @ flush();
        }
        //echo "\n";

        pclose($proc);

        echo "-- Erfolgreich abgeschlossen! --<br><br>";
    }


    public static function prepareCommand($type, $disableBacklog = false, $vvv = false)
    {


        if ($disableBacklog === true && $vvv === true) {
            $add = " --disableBacklog -vvv";
        } else {
            if ($disableBacklog === true) {
                $add = " --disableBacklog";
            } elseif ($vvv === true) {
                $add = " -vvv";
            } else {
                $add = "";
            }
        }

        $cmd = Config::$cmdPath . Config::$plentyconnectorBaseCommand . ":" . $type . $add;

        return $cmd;
    }


}