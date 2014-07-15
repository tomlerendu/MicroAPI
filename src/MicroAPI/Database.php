<?php

namespace MicroAPI;

use PDO;

class Database
{
	private $connection;
    private $fetchMode;

    /**
     * A wrapper class for PDO
     *
     * @param $dsn - Data source name, the required information to connect to the database
     * @param $user - The username to connect to the database
     * @param $pass - The password to connect to the database
     * @param $fetchMode - The fetch mode to use when using select statements
     */
	public function __construct($dsn, $user, $pass, $fetchMode)
	{
        $this->fetchMode = $fetchMode;
		$this->connect($dsn, $user, $pass);
	}

    /**
     * Make a connection to the database
     *
     * @param $dsn - Data source name, the required information to connect to the database
     * @param $user - The username to connect to the database
     * @param $pass - The password to connect to the database
     * @throws Exception
     */
    public function connect($dsn, $user, $pass)
    {
        //Attempt to make a connection to the database
        try
        {
            $this->connection = new PDO($dsn, $user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(\Exception $e)
        {
            $this->connection = null;
            //Throw a more generic message to avoid database connection details leaking
            throw new Exception('Error connecting to the database');
        }
    }

	/**
	 * Performs a query on the database.
	 * 
	 * @param query - The query to be performed on the database
     * @param params - The parameters to be added to the query
	 * @return - An executed database statement
     * @throws Exception
	 */
	public function query($query, $params)
	{
		//Make sure a database connection exists
        if($this->connection !== null)
        {
            //If only one data value was passed in wrap it in an array
            if(!is_array($params))
                $params = [$params];

            $statement = $this->connection->prepare($query);

            //If no parameters were passed
            if($params === '')
                $statement->execute();
            //If parameters were passed
            else
                $statement->execute($params);

            return $statement;
        }
        else
            throw new Exception('No database connection is active');
	}

    /**
     * Perform a select query on the database and returns a statement ready to fetch
     *
     * @param $query - The query to be performed on the database
     * @param array $params - The parameters to be added to the query
     * @param int $fetchMode - The PDO fetch mode to use
     */
    public function select($query, $params=[], $fetchMode=null)
    {
        //If a fetch mode was defined use it, else use the one defined in the config
        if($fetchMode === null)
            $fetchMode = $this->fetchMode;

        //Make the query
        $statement = $this->query($query, $params);

        return $statement;
    }

    /**
     * Perform a select query on the database and returns an 2d array of rows
     *
     * @param $query - The query to be performed on the database
     * @param array $params - The parameters to be added to the query
     * @param int $fetchMode - The PDO fetch mode to use
     */
    public function selectAll($query, $params=[], $fetchMode=null)
	{
		//If a fetch mode was defined use it, else use the one defined in the config
        if($fetchMode === null)
            $fetchMode = $this->fetchMode;

		//Make the query
		$statement = $this->query($query, $params);
		//Fetch the results from the database
		$results = $statement->fetchAll($fetchMode);

		return $results;
	}

    /**
     * Perform an insert query on the database
     *
     * @param $query - The query to be performed, should begin with "INSERT"
     * @param array $params - Parameters to be added to the query
     * @return int - The ID of the inserted row in the database
     */
    public function insert($query, $params=[])
	{
		//Make the query
		$this->query($query, $params);
		//Return the ID of the inserted row
		return $this->connection->lastInsertId();
	}

    /**
     * Perform an update query on the database
     *
     * @param $query - The query to be performed, should begin with "UPDATE"
     * @param array $params - Parameters to be added to the query
     * @return int - The number of rows the query effected
     */
	public function update($query, $params=[])
	{
		//Make the query
		$query = $this->query($query, $params);
		//Return the number of affected rows
		return $query->rowCount();
	}

    /**
     * Perform a delete query on the database
     *
     * @param $query - The query to be performed, should begin with "DELETE"
     * @param array $params - Parameters to be added to the query
     * @return int - The number of rows the query effected
     */
    public function delete($query, $params=[])
	{
		//Make and return the query
		return $this->update($query, $params);
	}

    /**
     * Begin a database transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit a database transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rollback a database transaction
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    /**
	 * Access the database directly
	 *
	 * @return - The PDO database object
	 */
	public function getConnection()
	{
		return (isset($this->connection)) ? $this->connection : null;
	}
}