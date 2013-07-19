<?php
namespace MicroAPI;

class Database extends Singleton
{
	//PDO database connection
	private $connection;
	
	/**
	 * Makes the connection to the database. Not called until a connection to the
	 * database is actually needed.
	 *
	 *	@return void
	 */
	private function checkForConnection()
	{
		//If a connection already exists return
		if(isset($connection))
			return;

		//Attempt to make a connection to the database
		try
		{
			$this->connection = new \PDO(
				'mysql:host=' . $this->config['host'] . ';dbname=' . $this->config['name'],
				$this->config['user'],
				$this->config['pass']
			);
		}
		catch(Exception $e)
		{
			//Throw a more generic message to avoid database connection details leaking
			throw new Exception('Error connecting to the database');
		}
	}

	/**
	 * Performs a query on the database.
	 * 
	 * @param 
	 * @return An executed database statment
	 */
	public function query($query, $params)
	{
		//Make sure a database connection exists
		$this->checkForConnection();

		//If only one data value was passed in wrap it in an array
		if(!is_array($params))
			$params = [$params];

		$statment = $this->connection->prepare($query);
		$statment->execute($params);
		
		return $statment;
	}

	/**
	 * Performs a query and fetches the results.
	 */
	public function select($query, $params, $options = [])
	{
		//If a fetch mode was defined use it, else use the one defined in the config file
		$fetchMode = (isset($options['fetch'])) ? $options['fetch'] : $this->config['fetch'];
		
		$statment = $this->query($query, $params);
		$results = $statment->fetchAll();

		return $results;
	}
}
