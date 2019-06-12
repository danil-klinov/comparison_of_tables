<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 20.05.2019
 * Time: 0:26
 */

include(__DIR__ . "/../config/config.php");

require_once 'StepInterface.php';
require_once(__DIR__ . '/../helpers/Connect.php');
require_once(__DIR__ . '/../helpers/Info.php');

class LineByLine implements StepInterface
{

    public function Postgres_Postgres(){

        global $xmlstr;

        $configurations = new SimpleXMLElement($xmlstr);

        $connect = new Connect();

        $info = new Info();

        $table1 = $info->getTable()[0];
        $table2 = $info->getTable()[1];
        $h = 0;

        $res1 = pg_query($connect->connectionDB($configurations, 1), "select * from users order by login, password, hash, is_admin, new_column, id");
        $res2 = pg_query($connect->connectionDB($configurations, 2), "select * from users order by login, password, hash, is_admin, new_column, id");

        while ($row1 = pg_fetch_row($res1)) {
            $row2 = pg_fetch_row($res2);
            if ($row1 != $row2){
                $h++;
            }
        }

        echo "<br>" . "<br>" . "GOOD";

    }

    public function Postgres_Oracle()
    {
        global $xmlstr;

        $configurations = new SimpleXMLElement($xmlstr);

        $connect = new Connect();

        $info = new Info();

        $table1 = $info->getTable()[0];
        $table2 = $info->getTable()[1];
        $h = 0;

        $res1 = pg_query($connect->connectionDB($configurations, 1), "select * from users order by login, password, hash, is_admin, new_column, id");
        $res2 = oci_parse($connect->connectionDB($configurations, 2), "select * from users order by login, password, hash, is_admin, new_column, id");

        while ($row1 = pg_fetch_row($res1)) {
            $row2 = oci_fetch_row($res2);
            if ($row1 != $row2){
                $h++;
            }
        }

        echo "<br>" . "<br>" . "GOOD";
    }

    public function Oracle_Oracle()
    {
        global $xmlstr;

        $configurations = new SimpleXMLElement($xmlstr);

        $connect = new Connect();

        $info = new Info();

        $table1 = $info->getTable()[0];
        $table2 = $info->getTable()[1];
        $h = 0;

        $res1 = oci_parse($connect->connectionDB($configurations, 1), "select * from users order by login, password, hash, is_admin, new_column, id");
        $res2 = oci_parse($connect->connectionDB($configurations, 2), "select * from users order by login, password, hash, is_admin, new_column, id");

        while ($row1 = oci_fetch_row($res1)) {
            $row2 = oci_fetch_row($res2);
            if ($row1 != $row2){
                $h++;
            }
        }

        echo "<br>" . "<br>" . "GOOD";
    }

}