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

        $db = pg_connect("host=" . $array[0] . " port=" . $array[1] . " dbname=" . $array[2] . " user=" . $array[3] . " password=" . $array[4]);

        return $db;

    }

    private function getInfo($configurations, $id){

        $array = array();
        

        $host = 'host' . $id;
        $port = 'port' . $id;
        $dbname = 'dbname' . $id;
        $user = 'user' . $id;
        $password = 'password' . $id;

        array_push($array,(string)$configurations->config[0]->$host);
        array_push($array,(string)$configurations->config[0]->$port);
        array_push($array,(string)$configurations->config[0]->$dbname);
        array_push($array,(string)$configurations->config[0]->$user);
        array_push($array,(string)$configurations->config[0]->$password);

        return $array;

    }

    public function closeConnectionDB()
    {
        pg_close();
    }

}