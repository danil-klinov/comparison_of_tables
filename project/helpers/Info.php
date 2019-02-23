<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 23.02.2019
 * Time: 22:22
 */

include(__DIR__ . "/../config/config.php");

class Info
{

    public function getCnfigurations()
    {

        global $xmlstr;
        $configurations = new SimpleXMLElement($xmlstr);

        return $configurations;

    }

    public function getTable()
    {

        $configurations = $this->getCnfigurations();

        $table1 = trim((string)$configurations->config[0]->table1);
        $table2 = trim((string)$configurations->config[0]->table2);

        $array = array();

        array_push($array, $table1);
        array_push($array, $table2);

        return $array;

    }

    public function getSchema(){

        $configurations = $this->getCnfigurations();

        $schema1 = trim((string)$configurations->config[0]->schema1);
        $schema2 = trim((string)$configurations->config[0]->schema2);

        $array = array();

        array_push($array, $schema1);
        array_push($array, $schema2);

        return $array;

    }



}