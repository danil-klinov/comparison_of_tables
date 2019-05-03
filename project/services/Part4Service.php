<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 03.05.2019
 * Time: 19:39
 */

include(__DIR__ . "/../config/old_config.php");

require_once 'StepInterface.php';
require_once(__DIR__ . '/../helpers/Connect.php');
require_once(__DIR__ . '/../helpers/Info.php');
require_once(__DIR__ . '/../helpers/Hash.php');

class Part4Service implements StepInterface
{

    public function Postgres_Postgres(){

        $forBloomFilter = new forBloomFilter();

        $cbfA = $forBloomFilter->getCBF(1, "BF");
        $stringsB = $forBloomFilter->getStrings(2);

        $info = new Info();
        (int)$countOfCheck = $info->getCountOfCheck();

        $m = $forBloomFilter->getM();
        $k = $forBloomFilter->getK();

        $hashClass = new Hash();
        $hash = $hashClass->getHash($k);

        $flag = true;

        for ($i = 0; $i < $countOfCheck && $flag; $i++){

            $index = array_rand($stringsB);
            $string = $stringsB[$index];
            unset($stringsB[$index]);

            //echo $string . "<br>";

            for ($q = 0; $q < $k && $flag; $q++){
                $hash1 = hash($hash[$q], $string);
                $hash1 = substr($hash1, 0, 12);
                $hash1 = hexdec($hash1);
                if ($cbfA[$hash1  % $m] == 0) {
                    $flag = false;
                }
            }

        }


//        for($i = 0; $i < count($cbfA);$i++){
//            echo $cbfA[$i] . " ";
//        }

//        echo "<br>";
//        for($i = 0; $i < count($stringsB) + $countOfCheck;$i++){
//            if (array_key_exists($i, $stringsB)) {
//                echo $stringsB[$i] . "<br>";
//            }
//        }

        return $flag;

    }

    public function Postgres_Oracle()
    {
        // TODO: Implement Postgres_Oracle() method.
    }

    public function Oracle_Oracle()
    {
        // TODO: Implement Oracle_Oracle() method.
    }

}