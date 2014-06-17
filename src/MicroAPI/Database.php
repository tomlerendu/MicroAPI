<?php

namespace MicroAPI;

use Exception;
use PDO;

class Database
{
	//PDO database connection
	private $connection;

	public function __construct($host, $name, $user, $pass)
	{
		//If a connection already exists return
		if(isset($connection))
			return;

		//Attempt to make a connection to the database
		try
		{
			$this->connection = new PDO('mysql:host=' . $host . ';dbname=' . $name, $user, $pass);
		}
		catch(Exception $e)
		{
			//Throw a more generic message to avoid database connection details leaking
			throw new Exception('Error connecting to the database');
            $this->connection = null;
		}
	}

	/**
	 * Performs a query on the database.
	 * 
	 * @param query
     * @param params
	 * @return - An executed database statement
	 */
	public function query($query, $params)
	{
		//Make sure a database connection exists
        if(isset($connection))
        {
            //If only one data value was passed in wrap it in an array
            if(!is_array($params))
                $params = [$params];

            $statement = $this->connection->prepare($query);

            //If no parameters were passed...
            if($params == '')
                $statement->execute();
            else
                $statement->execute($params);

            return $statement;
        }
        else
        {

        }
	}

    /**
     * @param $query
     * @param array $params
     * @param array $options
     * @return mixed
     */
    public function select($query, $params=[], $options=[])
	{
		//If a fetch mode was defined use it, else use the one defined in the config file
		$fetchMode = (isset($options['fetch'])) ? $options['fetch'] : $this->config['fetch'];

		//Make the query
		$statement = $this->query($query, $params);
		//Fetch the results from the database
		$results = $statement->fetchAll($fetchMode);

		return $results;
	}

	/**
	* Performs an insert query
	*
	* @return The primary key of the row inserted
	*/
	public function insert($query, $params=[])
	{
		//Make the query
		$this->query($query, $params);
		//Return the ID of the inserted row
		return $this->connection->lastInsertId();
	}

	/**
	 * Performs an update query
	 *
	 * @return The number of rows affected by the query
	 */ 
	public function update($query, $params=[])
	{
		//Make the query
		$query = $this->query($query, $params);
		//Return the number of affected rows
		return $query->rowCount();
	}

	/**
	 * Performs an delete query
	 *
	 * @return - The number of rows affected by the query
	 */ 
	public function delete($query, $params=[])
	{
		//Make and return the query
		return $this->update($query, $params);
	}

	/**
	 * Access the database object directly 
	 *
	 * @return - The PDO database object
	 */
	public function getConnection()
	{
		return (isset($this->connection)) ? $this->connection : null;
	}
}