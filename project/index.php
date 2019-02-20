<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 20.02.2019
 * Time: 22:27
 */

require_once './services/Part1Service.php';

$part = new Part1Service();

if ($part->doIt()){
    echo 'TRUE';
}
else{
    echo 'FALSE';
};






