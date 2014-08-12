<?php

require_once __DIR__ . '/tutorial_example_01.php';

ezcDbInstance::chooseDefault('wrapper');
$db = ezcDbInstance::get();

// $res = $db->getAttribute(PDO::ATTR_DRIVER_NAME);

// print_r($res);
//
$pdo = new PDO('sqlite::memory:');

$db = ezcDbFactory::wrapper($pdo);
print_r($db->getName());


$db = ezcDbFactory::wrapper(ezcDbInstance::get());
print_r($db->getName());

// $db = new ezcDbWrapperMysql($pdo);
// $res = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

// print_r($res);
//
// print_r($pdo);


// $r = $pdo->query("
// CREATE TABLE `quotes` (
//   `id` int(11) NOT NULL ,
//   `name` varchar(50) NOT NULL,
//   `quote` varchar(50) NOT NULL,
//   `author` varchar(50)  DEFAULT NULL
//   )");
// // print_r($pdo->errorInfo());
// // print_r($r);
// echo "----";



// // print_r($pdo->errorInfo());
// // print_r($res->fetchAll());

// $db = new ezcDbWrapperSqlite($pdo);

// $res = $pdo->query("SELECT * FROM SQLITE_MASTER");
// print_r($res->fetchAll());