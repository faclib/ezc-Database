<?php
/**
 * pgsql class  - pgsql.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


/**
 * PostgreSQL driver implementation
 *
 * @see ezcDbWrapper
 * @package Database
 * @version 1.4.7
 */
class ezcDbWrapperPgsql extends ezcDbWrapper
{
    /**
     * Constructs a handler object from the parameters $db.
     *
     * @throws ezcDbMissingParameterException if the database name was not specified.
     * @param  PDO $db Database connection .
     */
    public function __construct( $db )
    {
        parent::__construct( $db );
    }

    /**
     * Returns 'pgsql'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'pgsql';
    }

    /**
     * Returns a new ezcQueryExpression derived object with PostgreSQL implementation specifics.
     *
     * @return ezcQueryExpressionPgsql
     */
    public function createExpression()
    {
        return new ezcQueryExpressionPgsql( $this->getDb() );
    }

    /**
     * Returns a new ezcUtilities derived object with PostgreSQL implementation specifics.
     *
     * @return ezcUtilitiesPgsql
     */
    public function createUtilities()
    {
        return new ezcDbUtilitiesPgsql( $this );
    }
}
