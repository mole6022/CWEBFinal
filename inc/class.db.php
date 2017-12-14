<?php
class DB {
	private $dbserver = 'freedbinstance.cxv2ywrgg3kc.us-east-1.rds.amazonaws.com';
	private $dbuser = 'adminnaturalone';
	private $dbpass = 'nat2s1964!';
	private $dbname = 'naturalonegames';
	public $conn; 
	
	public function connectDb() {
		$this->conn = null;
		try {
			$this->conn = new PDO("mysql:host=".$this->dbserver.";dbname=".$this->dbname, $this->dbuser, $this->dbpass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Configure PDO to throw exceptions
			$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Configure to return associative arrays by default
			$this->conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); //Utilize buffered queries
			$this->conn->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING); //Convert empty strings to NULL
		}
		catch (PDOException $e) {
			die("Error connecting to database: ".$e->getMessage());
		}
		return $this->conn;
	}
	
	/*public function runQuery($sql, $param = NULL) {
		//Accept a SQL command and an optional list of parameters, returning an associative array
		try {
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(is_array($param)?$param);
			return $stmt;
		}
		catch (PDOException $e) {
			die("Error executing query: ".$e->getMessage());
		}
	}
	
	public function lastInsertId() {
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}*/
}

//Exception handling: custom errors based on errors in reading, writing, or in id collisions

class DbWriteException extends Exception {
	//Exception to be thrown when an error is encountered while writing to database
	public function __construct(string $error) {
		parent::__construct($error);
	}
}

class DbReadException extends Exception {
	//Exception to be thrown when an error is encountered while reading from the database
	public function __construct(string $error) {
		parent::__construct($error);
	}
}

class DbExistsException extends Exception {
	//Exception to be thrown when a record to be written to the database matches an extant record
	public function __construct(string $error) {
		parent::__construct($error);
	}
}