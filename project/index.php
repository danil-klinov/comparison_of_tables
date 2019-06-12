<?php
/**
 * Created by PhpStorm.
 * User: Даниил
 * Date: 20.02.2019
 * Time: 22:27
 */

ini_set('memory_limit', '6024M');

require_once './services/Part1Service.php';
require_once './services/Part2Service.php';
require_once './services/Part3Service.php';
require_once './services/Part4Service.php';
require_once './services/LineByLine.php';
require_once './helpers/Info.php';
require_once './helpers/forBloomFilter.php';

$type1 = trim(mb_strtolower($_POST["type1"]));
$type2 = trim(mb_strtolower($_POST["type2"]));
$name1 = trim($_POST["name1"]);
$name2 = trim($_POST["name2"]);
$host1 = trim($_POST["host1"]);
$host2 = trim($_POST["host2"]);
$port1 = trim($_POST["port1"]);
$port2 = trim($_POST["port2"]);
$user1 = trim($_POST["user1"]);
$user2 = trim($_POST["user2"]);
$password1 = trim($_POST["password1"]);
$password2 = trim($_POST["password2"]);
$schema1 = trim($_POST["schema1"]);
$schema2 = trim($_POST["schema2"]);
$table1 = trim($_POST["table1"]);
$table2 = trim($_POST["table2"]);
$p = trim($_POST["p"]);
$countOfCheck = trim($_POST["countOfCheck"]);

$fd = fopen("config/config.php", "w");
fclose($fd);

$str[0] = "<?php";
$str[1] = "\$xmlstr = <<<XML";
$str[2] = "<?xml version='1.0' standalone='yes'?>";
$str[3] = "<configurations>";
$str[4] = " <config>";
$str[5] = "  <type1>";
$str[6] = "  " . $type1;
$str[7] = "  </type1>";
$str[8] = "  <type2>";
$str[9] = "  " . $type2;
$str[10] = "  </type2>";
$str[11] = "  <host1>";
$str[12] = "  " . $host1;
$str[13] = "  </host1>";
$str[14] = "  <host2>";
$str[15] = "  " . $host2;
$str[16] = "  </host2>";
$str[17] = "  <port1>";
$str[18] = "  " . $port1;
$str[19] = "  </port1>";
$str[20] = "  <port2>";
$str[21] = "  " . $port2;
$str[22] = "  </port2>";
$str[23] = "  <dbname1>";
$str[24] = "  " . $name1;
$str[25] = "  </dbname1>";
$str[26] = "  <dbname2>";
$str[27] = "  " . $name2;
$str[28] = "  </dbname2>";
$str[29] = "  <schema1>";
$str[30] = "  " . $schema1;
$str[31] = "  </schema1>";
$str[32] = "  <schema2>";
$str[33] = "  " . $schema2;
$str[34] = "  </schema2>";
$str[35] = "  <table1>";
$str[36] = "  " . $table1;
$str[37] = "  </table1>";
$str[38] = "  <table2>";
$str[39] = "  " . $table2;
$str[40] = "  </table2>";
$str[41] = "  <user1>";
$str[42] = "  " . $user1;
$str[43] = "  </user1>";
$str[44] = "  <user2>";
$str[45] = "  " . $user2;
$str[46] = "  </user2>";
$str[47] = "  <password1>";
$str[48] = "  " . $password1;
$str[49] = "  </password1>";
$str[50] = "  <password2>";
$str[51] = "  " . $password2;
$str[52] = "  </password2>";
$str[53] = "  <p>";
$str[54] = "  " . $p;
$str[55] = "  </p>";
$str[56] = "  <countOfCheck>";
$str[57] = "  " . $countOfCheck;
$str[58] = "  </countOfCheck>";
$str[59] = " </config>";
$str[60] = "</configurations>";
$str[61] = "XML;";
$str[62] = "?>";

$fd = fopen("config/config.php", 'a+');

for ($i = 0; $i < 63; $i++){
    if ($str[$i] != "  ") {
        fwrite($fd, $str[$i] . PHP_EOL);
    }
}

fclose($fd);

sleep(1);

$part1 = new Part1Service();
$part2 = new Part2Service();
$part3 = new Part3Service();
$part4 = new Part4Service();
$lineByLine = new LineByLine();

$info = new Info();
$type = $info->getType();
$forBloomFilter = new forBloomFilter();

$n = $forBloomFilter->getN();
$a = $forBloomFilter->getA();

$step1 = false;
$step2 = false;
$step3 = false;
$step4 = false;

if ($type[0] == "postgresql" && $type[1] == "postgresql"){

    $start = microtime(true);
    $step1 = $part1->Postgres_Postgres();
    $finish = microtime(true);

    $start = $finish;
    if ($step1) { $step2 = $part2->Postgres_Postgres(); }
    $finish = microtime(true);

    $start = $finish;
    if ($step2) { $step3 = $part3->Postgres_Postgres(); }
    $finish = microtime(true);

    $start = $finish;
    if ($step3) { $step4 = $part4->Postgres_Postgres(); }
    $finish = microtime(true);

    $start = $finish;
    $lineByLine->Postgres_Postgres();
    $finish = microtime(true);

}

if ($type[0] == "postgresql" && $type[1] == "oracle"){
    $step1 = $part1->Postgres_Oracle();
    $step2 = $part2->Postgres_Oracle();
    $step3 = $part3->Postgres_Oracle();
    $step4 = $part4->Postgres_Oracle();
}

if ($type[0] == "oracle" && $type[1] == "oracle"){
    $step1 = $part1->Oracle_Oracle();
    $step2 = $part2->Oracle_Oracle();
    $step3 = $part3->Oracle_Oracle();
    $step4 = $part4->Oracle_Oracle();
}

$report = false;

if ($step1 && $step2 && $step3 && $step4){
    $report = true;
}

include "./index.html";






