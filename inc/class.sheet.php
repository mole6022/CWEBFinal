<?php
require_once 'class.db.php';

class SHEET {
	private $conn;
	//Core system variables
	private $sheetId;
	private $userId;
	private $sheetName;
	private $dateModified;
	private $visible;
	private $game = array();
	
	//Sheet-specific display variables
	public $attributes = array();
	public $charName;
	public $playerName;
	public $alignment;
	public $xpCurrent;
	public $level;
	public $class;
	public $race;
	public $campaign;
	public $deity;
	public $size;
	public $age;
	public $gender;
	public $height;
	public $weight;
	public $eyes;
	public $hair;
	public $skin;
	public $hp = array();
	public $armorClass = array();
	public $speed;
	public $armorType;
	public $initiative = array();
	public $saves = array();
	public $baseAttackBonus;
	public $attackBonuses = array();
	public $damageReduction;
	public $image;
	//private $attack = array();
	//private $spellResist;
	//private $money;
	
	public function __construct($id = NULL) {
		$db = new DB();
		$this->conn = $db->connectDb();
		
		if ($id) {
			$this->sheetId = $id;
			
			//Collect data from database to populate core sheet data
			try{
				//collect core sheet data
				$query = "SELECT SHEET.sheet_id AS sheet_id
						, user_id
						, sheet_name
						, SHEET.date_modified AS date_modified
						, visible
						, GAME.game_id AS game_id
						, game_name
						, access_key
						, char_name
						, playername
						, alignment
						, xpcurrent
						, level
						, `class`
						, race
						, campaign
						, deity
						, size
						, age
						, gender
						, height
						, weight
						, eyes
						, hair
						, skin
						, strbase
						, strtemp
						, dexbase
						, dextemp
						, conbase
						, contemp
						, intbase
						, inttemp
						, wisbase
						, wistemp
						, chabase
						, chatemp
						, hpmax
						, hpcurrent
						, hpnonlethal
						, hitdice
						, dr
						, actotal
						, acarmor
						, acshield
						, acnatural
						, acdeflect
						, acmisc
						, actouch
						, acflatfoot
						, inittotal
						, initmisc
						, speed
						, armortype
						, save_fortbase
						, save_fortmag
						, save_fortmisc
						, save_forttemp
						, save_willbase
						, save_willmag
						, save_willmisc
						, save_willtemp
						, save_refbase
						, save_refmag
						, save_refmisc
						, save_reftemp
						, baseattack
						, meleemisc
						, meleetemp
						, rangemisc
						, rangetemp
						, image
					FROM SHEET
						LEFT OUTER JOIN GAME_SHEET ON SHEET.sheet_id = GAME_SHEET.sheet_id
						LEFT OUTER JOIN GAME ON GAME_SHEET.game_id = GAME.game_id
					WHERE SHEET.sheet_id = :sid";
				$stmt = $this->conn->prepare($query);
				$stmt->bindParam("sid", $id);
				$stmt->execute();
				$sheet = $stmt->fetch();
				$stmt->closeCursor();
				
				//Populate core variables
				$this->userId = $sheet['user_id'];
				$this->sheetName = $sheet['sheet_name'];
				$this->dateModified = $sheet['date_modified'];
				$this->visible = $sheet['visible'];
				$this->game['id'] = $sheet['game_id'];
				$this->game['name'] = $sheet['game_name'];
				$this->game['key'] = $sheet['access_key'];
				
				//Populate sheet display variables
				$this->attributes['str']['base'] = $sheet['strbase'];
				$this->attributes['str']['temp'] = $sheet['strtemp'];
				$this->attributes['dex']['base'] = $sheet['dexbase'];
				$this->attributes['dex']['temp'] = $sheet['dextemp'];
				$this->attributes['con']['base'] = $sheet['conbase'];
				$this->attributes['con']['temp'] = $sheet['contemp'];
				$this->attributes['int']['base'] = $sheet['intbase'];
				$this->attributes['int']['temp'] = $sheet['inttemp'];
				$this->attributes['wis']['base'] = $sheet['wisbase'];
				$this->attributes['wis']['temp'] = $sheet['wistemp'];
				$this->attributes['cha']['base'] = $sheet['chabase'];
				$this->attributes['cha']['temp'] = $sheet['chatemp'];
				$this->charName = $sheet['char_name'];
				$this->playerName = $sheet['playername'];
				$this->alignment = $sheet['alignment'];
				$this->xpCurrent = $sheet['xpcurrent'];
				$this->level = $sheet['level'];
				$this->race = $sheet['race'];
				$this->class = $sheet['class'];
				$this->campaign = $sheet['campaign'];
				$this->deity = $sheet['deity'];
				$this->size = $sheet['size'];
				$this->age = $sheet['age'];
				$this->gender = $sheet['gender'];
				$this->height = $sheet['height'];
				$this->weight = $sheet['weight'];
				$this->eyes = $sheet['eyes'];
				$this->hair = $sheet['hair'];
				$this->skin = $sheet['skin'];
				$this->age = $sheet['age'];
				$this->hp['current'] = $sheet['hpcurrent'];
				$this->hp['max'] = $sheet['hpmax'];
				$this->hp['nonlethal'] = $sheet['hpnonlethal'];
				$this->hp['hitdice'] = $sheet['hitdice'];
				$this->armorClass['total'] = $sheet['actotal'];
				$this->armorClass['armor'] = $sheet['acarmor'];
				$this->armorClass['shield'] = $sheet['acshield'];
				$this->armorClass['natural'] = $sheet['acnatural'];
				$this->armorClass['deflect'] = $sheet['acdeflect'];
				$this->armorClass['misc'] = $sheet['acmisc'];
				$this->armorClass['touch'] = $sheet['actouch'];
				$this->armorClass['flatfoot'] = $sheet['acflatfoot'];
				$this->speed = $sheet['speed'];
				$this->armorType = $sheet['armortype'];
				$this->initiative['total'] = $sheet['inittotal'];
				$this->initiative['misc'] = $sheet['initmisc'];
				$this->damageReduction = $sheet['dr'];
				$this->saves['fort']['base'] = $sheet['save_fortbase'];
				$this->saves['fort']['mag'] = $sheet['save_fortmag'];
				$this->saves['fort']['misc'] = $sheet['save_fortmisc'];
				$this->saves['fort']['temp'] = $sheet['save_forttemp'];
				$this->saves['ref']['base'] = $sheet['save_refbase'];
				$this->saves['ref']['mag'] = $sheet['save_refmag'];
				$this->saves['ref']['misc'] = $sheet['save_refmisc'];
				$this->saves['ref']['temp'] = $sheet['save_reftemp'];
				$this->saves['will']['base'] = $sheet['save_willbase'];
				$this->saves['will']['mag'] = $sheet['save_willmag'];
				$this->saves['will']['misc'] = $sheet['save_willmisc'];
				$this->saves['will']['temp'] = $sheet['save_willtemp'];
				$this->baseAttackBonus = $sheet['baseattack'];
				$this->attackBonuses['melee']['misc'] = $sheet['meleemisc'];
				$this->attackBonuses['melee']['temp'] = $sheet['meleetemp'];
				$this->attackBonuses['range']['misc'] = $sheet['rangemisc'];
				$this->attackBonuses['range']['temp'] = $sheet['rangetemp'];
				$this->image = $sheet['image'];
				//$this->attack = array();
				//$this->spellResist = $sheet['spellresist'];
				//$this->money = $sheet['money'];
			
			}
			catch (PDOException $e) {
				throw new Exception("Could not read database: ".$e->getMessage()."<br />Query: ".$query);
			}
			catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
		} //Conclude if (!empty($id)) block
	}
	
