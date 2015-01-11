<?php

class TanModel {
	public $userID;
	public $tan;
	public $tanSequenceId;
	public $active;

	public function parseTan($tanEntry) {
		if(isset($tanEntry->userId))
			$this->userID = $tanEntry->userId;
		if(isset($tanEntry->tan))
			$this->tan = $tanEntry->tan;
		if(isset($tanEntry->tanSequenceId))
			$this->tanSequenceId = $tanEntry->tanSequenceId;
		if(isset($tanEntry->active))
			$this->active = $tanEntry->active;
	}

	public static function parseTanArray($array) {
		$tanNumbers = array();
		foreach ($array as $tanNumber) {
			$tanModel = new TanModel();
			$tanModel->parseTan($tanNumber);
			/*$tanNumber[] = $tanModel;*/
			array_push($tanNumbers, $tanModel);
		}
		return $tanNumbers;
	}
}


class Tan extends Model {
	
	function __construct($db) {
		parent::__construct($db);
	}
	
	public function getAllowedSimulatorTan($securePin, $transactionDetail, $email){
		$timenow = time();			
		$startTime = $timenow - 100;
		$tans = array();
		for($i =0; $i<120; $i++)
		{
			$key = $securePin . $transactionDetail . ($startTime+$i).$email;
			//echo " key : " . $key;
			$tan = substr(hash("sha256", $key, false), 0, 15);
			$tans[$i] = $tan;
		}
		return $tans;
	}

	public function getAllTanNumbersByUserID($userID) {
		$sql = "SELECT " . TAN_USERID . ", " .
			TAN_USERID . ", " .
			TAN_TAN . ", " .
			TAN_TANSEQUENCEID . ", " .
			TAN_ACTIVE . 
			" FROM " . TAN . " WHERE " . TAN_USERID . " = :uId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':uID' => $userID));
        $tanNos = $query->fetchAll();

        return TanModel::parseTanArray($tanNos);
	}

	//This is not working. inactive tans are being returned. or might be issue with session
	public function getAllActiveTanNumbersByUserID($userID) {
		$sql = "SELECT " .
			TAN_USERID . ", " .
			TAN_TAN . ", " .
			TAN_TANSEQUENCEID . ", " .
			TAN_ACTIVE . 
			" FROM " . TAN . " WHERE " . TAN_USERID . " = :uId AND " . TAN_ACTIVE . " = (1) " ;
        $query = $this->db->prepare($sql);
        $query->execute(array(':uId' => $userID));
        $tanNos = $query->fetchAll();
        return TanModel::parseTanArray($tanNos);
	}


	
	public function getTanByTanSequenceIdAndUser($userId, $tanSequence){
		$sql = "SELECT " .
			TAN_USERID . ", " .
			TAN_TAN . ", " .
			TAN_TANSEQUENCEID . ", " .
			TAN_ACTIVE . 
			" FROM " . TAN . " WHERE " . TAN_USERID . " = :uId AND " . TAN_TANSEQUENCEID . " = :tanSequence" ;
        $query = $this->db->prepare($sql);
        $query->execute(array(':uId' => $userId, ':tanSequence' => $tanSequence));
        $tanNoss = $query->fetchAll();
         $tanNos = $tanNoss[0];
        $tanModel = new TanModel();
        $tanModel->parseTan($tanNos);
        return $tanModel;
	}

	public function getTanByTan($tan){
		$sql = "SELECT " .
			TAN_USERID . ", " .
			TAN_TAN . ", " .
			TAN_TANSEQUENCEID . ", " .
			TAN_ACTIVE . 
			" FROM " . TAN . " WHERE " . TAN_TAN . " = :t " ;
        $query = $this->db->prepare($sql);
        $query->execute(array(':t' => $tan));
        $tanNoss = $query->fetchAll();
        $tanNos = $tanNoss[0];
        $tanModel = new TanModel();
        $tanModel->parseTan($tanNos);
        return $tanModel;
	}

	public function updateTanToInactive($tansequenceId, $userId)
	{
		$sql = "UPDATE tan SET active = 0 WHERE tansequenceid = :tanSequenceId AND userid = :userId";
		
		$query = $this->db->prepare($sql);
		$query->execute(array(':userId' => $userId, ':tanSequenceId' => $tansequenceId));
	}

	private function generateTanNumber($email){
			$timenow = microtime();
			$usernameAndTimenow = $timenow . $email;
			$hashedCombo = md5($usernameAndTimenow);
			$rand = substr($hashedCombo,rand(0,16),15);
			return $rand;
	}

	public function addTanNumbersToUser($userId) {
		$userModel = new User($this->db);
		$user = $userModel->getUserById($userId);
		$userEmail = $user->email;
		$tanList = "Hey ". $user->firstName . ",<br> here is your tan list: <br>";
		for($i=1;$i<=100;$i++) {
			$generatedTanSequence = $this->generateTanNumber($userEmail);
			$sql = "INSERT INTO ". TAN ."( ". TAN_USERID .", " . TAN_TAN .", " . TAN_ACTIVE .", " . TAN_TANSEQUENCEID . " ) VALUES (:uID, :t, (1), :seq)";
			$query = $this->db->prepare($sql);
			$query->execute(array(':uID' => $userId, ':t' => $generatedTanSequence, ':seq' => $i));

			$currentTAN = $this->getTanByTan($generatedTanSequence);
			$currentTANID = $currentTAN->tanSequenceId;
			$tanList = $tanList. $currentTANID . " : " . $generatedTanSequence . " <br>";
		}
		return $tanList;
	}

			
}
