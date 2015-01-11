<?php 
define('PASSWORDRECOVERYTOKEN', 'passwordRecoveryToken');
define('PASSWORDRECOVERYTOKEN_USER_ID','userId');
define('PASSWORDRECOVERYTOKEN_CREATED_DATE','createdDate');
define('PASSWORDRECOVERYTOKEN_TOKEN','token');

class TokenModel {
	public $userId;
	public $token;
	public $createdDate;
	public $isPassword;

	public function parseTan($entry) {
		if(isset($entry->userId))
			$this->userId = $entry->userId;
		if(isset($entry->token))
			$this->token = $entry->token;
		if(isset($entry->createdDate))
			$this->createdDate = $entry->createdDate;
		if(isset($entry->isPassword))
			$this->isPassword = $entry->isPassword;
	}
}


class Token extends Model {
	
	function __construct($db) {
		parent::__construct($db);
	}

	public function createTokenForUserIdAndIsPassword($userId, $userEmail, $isPassword) {
		$sql = "INSERT INTO " . TOKEN . "(" . 
			TOKEN_USERID. "," . 
			TOKEN_TOKEN . "," .
			TOKEN_ISPASSWORD . ") VALUES (:userId, :token, :isPassword)";
		$query = $this->db->prepare($sql);
		$token = Token::generateHashWithSalt($userEmail);
       	if ($query->execute(array(':userId' => $userId, ':token' => $token, ':isPassword' => $isPassword)))
       		return $token;
	}

	public function getTokenUserIdAndIsPassword($userId, $isPassword) {
		$sql = "SELECT " .
			TOKEN_USERID . ", " .
			TOKEN_CREATEDDATE . ", " .
			TOKEN_TOKEN . ", " .
			TOKEN_ISPASSWORD . 
			" FROM " . TOKEN . " WHERE " . TOKEN_USERID . " = :uId AND " . TOKEN_ISPASSWORD . " = :isPassword" ;
        $query = $this->db->prepare($sql);
        $query->execute(array(':uId' => $userId, ':isPassword' => $isPassword));
        $tokens = $query->fetchAll();
        if (isset($tokens) && isset($tokens[0])) {
		    $token = $tokens[0];
		    $tokenModel = new TokenModel();
		    $tokenModel->parseTan($token);
		    return $tokenModel;
    	}
    	return null;
	}

	public function getTokenByTokenAndIsPassword($token, $isPassword) {
		$sql = "SELECT " .
			TOKEN_USERID . ", " .
			TOKEN_CREATEDDATE . ", " .
			TOKEN_TOKEN . ", " .
			TOKEN_ISPASSWORD . 
			" FROM " . TOKEN . " WHERE " . TOKEN_TOKEN . " = :token AND " . TOKEN_ISPASSWORD . " = :isPassword" ;
        $query = $this->db->prepare($sql);
        $query->execute(array(':token' => $token, ':isPassword' => $isPassword));
        $tokens = $query->fetchAll();
        if (isset($tokens) && isset($tokens[0])) {
		    $token = $tokens[0];
		    $tokenModel = new TokenModel();
		    $tokenModel->parseTan($token);
		    return $tokenModel;
    	}
    	return null;
	}

	public function deleteToken($userId, $isPassword) {
		$sql = "DELETE FROM " . TOKEN . " where " . TOKEN_USERID . " = :userId AND " . TOKEN_ISPASSWORD . " = :isPassword";
		$query = $this->db->prepare($sql);
		$query->execute(array(':userId' => $userId, ':isPassword' => $isPassword));
	}

	public function deleteTokenByToken($token) {
		$sql = "DELETE FROM " . TOKEN . " where " . TOKEN_TOKEN . " = :token";
		$query = $this->db->prepare($sql);
		$query->execute(array(':token' => $token));
	}

	static function generateHashWithSalt($key) {
    	$intermediateSalt = md5(uniqid(rand(), true));
    	$key = $key . time();
    	$salt = substr($intermediateSalt, 0, 3);
    	return hash("sha256", $key . $salt);
	}
}