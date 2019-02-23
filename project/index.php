<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 20.02.2019
 * Time: 22:27
 */

require_once './services/Part1Service.php';
require_once './services/Part2Service.php';

$part1 = new Part1Service();

$part2 = new Part2Service();

if ($part1->doIt()){
    echo 'TRUE ';
}
else{
    echo 'FALSE ';
};

if ($part2->doIt()){
    echo 'TRUE ';
}
else{
    echo 'FALSE ';
};





