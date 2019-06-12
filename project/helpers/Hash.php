<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 03.05.2019
 * Time: 17:03
 */

class Hash
{

    public function getHash($n){
        $hashAll = array();
        $hash = array();
        $hashAll = hash_algos();
        for ($i = 0; $i < $n; $i++){
            $hash[$i] = $hashAll[$i];
        }
        return $hash;
    }

}