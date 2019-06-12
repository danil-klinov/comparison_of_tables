<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 03.05.2019
 * Time: 19:42
 */

include(__DIR__ . "/../config/config.php");

require_once(__DIR__ . '/../helpers/Connect.php');
require_once(__DIR__ . '/../helpers/Info.php');

$n;
$m;
$k;
$a;
$cbf1;
$cbf2;
$strings1;
$strings1;
$database = "null";

class forBloomFilter
{

    public function getN(){
        global $n;
        global $database;
        if ($n) {
            return $n;
        }
        else{
            global $xmlstr;
            $configurations = new SimpleXMLElement($xmlstr);
            $connect = new Connect();
            $info = new Info();
            $table = $info->getTable()[0];
            if ($database == "postgres") {
                $n = pg_query($connect->connectionDB($configurations, 1), "SELECT count(*) FROM $table");
                (float)$n = pg_fetch_row($n)[0];
            }
            else{
                $n = oci_parse($connect->connectionDB($configurations, 1), "SELECT count(*) FROM $table");
                (float)$n = oci_fetch_row($n)[0];
            }
            (float)$n = pg_fetch_row($n)[0];
            $connect->closeConnectionDB();
            return $n;
        }
    }

    public function getA(){
        global $a;
        global $database;
        if ($a) {
            return $a;
        }
        else {
            global $xmlstr;
            $configurations = new SimpleXMLElement($xmlstr);
            $connect = new Connect();
            $info = new Info();
            $table = $info->getTable()[0];
            $schema = $info->getSchema()[0];
            if ($database == "postgres"){
                $a = pg_query($connect->connectionDB($configurations, 1),
                    "SELECT count(column_name) FROM information_schema.columns WHERE table_schema = '" . $schema . "' AND table_name = '" . $table . "'");
                (float)$a = pg_fetch_row($a)[0];
            }
            else {
                $a = oci_parse($connect->connectionDB($configurations, 1),
                    "SELECT count(column_name) FROM information_schema.columns WHERE table_schema = '" . $schema . "' AND table_name = '" . $table . "'");
                (float)$a = oci_fetch_row($a)[0];
            }

            $connect->closeConnectionDB();
            return $a;
        }
    }

    public function getM(){
        global $m;
        if ($m) {
            echo $m . "<br>";;
            return $m;
        }
        else {
            $n = $this->getN();
            $info = new Info();
            (float)$p = $info->getP();
            $m = abs(($n * log($p)) / (pow(log(2), 2)));
            $m = (int)$m;
            echo $m . "<br>";
            return $m;
        }
    }

    public function getK(){
        global $k;
        if ($k) {
            return 1;
            return $k;
        }
        else {

            $m = $this->getM();
            $n = $this->getN();
            $k = (float)abs($m / $n * log(2));
            $k = (int)$k;
            return 1;
            return $k;
        }
    }

    public function getCBF($x, $type,  $database_in){
        global $database;
        $database = $database_in;
        global $cbf1;
        global $cbf2;
        if ($x == 1){
            $check = $cbf1;
        }
        else{
            $check = $cbf2;
        }
        if ($check) {
            return $check;
        }
        else {
            $array = $this->getStrings($x);

            $m = $this->getM();
            $k = $this->getK();

            $hashClass = new Hash();
            $hash = $hashClass->getHash($k);

            $cbf = array();
            for ($i = 0; $i < $m; $i++) {
                $cbf[$i] = 0;
            }

            for ($i = 0; $i < count($array); $i++) {

                for ($q = 0; $q < $k; $q++) {
                    $hash1 = hash($hash[$q], $array[$i]);
                    $hash1 = substr($hash1, 0, 12);
                    $hash1 = hexdec($hash1);
                    if ($type == "CBF") {
                        $cbf[$hash1 % $m]++;
                    } else {
                        $cbf[$hash1 % $m] = 1;
                    }
                }

            }
            if ($x == 1){
                $cbf1 = $cbf;
            }
            else{
                $cbf2 = $cbf;
            }
            return $cbf;
        }
    }

    public function getStrings($x){
        global $strings1;
        global $strings2;
        if ($x == 1){
            $check = $strings1;
        }
        else{
            $check = $strings2;
        }
        if ($check) {
            return $check;
        }
        else {
            global $xmlstr;
            global $database;
            $configurations = new SimpleXMLElement($xmlstr);
            $connect = new Connect();

            $info = new Info();
            $schema = $info->getSchema()[$x - 1];
            $table = $info->getTable()[$x - 1];

            if ($database == "postgres"){
                $colums = pg_query($connect->connectionDB($configurations, $x),
                    "SELECT column_name FROM information_schema.columns WHERE table_schema = '" . $schema . "' AND table_name = '" . $table . "'");

                $select = "SELECT ";

                while ($row = pg_fetch_row($colums)) {
                    $select = $select . "COALESCE(" . $row[0] . "::varchar,'') || ";
                }

                $select = substr($select, 0, -3);
                $select = $select . "AS string from " . $table;

                $res = pg_query($connect->connectionDB($configurations, $x), $select);

                $array = array();

                while ($row = pg_fetch_row($res)) {
                    array_push($array, $row[0]);
                }
            }
            else{
                $colums = oci_parse($connect->connectionDB($configurations, $x),
                    "SELECT column_name FROM information_schema.columns WHERE table_schema = '" . $schema . "' AND table_name = '" . $table . "'");

                $select = "SELECT ";

                while ($row = oce_fetch_row($colums)) {
                    $select = $select . "COALESCE(" . $row[0] . "::varchar,'') || ";
                }

                $select = substr($select, 0, -3);
                $select = $select . "AS string from " . $table;

                $res = oci_parse($connect->connectionDB($configurations, $x), $select);

                $array = array();

                while ($row = oci_fetch_row($res)) {
                    array_push($array, $row[0]);
                }
            }

            $connect->closeConnectionDB();

            if ($x == 1) {
                $strings1 = $array;
            } else {
                $strings2 = $array;
            }
            return $array;
        }
    }

}