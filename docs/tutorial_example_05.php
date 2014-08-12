<?php

require_once __DIR__ . '/tutorial_example_01.php';

$db = ezcDbInstance::get();

$q = $db->createSelectQuery();
$q->select( '*' )->from( 'quotes' );

$stmt = $q->prepare();
$stmt->execute();

