<?php

require_once __DIR__ . '/tutorial_example_01.php';

$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');

// $db = new ezcDbWrapperMysql($pdo);
$db = ezcDbFactory::wrapper($pdo);

ezcDbInstance::set( $db, 'wrapper' );
ezcDbInstance::chooseDefault('wrapper');

$db = ezcDbInstance::get();
$res = $db->getAttribute(PDO::ATTR_DRIVER_NAME);

print_r($db);
print_r($res);



echo "\n----\n";



$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');

// $db = new ezcDbWrapperMysql($pdo);
$db = ezcDbFactory::create($pdo);

ezcDbInstance::set( $db, 'wrapper-1' );
ezcDbInstance::chooseDefault('wrapper-1');
$db = ezcDbInstance::get();
$res = $db->getAttribute(PDO::ATTR_DRIVER_NAME);

print_r($db);
print_r($res);
