<?php
class SecurePinModel{
	public $userId =-1;
	public $securePin =-1;
	
	public function parseSecurePin($securePin){
		if(isset($securePin->SecurePin))
		$this->securePin = $securePin->SecurePin;
		if(isset($securePin->UserID))
		$this->userId = $securePin->UserID;	
	}
}

class SecurePin extends Model{

	function __construct($db) {
		parent::__construct($db);
	}
	
	public function getPinForUser($userId){
		$sql = "SELECT ". SECUREPIN_USERID .", ". SECUREPIN_PIN ." from ". SECUREPIN ." WHERE UserID = :userId";
		
		$query = $this->db->prepare($sql);
		$query->execute(array(':userId' => $userId));
		$pins = $query->fetchAll();
		$pin = $pins[0];
		$securePinModel = new SecurePinModel();
		$securePinModel->parseSecurePin($pin);
		return $securePinModel;
	}
	
	public function addSecurePin($userId, $securePin)
	{
		$sql = "INSERT into ". SECUREPIN ."(". SECUREPIN_USERID .",". SECUREPIN_PIN .") VALUES(:userid, :pin)";
		
		$query = $this->db->prepare($sql);
		$query->execute(array('userid'=> $userId, 'pin'=>$securePin));
	}
}
?> 
