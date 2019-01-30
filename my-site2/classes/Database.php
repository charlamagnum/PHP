<?php

class Database{

	//set attributes
	//private $host = 'localhost'
	//private $host = 'http://localhost/my-site/tutorial1.php'
	
	//connection and authentication properties
	private $host = 'localhost';
	private $user = 'root';
	private $pass = '';
	private $dbname = 'myblog';

	//database handler properties
	private $dbh;

	//property for our errors
	private $error;

	//we also need a statement property we must pass
	private $stmt;


	//we need to run a constructor 
	public function __construct(){
		//Set DSN -- this will be our connection string
		$dsn = 'mysql:host='.$this->host . ';dbname = '. $this->dbname;
		//Set options -- We need to set our options
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		//Create new PDO instance
		try{
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		} catch(PDOEception $e){
			$this->error = $e->getMessage();

		}
		
	} //End of __construct

	//----------------------------------------------------------------------
	//Beginning of tutorial2 : functions to make queries unto the database
	
	//function that will take in a query parameter
	public function query($query){
		$this->stmt = $this->dbh->prepare($query);
	}

	//1-bind function
	//bind function so we can bind our data
	public function bind($param, $value, $type = null){
		
		//we are going to check if the value is integer, boolean, or null
		if(is_null($type)){
		
			switch(true){
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}



	//2-execute function: we can excute the prepared the statement
	public function execute(){
		return $this->stmt->execute();
	}

	//3-results set
	public function resultset(){
		$this->execute();
		//we need to return it as (we will choose associative array)
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);

	}



	//----------------------------------------------------------------------
	//Beginning of tutorial3 : functions to make queries unto the database
	//----------------------------------------------------------------------
	
	//We're gonna create a function lastinsertid 
	//to check and see if the data went in
	public function lastInsertId(){
		$this->dbh->lastInsertId();
	
	}


}



















?>