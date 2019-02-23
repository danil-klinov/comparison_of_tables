<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 17.02.2019
 * Time: 18:19
 */

include(__DIR__ . "/../config/config.php");

require_once 'Part2ServiceInterface.php';
require_once(__DIR__ . '/../helpers/Connect.php');
require_once(__DIR__ . '/../helpers/Info.php');

class Part2Service implements Part2ServiceInterface
{

    public function doIt(){

        global $xmlstr;

        $configurations = new SimpleXMLElement($xmlstr);

        $connect = new Connect();

        $info = new Info();

        $schema1 = $info->getSchema()[0];
        $table1 = $info->getTable()[0];
        $table2 = $info->getTable()[1];

        $res = pg_query($connect->connectionDB($configurations, 1), "SELECT column_name, data_type FROM information_schema.columns WHERE table_schema = '" . $schema1 . "' AND table_name = '" . $table1 . "' ORDER BY ordinal_position");

        $column = array();
        $type = array();

        while ($row = pg_fetch_row($res)) {
            array_push($column, $row[0]);
            array_push($type, $row[1]);
        }

        $select1 = "SELECT count(*) as cnt_all, ";

        for ($i = 0; $i < count($column); $i++){

            if ($type[$i] != 'boolean') {
                $select1 = $select1 . "min(" . $column[$i] . ") as min_" . $column[$i] . ", max(" . $column[$i] . ") as max_" . $column[$i] . ", count(" . $column[$i] . ") as count_" . $column[$i] . ", ";
            }
            else{
                $select1 = $select1 . "count(" . $column[$i] . ") as count_" . $column[$i] . ", ";
            }

        }

        $select1 = substr($select1, 0, -2);

        $select2 = $select1 . " FROM " . $table2 . ";";
        $select1 = $select1 . " FROM " . $table1 . ";";

        $row1 = pg_fetch_row(pg_query($connect->connectionDB($configurations, 1), $select1));
        $row2 = pg_fetch_row(pg_query($connect->connectionDB($configurations, 2), $select2));

        $flag = false;

        if ($row1 == $row2){
            $flag = true;
        }

        $connect->closeConnectionDB();

        return $flag;

    }

}