	public function saveSheet($inputData) {
		//Updates game options
		// Return: 
		//   1: Successful write
		// Exceptions returned: 
		//   PDOException
		//   Exception
		// Parameters:
		//   $inputData: associative array containing all data indexed *directly* to query variables
		
		$query = "UPDATE SHEET
					SET date_modified = NOW()
						, char_name = :charName
						, playername = :playerName
						, alignment = :alignment
						, xpcurrent = :currentXp
						, level = :level
						, `class` = :class
						, race = :race
						, campaign = :campaign
						, deity = :deity
						, size = :size
						, age = :age
						, gender = :gender
						, height = :height
						, weight = :weight
						, eyes = :eyes
						, hair = :hair
						, skin = :skin
						, strbase = :strbase
						, strtemp = :strtemp
						, dexbase = :dexbase
						, dextemp = :dextemp
						, conbase = :conbase
						, contemp = :contemp
						, intbase = :intbase
						, inttemp = :inttemp
						, wisbase = :wisbase
						, wistemp = :wistemp
						, chabase = :chabase
						, chatemp = :chatemp
						, hpmax = :hpmax
						, hpcurrent = :hpcurrent
						, hpnonlethal = :hpnonlethal
						, hitdice = :hitdice
						, dr = :damagereduce
						, actotal = :actotal
						, acarmor = :acarmor
						, acshield = :acshield
						, acnatural = :acnatural
						, acdeflect = :acdeflect
						, acmisc = :acmisc
						, actouch = :actouch
						, acflatfoot = :acflat
						, inittotal = :inittotal
						, initmisc = :initmisc
						, speed = :speed
						, armortype = :armorclass
						, save_fortbase = :fortSaveBase
						, save_fortmag = :fortSaveMagic
						, save_fortmisc = :fortSaveMisc
						, save_forttemp = :fortSaveTemp
						, save_willbase = :willSaveBase
						, save_willmag = :willSaveMagic
						, save_willmisc = :willSaveMisc
						, save_willtemp = :willSaveTemp
						, save_refbase = :reflexSaveBase
						, save_refmag = :reflexSaveMagic
						, save_refmisc = :reflexSaveMisc
						, save_reftemp = :reflexSaveTemp
						, baseattack = :babBase
						, meleemisc = :babMeleeMisc
						, meleetemp = :babMeleeTemp
						, rangemisc = :babRangeMisc
						, rangetemp = :babRangeTemp
					WHERE SHEET.sheet_id = :sheetId";
		
		try {
			$stmt = $this->conn->prepare($query);
			foreach($inputData as $datumKey => $datumValue) {
				if($datumValue == NULL || empty($datumValue)) {
					$stmt->bindValue($datumKey, NULL);
				} else {
					$stmt->bindValue($datumKey, $datumValue);
				}
			}
			$stmt->execute();
		}
		catch (PDOException $e) {
			throw new Exception ("Could not write to database: ".$e->getMessage());
		}
		catch (Exception $e) {
			throw new Exception ($e->getMessage());
		}
		return 1;
	}
	
