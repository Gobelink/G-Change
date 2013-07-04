<?php
// Standard interface for interacting with a database server 
interface databaseConnectionManager {
	
	public function newConnection($dbURL, $userName, $password);

	public function closeConnection($databaseConnection);

	public function selectQuery($databaseConnection, $query);

	public function otherQuery($databaseConnection, $query);
}