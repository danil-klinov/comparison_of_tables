<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 17.02.2019
 * Time: 18:17
 */

include(__DIR__ . "/../config/old_config.php");

require_once 'StepInterface.php';
require_once(__DIR__ . '/../helpers/Connect.php');
require_once(__DIR__ . '/../helpers/Info.php');

class Part1Service implements StepInterface
{

    public function Postgres_Postgres(){

        global $xmlstr;

        $configurations = new SimpleXMLElement($xmlstr);

        $connect = new Connect();

        $info = new Info();

        $schema1 = $info->getSchema()[0];
        $schema2 = $info->getSchema()[1];
        $table1 = $info->getTable()[0];
        $table2 = $info->getTable()[1];

        //phpinfo();
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

        //print_r($array1);

        $flag = false;

        if ($array1 == $array2){
            $flag = true;
        }

        $connect->closeConnectionDB();

        return $flag;

    }

    public function Postgres_Oracle(){

        global $xmlstr;

        $configurations = new SimpleXMLElement($xmlstr);

        $connect = new Connect();

        $info = new Info();

        $schema1 = $info->getSchema()[0];
        $table1 = $info->getTable()[0];
        $table2 = $info->getTable()[1];

        $res1 = pg_query($connect->connectionDB($configurations, 1), "SELECT ordinal_position, column_name, data_type FROM information_schema.columns WHERE table_schema = '" . $schema1 . "' AND table_name = '" . $table1 . "' ORDER BY ordinal_position");

        $res2 = oci_parse($connect->connectionDB($configurations, 2), "SELECT column_id, column_name, data_type FROM user_tab_columns WHERE table_name = '" . $table2 . "' ORDER BY column_id");
        $didbv = 60;
        oci_bind_by_name($res2, ':didbv', $didbv);
        oci_execute($res2);

        $array1 = array();
        $array2 = array();

        while ($row = pg_fetch_row($res1)) {
            array_push($array1, $row);
        }

        while ($row = oci_fetch_row($res2)) {
            array_push($array2, $row);
        }

        print_r($array1);
        print_r($array2);

        $flag = false;

        if ($array1 == $array2){
            $flag = true;
        }

        $connect->closeConnectionDB();

        return $flag;

    }

    public function Oracle_Oracle()
    {
        // TODO: Implement Oracle_Oracle() method.
    }

}