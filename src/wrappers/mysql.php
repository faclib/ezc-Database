<?php
/**
 * mysql class  - mysql.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


/**
 * MySQL driver implementation
 *
 * @see ezcDbWrapper
 * @package Database
 * @version 1.4.7
 */
class ezcDbWrapperMysql extends ezcDbWrapper
{
    /**
     * Characters to quote identifiers with. Should be overwritten in handler
     * implementation, if different for a specific handler. In some cases the
     * quoting start and end characters differ (e.g. ODBC), but mostly they are
     * the same.
     *
     * @var string
     */
    protected $identifierQuoteChars = array(
        "start" => '`',
        "end"   => '`',
    );

    /**
     * Constructs a handler object from the parameters $db.
     *
     * @throws ezcDbMissingParameterException if the database name was not specified.
     * @param  PDO $db Database connection parameters.
     */
    public function __construct( $db )
    {
        parent::__construct( $db );
    }

    /**
     * Returns 'mysql'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'mysql';
    }

    /**
     * Returns true if $feature is supported by MySQL.
     *
     * @apichange Never implemented properly, no good use (See #10937)
     * @ignore
     * @param array(string) $feature
     * @return bool
     */
    static public function hasFeature( $feature )
    {
        $supportedFeatures = array( 'multi-table-delete', 'cross-table-update' );
        return in_array( $feature, $supportedFeatures );
    }

    /**
     * Returns a new ezcUtilities derived object for this database instance.
     *
     * @return ezcUtilitiesMysql
     */
    public function createUtilities()
    {
        return new ezcDbUtilitiesMysql( $this );
    }
}
