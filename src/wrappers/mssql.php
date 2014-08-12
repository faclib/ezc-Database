<?php
/**
 * mssql class  - mssql.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


/**
 * MS SQL Server driver implementation.
 *
 * @see ezcDbWrapper
 * @package Database
 * @version 1.4.7
 */
class ezcDbWrapperMssql extends ezcDbWrapper
{
    /**
     * Contains the options that are used to set up handler.
     *
     * @var ezcDbMssqlOptions
     */
    public $options;

    /**
     * Constructs a handler object from the parameters $db.
     *
     * @param PDO $db Database connection .
     * @throws ezcDbMissingParameterException if the database name was not specified.
     */
    public function __construct( PDO $db )
    {

        parent::__construct( $db );

        // setup options
        // $this->setOptions( new ezcDbMssqlOptions() );
    }

    /**
     * Associates an option object with this handler and changes settings for
     * opened connections.
     *
     * @param ezcDbMssqlOptions $options
     */
    public function setOptions( ezcDbMssqlOptions $options )
    {
        $this->options = $options;
        $this->setupConnection();
    }

    /**
     * Sets up opened connection according to options.
     */
    private function setupConnection()
    {
        $requiredMode = $this->options->quoteIdentifier;
        if ( $requiredMode == ezcDbMssqlOptions::QUOTES_GUESS )
        {
            $result = $this->db->query( "SELECT sessionproperty('QUOTED_IDENTIFIER')" );
            $rows = $result->fetchAll();
            $mode = (int)$rows[0][0];
            if ( $mode == 0 )
            {
                $this->identifierQuoteChars = array( 'start' => '[', 'end' => ']' );
            }
            else
            {
                $this->identifierQuoteChars = array( 'start' => '"', 'end' => '"' );
            }
        }
        else if ( $requiredMode == ezcDbMssqlOptions::QUOTES_COMPLIANT )
        {
            $this->db->exec( 'SET QUOTED_IDENTIFIER ON' );
            $this->identifierQuoteChars = array( 'start' => '"', 'end' => '"' );
        }
        else if ( $requiredMode == ezcDbMssqlOptions::QUOTES_LEGACY )
        {
            $this->db->exec( 'SET QUOTED_IDENTIFIER OFF' );
            $this->identifierQuoteChars = array( 'start' => '[', 'end' => ']' );
        }
    }

    /**
     * Returns a new ezcQueryExpression derived object with SQL Server
     * implementation specifics.
     *
     * @return ezcQueryExpressionMssql
     */
    public function createExpression()
    {
        return new ezcQueryExpressionMssql( $this->getDb()  );
    }

    /**
     * Returns 'mssql'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'mssql';
    }

    /**
     * Returns a new ezcQuerySelectMssql derived object with SQL Server
     * implementation specifics.
     *
     * @return ezcQuerySelectMssql
     */
    public function createSelectQuery()
    {
        return new ezcQuerySelectMssql( $this );
    }

    /**
     * Begins a transaction.
     *
     * This method executes a begin transaction query unless a
     * transaction has already been started (transaction nesting level > 0 ).
     *
     * Each call to begin() must have a corresponding commit() or rollback() call.
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
            $retval = $this->db->exec( "BEGIN TRANSACTION" );
        }
        // else NOP

        $this->transactionNestingLevel++;
        return $retval;
    }

    /**
     * Commits a transaction.
     *
     * If this this call to commit corresponds to the outermost call to
     * begin() and all queries within this transaction were successful,
     * a commit query is executed. If one of the queries
     * returned with an error, a rollback query is executed instead.
     *
     * This method returns true if the transaction was successful. If the
     * transaction failed and rollback was called, false is returned.
     *
     * @see begin()
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
                $this->db->exec( "ROLLBACK TRANSACTION" );
                $this->transactionErrorFlag = false; // reset error flag
                $retval = false;
            }
            else
            {
                $this->db->exec( "COMMIT TRANSACTION" );
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
     * begin(), a rollback query is executed. If this is an inner transaction
     * (nesting level > 1) the error flag is set, leaving the rollback to the
     * outermost transaction.
     *
     * This method always returns true.
     *
     * @see begin()
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
            $this->db->exec( "ROLLBACK TRANSACTION" );
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
}