	//Set functions
	public function updateOptions($newName, $game = 0, $newVisible = 1) {
		//Updates game options
		// Return: 
		//   
		// Exceptions returned: 
		//   Exception
		// Parameters:
		//   $newName: New sheet name
		//   $game: Game identifier (Game ID) (DEFAULT NO_GAME)
		//   $visible: Visibility status (DEFAULT 1)
		
		//Obtain game ID corresponding to access code if access code specified
		/*if(!empty($gameAccessCode)) {
			$queryGame = "SELECT game_id FROM GAME WHERE game_name = :accKey LIMIT 1";
			$game_id = "";
			try {
				$this->conn->prepare($queryGame);
				$this->conn->bindParam("accKey", $game);
				$this->conn->execute();
			}
			catch (Exception $e) {
				
			}
		}*/
		
		//Prepare session state for update of all relevant entries
		$updateSheet = "UPDATE SHEET
			SET SHEET_NAME = :sname, DATE_MODIFIED = NOW(), VISIBLE = :visible
			WHERE sheet_id = :sid";
		$deleteLink = "DELETE FROM GAME_SHEET WHERE sheet_id = :sid";
		$updateLink = "INSERT INTO GAME_SHEET(game_id, sheet_id) VALUES(:gid, :sid)";
		try {
			$this->conn->beginTransaction();
			$setUpdate = $this->conn->prepare($updateSheet);
			$setUpdate->bindParam("sname", $newName);
			$setUpdate->bindParam("visible", $newVisible);
			$setUpdate->bindParam("sid", $this->sheetId);
			$setUpdate->execute();
			$deleteUpdate = $this->conn->prepare($deleteLink);
			$deleteUpdate->bindParam("sid", $this->sheetId);
			$deleteUpdate->execute();
			if(!empty($game) && $game != 0) {
				$setGame = $this->conn->prepare($updateLink);
				$setGame->bindParam("gid", $game);
				$setGame->bindParam("sid", $this->sheetId);
				$setGame->execute();
			}
			$this->conn->commit();
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function createSheet($user_id, $sname, $game = 0, $visible = 1) {
		//Constructs a new sheet in the database
		// Return: 
		//   
		// Exceptions returned: 
		//   Exception
		// Parameters:
		//   $user_id: ID of the user creating the sheet
		//   $sname: Sheet name
		//   $game: Game identifier (Game ID) (DEFAULT NO_GAME)
		//   $newVisible: Visibility status (DEFAULT 1)
		
		//Prepare session state for update of all relevant entries
		$createSheet = "INSERT INTO SHEET(`user_id`, `sheet_name`, `visible`)
			VALUES(:uid, :sname, :visible)";
		$createGameLink = "INSERT INTO GAME_SHEET(game_id, sheet_id) VALUES(:gid, :sid)";
		try {
			$this->conn->beginTransaction();
			$setUpdate = $this->conn->prepare($createSheet);
			$setUpdate->bindParam("uid", $user_id);
			$setUpdate->bindParam("sname", $sname);
			$setUpdate->bindParam("visible", $visible);
			$setUpdate->execute();
			$sid = $this->conn->lastInsertId();
			if(!empty($game) && $game != 0) {
				$setGame = $this->conn->prepare($createGameLink);
				$setGame->bindParam("gid", $game);
				$setGame->bindParam("sid", $sid);
				$setGame->execute();
			}
			$this->conn->commit();
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function deleteSheet() {
		//Deletes a sheet and all child table entries in the database
		// Return: 
		//   
		// Exceptions returned: 
		//   Exception
		
		$query = "";
		try {
			$query = "DELETE FROM CHAR_ARMOR WHERE sheet_id = :sid";
			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->execute();
			
			$query = "DELETE FROM CHAR_ATTACK WHERE sheet_id = :sid";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->execute();
			
			$query = "DELETE FROM CHAR_FEATS WHERE sheet_id = :sid";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->execute();
			
			$query = "DELETE FROM CHAR_INV WHERE sheet_id = :sid";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->execute();
			
			$query = "DELETE FROM CHAR_SKILLS WHERE sheet_id = :sid";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->execute();
			
			$query = "DELETE FROM CHAR_SPELL WHERE sheet_id = :sid";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->execute();
			
			$query = "DELETE FROM GAME_SHEET WHERE sheet_id = :sid";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->execute();
			
			$query = "DELETE FROM SHEET WHERE sheet_id = :sid";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->execute();
			
			$this->conn->commit();
			return 1;
		}
		catch (Exception $e) {
			throw new Exception ($e->getMessage()." / Query: ".$query);
		}
	}
	
	public function setPortrait($portraitUrl = NULL) {
		//Writes a validated portrait URL to the database
		
		$query = "UPDATE SHEET
			SET `image` = :portrait
			WHERE sheet_id = :sid";
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam("sid", $this->sheetId);
			$stmt->bindParam("portrait", $portraitUrl);
			$stmt->execute();
			
			return 1;
		}
		catch (Exception $e) {
			throw new Exception ($e->getMessage()." / Query: ".$query);
		}
	}
	
	//Get functions
	public function getSheetId() { return $this->sheetId; }
	public function getUserId() { return $this->userId; }
	public function getSheetName() { return $this->sheetName; }
	public function getGameId() { return $this->game['id']; }
	public function getGameName() { return $this->game['name']; }
	public function getGameAccessKey() { return $this->game['key']; }
	public function getVisibility() { return $this->visible; }
}
?>