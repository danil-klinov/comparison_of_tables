<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 12.07.2018
 * Time: 15:15
 */

class Connect
{

    public function connectionDB($configurations, $id)
    {

        $array = $this->getInfo($configurations, $id);

        $array[0] = trim($array[0]);
        $array[1] = trim($array[1]);
        $array[2] = trim($array[2]);
        $array[3] = trim($array[3]);
        $array[4] = trim($array[4]);
        $array[5] = trim($array[5]);

        if (trim($array[5]) == "postgresql"){
            $db = pg_connect("host=" . $array[0] . " port=" . $array[1] . " dbname=" . $array[2] . " user=" . $array[3] . " password=" . $array[4]);
        }
        else{
            $db = oci_connect("'" . $array[3] . "'", "'" . $array[4] . "'", "'" . $array[2] . "'");
        }

        return $db;

    }

    private function getInfo($configurations, $id){

        $array = array();

        $host = 'host' . $id;
        $port = 'port' . $id;
        $dbname = 'dbname' . $id;
        $user = 'user' . $id;
        $password = 'password' . $id;
        $type = 'type' . $id;

        array_push($array,(string)$configurations->config[0]->$host);
        array_push($array,(string)$configurations->config[0]->$port);
        array_push($array,(string)$configurations->config[0]->$dbname);
        array_push($array,(string)$configurations->config[0]->$user);
        array_push($array,(string)$configurations->config[0]->$password);
        array_push($array,(string)$configurations->config[0]->$type);

        return $array;

    }

    public function closeConnectionDB()
    {
        pg_close();
        oci_close();
    }


}