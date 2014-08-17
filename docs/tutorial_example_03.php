<?php

require_once __DIR__ . '/tutorial_example_01.php';

$db = ezcDbInstance::get();
$stmt = $db->prepare( 'SELECT * FROM quotes where author = :author' );
$stmt->bindValue( ':author', 'Robert Foster' );

$stmt->execute();
$rows = $stmt->fetchAll();

print_r($rows);


