<?php
/**
 * sqlite class  - sqlite.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


/**
 * SQLite driver implementation
 *
 * @see ezcDbWrapper
 * @package Database
 * @version 1.4.7
 */
class ezcDbWrapperSqlite extends ezcDbWrapper
{
    /**
     * Constructs a handler object from the parameters $dbParams.
     *
     * Supported database parameters are:
     * - dbname|database: Database name
     * - port:            If "memory" is used then the driver will use an
     *                    in-memory database, and the database name is ignored.
     *
     * @throws ezcDbMissingParameterException if the database name was not specified.
     * @param array $dbParams Database connection parameters (key=>value pairs).
     */
    public function __construct( $db )
    {
        parent::__construct( $db );

        /* Register PHP implementations of missing functions in SQLite */
        /*
        $this->sqliteCreateFunction( 'md5', array( 'ezcQuerySqliteFunctions', 'md5Impl' ), 1 );
        $this->sqliteCreateFunction( 'mod', array( 'ezcQuerySqliteFunctions', 'modImpl' ), 2 );
        $this->sqliteCreateFunction( 'locate', array( 'ezcQuerySqliteFunctions', 'positionImpl' ), 2 );
        $this->sqliteCreateFunction( 'floor', array( 'ezcQuerySqliteFunctions', 'floorImpl' ), 1 );
        $this->sqliteCreateFunction( 'ceil', array( 'ezcQuerySqliteFunctions', 'ceilImpl' ), 1 );
        $this->sqliteCreateFunction( 'concat', array( 'ezcQuerySqliteFunctions', 'concatImpl' ) );
        $this->sqliteCreateFunction( 'toUnixTimestamp', array( 'ezcQuerySqliteFunctions', 'toUnixTimestampImpl' ), 1 );
        $this->sqliteCreateFunction( 'now', 'time', 0 );
        /* */
    }

    /**
     * Returns 'sqlite'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'sqlite';
    }

    /**
     * Returns true if $feature is supported by this db handler.
     *
     * @apichange Never implemented properly, no good use (See #10937)
     * @ignore
     * @param string $feature
     * @return array(string)
     */
    static public function hasFeature( $feature )
    {
        $supportedFeatures = array( 'multi-table-delete', 'cross-table-update' );
        return in_array( $feature, $supportedFeatures );
    }

    /**
     * Returns a new ezcQuerySelect derived object with SQLite implementation specifics.
     *
     * @return ezcQuerySelectSqlite
     */
    public function createSelectQuery()
    {
        return new ezcQuerySelectSqlite( $this );
    }

    /**
     * Returns a new ezcQueryExpression derived object with SQLite implementation specifics.
     *
     * @return ezcQueryExpressionqSqlite
     */
    public function createExpression()
    {
        return new ezcQueryExpressionSqlite( $this->getDb() );
    }

    /**
     * Returns a new ezcUtilities derived object with SQLite implementation specifics.
     *
     * @return ezcUtilitiesSqlite
     */
    public function createUtilities()
    {
        return new ezcDbUtilitiesSqlite( $this );
    }
}
