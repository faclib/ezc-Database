<?php
/**
 * interface class  - interface.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
/**
 *
 * @author Dmitriy Tyurin <fobia3d@gmail.com>
 */
interface ezcDbInterface
{
    public function getDb();

    public function beginTransaction();

    public function commit();

    public function rollback();

    public function createSelectQuery();

    public function createUpdateQuery();

    public function createInsertQuery();

    public function createDeleteQuery();

    public function createExpression();

    public function createUtilities();

    public function quoteIdentifier( $identifier );

}

// interface ezcDbPDOInterface
// {
    // public function exec($statement);
    // public function query($statement);

    // public function quote($string, $parameter_type = \PDO::PARAM_STR);

    // public function prepare($statement, array $driver_options = array());

    // public function errorCode();
    // public function errorInfo();
    // public function getAttribute(  $attribute );
    // public function getAvailableDrivers();
    // public function inTransaction();
    // public function lastInsertId( $name = NULL );
    // public function setAttribute( $attribute , $value );
// }

