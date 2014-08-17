<?php
/**
 * wrapper class  - wrapper.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * Определяет интерфейс для всех реализаций драйверов баз данных.
 *
 * @author  Dmitriy Tyurin <fobia3d@gmail.com>
 * @package Database
 */
abstract class ezcDbWrapper implements ezcDbInterface
{
    /**
     * Stores the transaction nesting level.
     *
     * @var int
     */
    protected $transactionNestingLevel = 0;

    /**
     * This flag is set to true when an SQL query has failed.
     * In this case the transaction should be rolled back.
     *
     * @var bool
     */
    protected $transactionErrorFlag = false;

    /**
     * Characters to quote identifiers with. Should be overwritten in handler
     * implementation, if different for a specific handler. In some cases the
     * quoting start and end characters differ (e.g. ODBC), but mostly they are
     * the same.
     *
     * @var string
     */
    protected $identifierQuoteChars = array(
        "start" => '"',
        "end"   => '"',
    );

    /**
     * Указатель на обработчик базы данных, чтобы использовать для этого запроса.
     *
     * @var PDO
     */
    protected $db;

    /**
     * Constructs a handler object.
     *
     * note: Remember to always call this constructor from constructor of a derived class!
     *
     * @param PDO   $db       Misc database connection parameters.
     */
    public function __construct( PDO $db )
    {
        $this->db = $db;
    }


    /**
     * Constructs a handler object.
     *
     * note: Remember to always call this constructor from constructor of a derived class!
     *
     * @see PDO::__construct()
     * @param array  $dbParams Misc database connection parameters.
     * @param string $dsn      Data Source Name, generated by constructor
     *                         of a derived class.
     */
    protected function createPDO( $dbParams, $dsn )
    {
        $user          = null;
        $pass          = null;
        $driverOptions = null;

        foreach ( $dbParams as $key => $val )
        {
            switch ( $key )
            {
                case 'user':
                case 'username':
                    $user = $val;
                    break;

                case 'pass':
                case 'password':
                    $pass = $val;
                    break;

                case 'driver-opts':
                    $driverOptions = $val;
                    break;
            }
        }

        $db = new PDO( $dsn, $user, $pass, $driverOptions );

        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $db->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );

