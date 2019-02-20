<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 12.07.2018
 * Time: 15:15
 */
include "config.php";

$configurations = new SimpleXMLElement($xmlstr);

$host1 = $configurations->config[0]->host1;
$host2 = $configurations->config[0]->host2;

$port1 = $configurations->config[0]->port1;
$port2 = $configurations->config[0]->port2;

$dbname1 = $configurations->config[0]->dbname1;
$dbname2 = $configurations->config[0]->dbname2;

$user1 = $configurations->config[0]->user1;
$user2 = $configurations->config[0]->user2;

$password1 = $configurations->config[0]->password1;
$password2 = $configurations->config[0]->password2;

class Connect
{

    public function connectionDB(){
        $db = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=Danil739");
        return $db;
    }

    public function closeConnectionDB(){
        pg_close();
    }

}