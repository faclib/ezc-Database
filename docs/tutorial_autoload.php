<?php
$files = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../../../vendor/autoload.php',
);
foreach ($files as $file) {
    if (file_exists($file)) {
        require_once $file;
        break;
    }
}

$dir = dirname( __FILE__ );
$dirParts = explode( DIRECTORY_SEPARATOR, $dir );
if (!class_exists('ezcBase')) {
    switch ( $dirParts[count( $dirParts ) - 3] )
    {
        case 'docs': require_once 'ezc/Base/base.php'; break; // pear
        case 'trunk': require_once "$dir/../../tutorial_autoload.php"; break; // svn
        default: require_once "$dir/../../tutorial_autoload.php"; break; // bundle
    }
}

/**
 * Autoload ezc classes 
 * 
 * @param string $className 
 */
function __autoload( $className )
{
    ezcBase::autoload( $className );
}
?>
