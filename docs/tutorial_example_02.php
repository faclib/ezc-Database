<?php

require_once __DIR__ . '/tutorial_example_01.php';

$db = ezcDbInstance::get();

$rows = $db->query( 'SELECT * FROM quotes' );

// Iterate over the rows and print the information from each result.
foreach( $rows as $row )
{
    print_r( $row );
}

