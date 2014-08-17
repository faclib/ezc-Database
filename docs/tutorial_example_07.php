<?php

require_once __DIR__ . '/tutorial_example_01.php';

$db = ezcDbInstance::get();

$q = $db->createSelectQuery();

// Left join of two tables. Will produce SQL:
// "SELECT id FROM table1 LEFT JOIN table2 ON table1.id = table2.id".
$q->select( 'id' )->from( 'table1' )->leftJoin( 'table2', $q->expr->eq( 'table1.id', 'table2.id' ) );

$stmt = $q->prepare();
echo $stmt->queryString;
//$stmt->execute();