        return $db;
    }

    /**
     * @return PDO
     */
    public function getDb()
    {
        return $this->db;
    }


    public function __call($name, $args)
    {
        switch (@count($args)) {
            case 0: return $this->db->{$name}();
            case 1: return $this->db->{$name}($args[0]);
            case 2: return $this->db->{$name}($args[0], $args[1]);
            case 3: return $this->db->{$name}($args[0], $args[1], $args[2]);
            default: return call_user_func_array(array($this->db, $name), $args);
        }
        // switch ($name) {
        //     case 'exec':
        //     case 'query':
        //     case 'quote':
        //     case 'prepare':
        //     case 'errorCode':
        //     case 'errorInfo':
        //     case 'getAttribute':
        //     case 'getAvailableDrivers':
        //     case 'inTransaction':
        //     case 'lastInsertId':
        //     case 'setAttribute':
        //     default:
        //         return $this->dispatchMethod($name, $args);
        //         break;
        // }
    }


    // protected function dispatchMethod($method, array $p = null)
    // {
    //     switch (@count($p)) {
    //         case 0: return $this->db->{$method}();
    //         case 1: return $this->db->{$method}($p[0]);
    //         case 2: return $this->db->{$method}($p[0], $p[1]);
    //         case 3: return $this->db->{$method}($p[0], $p[1], $p[2]);
    //         default: return call_user_func_array(array($this->db, $method), $p);
    //     }
    // }

    /**
     * Returns true if the given $feature is supported by the handler.
     *
     * Derived classes should override this method to report
     * the features they support.
     *
     * @apichange Never implemented properly, no good use (See #10937)
     * @ignore
     * @param string $feature
     * @return bool
     */
    static public function hasFeature( $feature )
    {
        return false;
    }

    /**
     * Begins a transaction.
     *
     * This method executes a begin transaction query unless a
     * transaction has already been started (transaction nesting level > 0 )
     *
     * Each call to beginTransaction() must have a corresponding commit() or
     * rollback() call.
     *
     * @see commit()
     * @see rollback()
     * @return bool
     */
    public function beginTransaction()
    {
        $retval = true;
        if ( $this->transactionNestingLevel == 0 )
        {
            $retval = $this->db->beginTransaction();
        }
        // else NOP

        $this->transactionNestingLevel++;
        return $retval;
    }

    /**
     * Commits a transaction.
     *
     * If this this call to commit corresponds to the outermost call to
     * beginTransaction() and all queries within this transaction were
     * successful, a commit query is executed. If one of the queries returned
     * with an error, a rollback query is executed instead.
     *
     * This method returns true if the transaction was successful. If the
     * transaction failed and rollback was called, false is returned.
     *
     * @see beginTransaction()
     * @see rollback()
     * @return bool
     */
    public function commit()
    {
        if ( $this->transactionNestingLevel <= 0 )
        {
            $this->transactionNestingLevel = 0;

            throw new ezcDbTransactionException( "commit() called before beginTransaction()." );
        }

        $retval = true;
        if ( $this->transactionNestingLevel == 1 )
        {
            if ( $this->transactionErrorFlag )
            {
                $this->db->rollback();
                $this->transactionErrorFlag = false; // reset error flag
                $retval = false;
            }
            else
            {
                $this->db->commit();
            }
        }
        // else NOP

        $this->transactionNestingLevel--;
        return $retval;
    }

    /**
     * Rollback a transaction.
     *
     * If this this call to rollback corresponds to the outermost call to
     * beginTransaction(), a rollback query is executed. If this is an inner
     * transaction (nesting level > 1) the error flag is set, leaving the
     * rollback to the outermost transaction.
     *
     * This method always returns true.
     *
     * @see beginTransaction()
     * @see commit()
     * @return bool
     */
    public function rollback()
    {
        if ( $this->transactionNestingLevel <= 0 )
        {
            $this->transactionNestingLevel = 0;
            throw new ezcDbTransactionException( "rollback() called without previous beginTransaction()." );
        }

        if ( $this->transactionNestingLevel == 1 )
        {
            $this->db->rollback();
            $this->transactionErrorFlag = false; // reset error flag
        }
        else
        {
            // set the error flag, so that if there is outermost commit
            // then ROLLBACK will be done instead of COMMIT
            $this->transactionErrorFlag = true;
        }

        $this->transactionNestingLevel--;
        return true;
    }

    /**
     * Returns a new ezcQuerySelect derived object for the correct database type.
     *
     * @return ezcQuerySelect
     */
    public function createSelectQuery()
    {
        return new ezcQuerySelect( $this );
    }

    /**
     * Returns a new ezcQueryUpdate derived object for the correct database type.
     *
     * @return ezcQueryUpdate
     */
    public function createUpdateQuery()
    {
        return new ezcQueryUpdate( $this );
    }

    /**
     * Returns a new ezcQueryInsert derived object for the correct database type.
     *
     * @return ezcQueryInsert
     */
    public function createInsertQuery()
    {
        return new ezcQueryInsert( $this );
    }

    /**
     * Returns a new ezcQueryDelete derived object for the correct database type.
     *
     * @return ezcQueryDelete
     */
    public function createDeleteQuery()
    {
        return new ezcQueryDelete( $this );
    }

    /**
     * Returns a new ezcQueryExpression derived object for the correct database type.
     *
     * @return ezcQueryExpression
     */
    public function createExpression()
    {
        return new ezcQueryExpression( $this );
    }

    /**
     * Returns a new ezcUtilities derived object for the correct database type.
     *
     * @return ezcDbUtilities
     */
    public function createUtilities()
    {
        return new ezcDbUtilities( $this );
    }

    /**
     * Returns the quoted version of an identifier to be used in an SQL query.
     * This method takes a given identifier and quotes it, so it can safely be
     * used in SQL queries.
     *
     * @param string $identifier The identifier to quote.
     * @return string The quoted identifier.
     */
    public function quoteIdentifier( $identifier )
    {
        if ( sizeof( $this->identifierQuoteChars ) === 2 )
        {
            $identifier =
                $this->identifierQuoteChars["start"]
                . str_replace(
                    $this->identifierQuoteChars["end"],
                    $this->identifierQuoteChars["end"].$this->identifierQuoteChars["end"],
                    $identifier
                  )
                . $this->identifierQuoteChars["end"];
        }
        return $identifier;
    }
}