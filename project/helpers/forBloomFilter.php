<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 03.05.2019
 * Time: 19:42
 */

include(__DIR__ . "/../config/old_config.php");

require_once(__DIR__ . '/../helpers/Connect.php');
require_once(__DIR__ . '/../helpers/Info.php');

class forBloomFilter
{

    public function getN(){
        global $xmlstr;
        $configurations = new SimpleXMLElement($xmlstr);
        $connect = new Connect();
        $info = new Info();
        $table = $info->getTable()[0];
        $n = pg_query($connect->connectionDB($configurations, 1), "SELECT count(*) FROM $table");
        (float)$n = pg_fetch_row($n)[0];
        $connect->closeConnectionDB();
        return $n;
    }

    public function getM(){
        $n = $this->getN();
        $info = new Info();
        (float)$p = $info->getP();
        $m = abs(($n * log($p)) / (pow(log(2), 2)));
        $m = (int)$m;
        return $m;
    }

    public function getK(){
        $m = $this->getM();
        $n = $this->getN();
        $k = (float)abs($m / $n * log(2));
        $k = (int)$k;
        return $k;
    }

    public function getCBF($x, $type){

        $array = $this->getStrings($x);

        $m = $this->getM();
        $k = $this->getK();

        //echo $m . " " . $k . "<br>";

        $hashClass = new Hash();
        $hash = $hashClass->getHash($k);

        $cbf = array();

        for ($i = 0; $i < $m; $i++){
            $cbf[$i] = 0;
        }

        for ($i = 0; $i < count($array); $i++){

            //echo $array[$i] . "<br>";

            for ($q = 0; $q < $k; $q++){
                $hash1 = hash($hash[$q], $array[$i]);
                $hash1 = substr($hash1, 0, 12);
                $hash1 = hexdec($hash1);
                if ($type == "CBF") {
                    $cbf[$hash1 % $m]++;
                }
                else{
                    $cbf[$hash1 % $m] = 1;
                }
            }

        }

        return $cbf;

    }

    public function getStrings($x){
        global $xmlstr;
        $configurations = new SimpleXMLElement($xmlstr);
        $connect = new Connect();

        $info = new Info();
        $schema = $info->getSchema()[$x - 1];
        $table = $info->getTable()[$x - 1];

        $colums = pg_query($connect->connectionDB($configurations, $x), "SELECT column_name FROM information_schema.columns WHERE table_schema = '" . $schema . "' AND table_name = '" . $table . "'");

        $select = "SELECT ";

        while ($row = pg_fetch_row($colums)){
            $select = $select . "COALESCE(" . $row[0] . "::varchar,'') || ";
        }

        $select = substr($select, 0, -3);
        $select = $select . "AS string from " . $table;

        //echo $select;

        $res = pg_query($connect->connectionDB($configurations, $x), $select);

        $array = array();

        while ($row = pg_fetch_row($res)) {
            array_push($array, $row[0]);
        }

        $connect->closeConnectionDB();

        return $array;
    }

}