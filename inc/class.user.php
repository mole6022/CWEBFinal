<?php
require_once ('class.db.php');

class USER {
	public $conn;
	/*
	Operates on session variables contained within array:
		userSession: ContainerArray
			user_id: User ID
			user_type: User type (Player or GM)
			user_username: Username
			user_email: If set, user email. If not set, null or not indexed.
			NOTE: Password is *NOT* stored in the session due to acute paranoia and lack of need
	*/
	
	public function __construct() {
		$db = new DB();
		$this->conn = $db->connectDb();
	}
	
	public function register($uname, $pass, $type = 1, $email = NULL) {
		// Write a new user record to the database
		// Return: 
		//   1: Normal function exit
		// Exceptions returned: 
		//   Exception
		// Parameters: 
		//   $uname: Username (REQUIRED). 5-50 characters
		//   $pass: Password (REQUIRED). 5-255 characters
		//   $email: Email address (OPTIONAL). 1-255 characters.
		try {
			//Confirm username is not already used
			$qread = $this->conn->prepare("SELECT 1 FROM USER WHERE user_username = :uname");
			$qread->bindParam("uname", $uname);
			$qread->execute();
		}
		catch(PDOException $e) {
			throw new Exception ("Could not read database: ".$e->getMessage());
		}
		
		if($qread->rowCount() == 0) {
			try {
				//Write user to database
				$qwrite = $this->conn->prepare("INSERT INTO USER(user_type, user_username, user_password, user_email)
					VALUES(:utype, :uname, :upass, :uemail)");
				$qwrite->bindParam("uname", $uname);
				$qwrite->bindParam("upass", md5($pass));
				$qwrite->bindParam("utype", $type);
				$qwrite->bindParam("uemail", $email);
				$qwrite->execute();
				return 1;
			}
			catch(PDOException $e) {
				throw new Exception ("Could not write user to database: ".$e->getMessage());
			}
		} else {
			throw new Exception ("User name already exists in system");
		}
	}
	
	public function login($uname, $upass) {
		// Confirm username exists in database and construct user session
		// Return:
		//   1: Normal function exit
		// Exceptions returned: 
		//   DbReadException
		//   Exception (Cannot locate user)
		// Parameters:
		//   $uname: Username (REQUIRED). 5-50 characters.
		//   $pass: Password (REQUIRED). 5-255 characters.
		
		$encpass = md5($upass);
		try {
			$userget = $this->conn->prepare("SELECT user_id, user_type, user_username, user_email FROM USER WHERE user_username = :uname AND user_password = :pass LIMIT 1");
			$userget->bindParam("uname", $uname);
			$userget->bindParam("pass", $encpass);
			$userget->execute();
		}
		catch (PDOException $e) { 
			throw new DbReadException ("Could not read database: ".$e->getMessage()); 
		}
		
		if($userget->rowCount() == 0) {
			throw new Exception("An account matching the username and passsword could not be found. Please confirm that both the username and password have been entered correctly.");
		} else {
			$_SESSION['userSession'] = $userget->fetch();
		}
		
		return 1;
	}
	
	public function updateUser($uname, $pass, $email = NULL, $newpass = NULL, $type = NULL) {
		// Update a user account with new information
		// Return: 
		//   1: Normal function exit
		// Exceptions returned: 
		//   DbReadException
		//   DbWriteException
		//   DbExistsException
		// Parameters: 
		//   $uname: Username (REQUIRED). 5-50 characters
		//   $pass: Password (REQUIRED). 5-255 characters
		//   $email: Email address (OPTIONAL). *Will* write if NULL. 1-255 characters.
		//   $newpass: New Password (OPTIONAL). Does not write if NULL. 5-255 characters
		//   $type: User type (OPTIONAL). Does not write if NULL. Values defined in database schema KEY_USERTYPE
		
		try {
			//Confirm username exists in system
			$qread = $this->conn->prepare("SELECT user_id FROM USER WHERE user_username = :uname  AND user_password = :upass LIMIT 1");
			$qread->bindParam("uname", $uname);
			$qread->bindParam("upass", md5($pass));
			$qread->execute();
		}
		catch(PDOException $e) {
			throw new DbReadException ("Could not read database: ".$e->getMessage());
		}
		if($qread->rowCount() == 1) {
			$userFetch = $qread->fetch();
			$userId = $userFetch['user_id'];
			$qread->closeCursor();
			
			//Construct the update query based on specified values
			$query = "UPDATE USER 
					SET user_email = :uemail";
			if (!empty($newpass)) {
				$query .= ", user_password = :npass";
			}
			if (!empty($type)) {
				$query .= ", user_type = :utype";
			}
			$query .= " WHERE user_id = :uid";
			
			try {
				//Write user to database
				$qwrite = $this->conn->prepare($query);
				$qwrite->bindParam("uid", $userId);
				$qwrite->bindParam("uemail", $email);
				if(!empty($newpass)) $qwrite->bindParam("npass", md5($newpass));
				if(!empty($type)) $qwrite->bindParam("utype", $type, PDO::PARAM_INT);
				$qwrite->execute();
				return 1;
			}
			catch(PDOException $e) {
				throw new Exception ("Could not write user to database: ".$e->getMessage());
			}
		} else {
			throw new Exception ("Could not find user and password. Please confirm password is valid.");
		}
	}
	
	public function logout() {
		//Nukes session state, including user data
		session_start();
		session_destroy();
	}
	
	//Begin IS functions - boolean returns
	public function isLoggedIn() {
		//Returns user status - is logged in or not
		return isset($_SESSION['userSession']) && is_array($_SESSION['userSession']);
	}
	
	public function isGM() {
		//Returns true if user is GM - returns FALSE if user is not logged in
		if($this->isLoggedIn())
			return $_SESSION['userSession']['user_type'] == 2;
		else
			return FALSE; //User is not logged in or session state not initialized
	}
	
	//Begin GET functions - retrieve key data
	public function getUserId() {
		//Returns user ID for active user - returns FALSE if user is not logged in
		if($this->isLoggedIn())
			return $_SESSION['userSession']['user_id'];
		else
			return FALSE; //User is not logged in or session state not initialized
	}
	
	public function getUsername() {
		//Returns user name for active user - returns FALSE if user is not logged in
		if($this->isLoggedIn())
			return $_SESSION['userSession']['user_username'];
		else
			return FALSE; //User is not logged in or session state not initialized
	}
	
	public function getEmail() {
		//Returns email address for active user - returns FALSE if user is not logged in
		if($this->isLoggedIn())
			return $_SESSION['userSession']['user_email'];
		else
			return FALSE; //User is not logged in or session state not initialized
	}
	/*
	public function getPassword() {
		//Returns user password for active user as an MD5 hashed string, or FALSE if the user is not logged in.
		// Exceptions returned:
		//   DbReadException
		
		if($this->isLoggedIn()) {
			
		} else {
			//User is not logged in or session state not initialized
			return FALSE;
		}
	}
	*/
	public function getSheets($page = 0, $perPage = 5, $sortField='none', $sortOrder='asc') {
		// Obtains key data about all sheets associated with user, indexed by page
		// Returns: 
		//   resultSheets: Associative array containing key data according to following indices
		//     Sheet ID
		//     Sheet Name
		//     Game (optional)
		//     Date Last Modified
		//     Visibility status (not implemented)
		//   FALSE: User not logged in
		// Exceptions returned: 
		//   Exception
		// Parameters: 
		//   $page: Page number (defaults to first page, zero-indexed)
		//   $perPage: Sheets displayed per page (defaults to 5; specify 0 to retrieve all sheets)
		if($this->isLoggedIn()) {
			$query = "SELECT SHEET.sheet_id AS sheet_id, sheet_name, game_name, SHEET.date_modified AS date_modified, visible
				FROM SHEET 
					LEFT OUTER JOIN GAME_SHEET ON SHEET.SHEET_ID = GAME_SHEET.SHEET_ID
					LEFT OUTER JOIN GAME ON GAME_SHEET.GAME_ID = GAME.GAME_ID
				WHERE USER_ID = :uid";
			$id = $this->getUserId();
			$resultSheets = array();
			//Configure sort functionality if such exists
			switch($sortField) {
				case 'sheet':
					$query .= " ORDER BY sheet_name";
					break;
				case 'game':
					$query .= " ORDER BY game_name";
					break;
				case 'date':
					$query .= " ORDER BY date_modified";
					break;
				case 'none':
				default:
					$query .= " ORDER BY sheet_id";
					break;
			}
			switch ($sortOrder) {
				case 'desc':
					$query .= " DESC";
					break;
				case 'asc':
				default:
					$query .= " ASC";
			}
			//Configure page limit if such exists
			if($perPage != 0) {
				$query .= " LIMIT :initrecord, :pagecount";
				$initrecord = $page * $perPage;
			}
			
			//Call information from database
			try {
				$sheetget = $this->conn->prepare($query);
				$sheetget->bindParam("uid", $id);
				if($perPage != 0) {
					$sheetget->bindParam("initrecord", $initrecord, PDO::PARAM_INT);
					$sheetget->bindParam("pagecount", $perPage, PDO::PARAM_INT);
				}
				$sheetget->execute();
				
				$resultSheets = $sheetget->fetchAll();
			}
			catch (PDOException $e) {
				throw new Exception ("Could not read database: ".$e->getMessage()
					."<br />".$id
					."<br />".$initrecord
					."<br />".$perPage
				); 
			}
			return $resultSheets;
		} else { //User not logged in
			return FALSE;
		}
	}
	
	public function getTotalSheets() {
		// Obtains total number of sheets associated with user
		// Returns: 
		//   resultSheets: INT
		//   FALSE: User not logged in
		// Exceptions returned: 
		//   Exceptioin
		if ($this->isLoggedIn()) {
			$id = $this->getUserId();
			$countSheets = NULL;
			try {
				$countget = $this->conn->prepare("SELECT COUNT(1) AS 'total' FROM SHEET WHERE USER_ID = :uid");
				$countget->bindParam("uid", $id);
				$countget->execute();
				
				$countset = $countget->fetch();
				$countSheets = $countset['total'];
			}
			catch (Exception $e) {
				throw new Exception ($e->getMessage());
			}
			
			return $countSheets;
		} else { //User not logged in
			return FALSE;
		}
	}
	
	public function getGames($page = 0, $perPage = 5) {
		// Obtains key data about all games associated with user regardless of type, indexed by page
		// Returns: 
		//   resultGames: Associative array containing key data according to following indices
		//     Game ID
		//     Game Name
		//     Date Last Modified
		//     Game Open status (open or closed)
		//   FALSE: User not logged in
		// Exceptions returned: 
		//   DbReadException
		// Parameters: 
		//   $page: Page number (defaults to first page, zero-indexed)
		//   $perPage: Sheets displayed per page (defaults to 5; specify 0 to retrieve all sheets)
		if($this->isLoggedIn()) {
			$query = "SELECT game_id, game_name, date_modified, open
				FROM GAME
				WHERE GM_ID = :uid";
			$id = $this->getUserId();
			$resultGames = array();
			if($perPage != 0) {
				$query .= " LIMIT :initrecord, :pagecount";
				$initrecord = $page * $perPage;
			}
			try {
				$gameget = $this->conn->prepare($query);
				$gameget->bindParam("uid", $id);
				if($perPage != 0) {
					$gameget->bindParam("initrecord", $initrecord, PDO::PARAM_INT);
					$gameget->bindParam("pagecount", $perPage, PDO::PARAM_INT);
				}
				$gameget->execute();
				
				$resultGames = $gameget->fetchAll();
			}
			catch (PDOException $e) {
				throw new DbReadException ("Could not read database: ".$e->getMessage());
			}
			return $resultGames;
		} else { //User not logged in
			return FALSE;
		}
	}
	
	public function getTotalGames() {
		// Obtains total number of sheets associated with user
		// Returns: 
		//   resultSheets: INT
		//   FALSE: User not logged in
		// Exceptions returned: 
		//   Exceptioin
		if ($this->isLoggedIn()) {
			$id = $this->getUserId();
			$countGames = NULL;
			try {
				$countget = $this->conn->prepare("SELECT COUNT(1) AS 'total' FROM GAME WHERE GM_ID = :uid");
				$countget->bindParam("uid", $id);
				$countget->execute();
				
				$countset = $countget->fetch();
				$countGames = $countset['total'];
			}
			catch (Exception $e) {
				throw new Exception ($e->getMessage());
			}
			
			return $countGames;
		} else { //User not logged in
			return FALSE;
		}
	}
}
?>