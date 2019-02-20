<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 17.02.2019
 * Time: 18:17
 */

include(__DIR__ . "/../config/config.php");

require_once 'Part1ServiceInterface.php';
require_once(__DIR__ . '/../helpers/Connect.php');

class Part1Service implements Part1ServiceInterface
{

    public function doIt(){

        global $xmlstr;

        $configurations = new SimpleXMLElement($xmlstr);

        $connect = new Connect();

        $schema1 = trim((string)$configurations->config[0]->schema1);
        $schema2 = trim((string)$configurations->config[0]->schema2);
        $table1 = trim((string)$configurations->config[0]->table1);
        $table2 = trim((string)$configurations->config[0]->table2);

        $res1 = pg_query($connect->connectionDB($configurations, 1), "SELECT ordinal_position, column_name, data_type FROM information_schema.columns WHERE table_schema = '" . $schema1 . "' AND table_name = '" . $table1 . "' ORDER BY ordinal_position");
        $res2 = pg_query($connect->connectionDB($configurations, 2), "SELECT ordinal_position, column_name, data_type FROM information_schema.columns WHERE table_schema = '" . $schema2 . "' AND table_name = '" . $table2 . "' ORDER BY ordinal_position");

        $array1 = array();
        $array2 = array();

        while ($row = pg_fetch_row($res1)) {
            array_push($array1, $row);
        }

        while ($row = pg_fetch_row($res2)) {
            array_push($array2, $row);
        }

        $flag = false;

        if ($array1 == $array2){
            $flag = true;
        }

        $connect->closeConnectionDB();

        return $flag;

    }

}