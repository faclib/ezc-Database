<?php
/**
 * oracle class  - oracle.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


/**
 * Oracle driver implementation
 *
 * @see ezcDbWrapper
 * @package Database
 * @version 1.4.7
 */

class ezcDbWrapperOracle extends ezcDbWrapper
{
    /**
     * Constructs a handler object from the parameters $db.
     *
     * @param PDO $db Database connection .
     * @throws ezcDbMissingParameterException if the database name was not specified.
     */
    public function __construct( $db )
    {
        parent::__construct( $db );
    }

    /**
     * Returns 'oracle'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'oracle';
    }

    /**
     * Returns a new ezcQuerySelect derived object with Oracle implementation specifics.
     *
     * @return ezcQuerySelectOracle
     */
    public function createSelectQuery()
    {
        return new ezcQuerySelectOracle( $this );
    }

    /**
     * Returns a new ezcQueryExpression derived object with Oracle implementation specifics.
     *
     * @return ezcQueryExpressionPgsql
     */
    public function createExpression()
    {
        return new ezcQueryExpressionOracle( $this->getDb()  );
    }

    /**
     * Returns a new ezcUtilities derived object with Oracle implementation specifics.
     *
     * @return ezcUtilitiesOracle
     */
    public function createUtilities()
    {
        return new ezcDbUtilitiesOracle( $this );
    }

    /**
     * Returns an SQL query with LIMIT/OFFSET functionality appended.
     *
     * The LIMIT/OFFSET is added to $queryString.
     * $limit controls the maximum number of entries in the resultset.
     * $offset controls where in the resultset results should be
     * returned from.
     *
     * @param string $queryString
     * @param int $limit
     * @param int $offset
     * @return string
     */
    protected function processLimitOffset( $queryString, $limit, $offset )
    {
        if ( isset( $limit ) )
        {
            if ( !isset( $offset ) )
            {
                $offset = 0;
            }

            $min = $offset+1;
            $max = $offset+$limit;

            $queryString = "SELECT * FROM (SELECT a.*, ROWNUM rn FROM ($queryString) a WHERE rownum <= $max) WHERE rn >= $min";
        }

        return $queryString;
    }

    /**
     * Returns $str quoted for the Oracle database.
     *
     * Reimplemented from PDO since PDO is broken using Oracle8.
     *
     * @param string $str
     * @param int $paramStr
     * @return string
     */
    public function quote( $str, $paramStr = PDO::PARAM_STR )
    {
        // looks like PDO::quote() does not work properly with oci8 driver.

        $str = str_replace( "'", "''", $str );
        return "'$str'";
    }
}
