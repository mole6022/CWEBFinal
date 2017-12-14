<?php
require_once 'class.db.php';

class GAME {
	private $conn;
	private $gameId;
	private $gmId;
	private $gameName;
	private $open;
	private $accessKey;
	
	public function __construct($id = NULL) {
		$db = new DB();
		$this->conn = $db->connectDb();
		
		if(!empty($id)) {
			$this->game_id = $id;
			
			try{
				$stmt = $this->conn->prepare("SELECT game_id, gm_id, game_name, open, access_key
					FROM GAME
					WHERE game_id = :gid");
				$stmt->bindParam("gid", $id);
				$stmt->execute();
			}
			catch (PDOException $e) {
				return "Could not read database: ".$e->getMessage();
			}
			
			if($stmt->rowCount() == 1) {
				//Construct GAME object from existing game
				$game = $stmt->fetch();
				$this->gameId = $game['game_id'];
				$this->gmId = $game['gm_id'];
				$this->gameName = $game['game_name'];
				$this->open = $game['open'];
				$this->accessKey = $game['access_key'];
			}
			else { //Did not find game
				return "Could not find game: ".$e->getMessage();
			}
		}
	}
	//Set functions
	public function updateOptions($newName, $accessKey = NULL, $newVisible = 1) {
		//Updates game options
		// Return: 
		//   
		// Exceptions returned: 
		//   Exception
		// Parameters:
		//   $newName: New game name
		//   $access_key: Optional access key (DEFAULT NULL)
		//   $visible: Visibility status (DEFAULT 1)
		
		//Prepare session state for update of all relevant entries
		$update = "UPDATE GAME
			SET GAME_NAME = :name, DATE_MODIFIED = NOW(), ACCESS_KEY = :key, OPEN = :visible
			WHERE game_id = :gid";
		try {
			$this->conn->beginTransaction();
			$setUpdate = $this->conn->prepare($update);
			$setUpdate->bindParam("name", $newName);
			$setUpdate->bindParam("key", $accessKey);
			$setUpdate->bindParam("visible", $newVisible);
			$setUpdate->bindParam("gid", $this->gameId);
			$setUpdate->execute();
			$this->conn->commit();
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function createGame($user_id, $name, $access_key = NULL, $open = 1) {
		//Constructs a new game in the database
		// Return: 
		//   
		// Exceptions returned: 
		//   Exception
		// Parameters:
		//   $user_id:  ID of the user creating the sheet
		//   $name: Game name
		//   $access_key: Optional access key (DEFAULT NULL)
		//   $newVisible: Visibility status (DEFAULT 1)
		$createSheet = "INSERT INTO GAME(`gm_id`, `game_name`, `access_key`, `open`)
			VALUES(:uid, :name, :key, :open)";
		try {
			$this->conn->beginTransaction();
			$setUpdate = $this->conn->prepare($createSheet);
			$setUpdate->bindParam("uid", $user_id);
			$setUpdate->bindParam("name", $name);
			$setUpdate->bindParam("key", $access_key);
			$setUpdate->bindParam("open", $open);
			$setUpdate->execute();
			$this->conn->commit();
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function deleteGame() {
		//Removes a game from the database, as well as all associated game-sheet links
		// Return: 
		//   
		// Exceptions returned: 
		//   Exception
		
		$query = "";
		try {
			$query = "DELETE FROM GAME_SHEET WHERE game_id = :gid";
			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("gid", $this->gameId);
			$stmt->execute();
			
			$query = "DELETE FROM GAME WHERE game_id = :gid";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("gid", $this->gameId);
			$stmt->execute();
			
			$this->conn->commit();
			return 1;
		}
		catch (Exception $e) {
			throw new Exception ($e->getMessage()." / Query: ".$query);
		}
	}
	
	//Get functions
	public function getOpenGames() {
		//Returns associative array of all open games with IDs in the system
		try{
			$gameSet = $this->conn->prepare("SELECT game_id, game_name FROM GAME WHERE OPEN = 1");
			$gameSet->execute();
			
			$games = $gameSet->fetchAll();
			return $games;
		}
		catch (Exception $e) {
			throw new Exception ("Could not read database: ".$e->getMessage());
		}
	}
	public function getUserId() { return $this->gmId; }
	public function getGameName() { return $this->gameName; }
}
?>