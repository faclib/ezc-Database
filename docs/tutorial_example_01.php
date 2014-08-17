<?php

require_once __DIR__ . '/tutorial_autoload.php';

$db = ezcDbFactory::create( 'mysql://root@localhost/test' );
ezcDbInstance::set( $db );

// anywhere later in your program you can retrieve the db instance again using
$db = ezcDbInstance::get();




