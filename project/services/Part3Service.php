<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 17.02.2019
 * Time: 18:19
 */

include(__DIR__ . "/../config/config.php");

require_once 'StepInterface.php';
require_once(__DIR__ . '/../helpers/Connect.php');
require_once(__DIR__ . '/../helpers/Info.php');
require_once(__DIR__ . '/../helpers/Hash.php');
require_once(__DIR__ . '/../helpers/forBloomFilter.php');

class Part3Service implements StepInterface
{

    public function Postgres_Postgres(){

        $forBloomFilter = new forBloomFilter();

        $cbfA = $forBloomFilter->getCBF(1, "CBF", "postgres");
        $cbfB = $forBloomFilter->getCBF(2, "CBF", "postgres");

        $flag = false;

        if ($cbfA == $cbfB){
            $flag = true;
        }

        return $flag;

    }


    public function Postgres_Oracle()
    {

        $forBloomFilter = new forBloomFilter();

        $cbfA = $forBloomFilter->getCBF(1, "CBF", "postgres");
        $cbfB = $forBloomFilter->getCBF(2, "CBF","oracle");

        $flag = false;

        if ($cbfA == $cbfB){
            $flag = true;
        }

        return $flag;

    }

    public function Oracle_Oracle()
    {

        $forBloomFilter = new forBloomFilter();

        $cbfA = $forBloomFilter->getCBF(1, "CBF", "oracle");
        $cbfB = $forBloomFilter->getCBF(2, "CBF", "oracle");

        $flag = false;

        if ($cbfA == $cbfB){
            $flag = true;
        }

        return $flag;

    }

}