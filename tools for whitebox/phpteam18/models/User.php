<?php

class UserModel {
	public $userId;
	public $firstName;
	public $lastName;
	public $email;
	public $mobile;
	public $active;
	public $bankAccount;
	public $userprivilege; // 1-admin 2-Emp 3-Client
	public $password;
	public $usesSecurePin;
	public $verified;

	public $transactions;
	public $tanNumbers;

	public function parseUser($user) {
		if (isset($user->bankUserId))
			$this->userId = $user->bankUserId;
		if (isset($user->firstName))
			$this->firstName = $user->firstName;
		if (isset($user->lastName))
			$this->lastName = $user->lastName;
		if (isset($user->active))
			$this->active = $user->active;
		if (isset($user->email))
			$this->email = $user->email;
		if (isset($user->mobile))
			$this->mobile = $user->mobile;
		if (isset($user->passwd)){
			$key = substr(hash("sha256", $this->email, false), 0, 15);
        	$password = $this->decryptString($user->passwd, $key);
			$this->password = $password;
		}
		if (isset($user->privilege))
			$this->userprivilege = $user->privilege;
		if (isset($user->useSecurePin))
			$this->usesSecurePin = $user->useSecurePin;
		if (isset($user->verified))
			$this->verified = $user->verified;
	}

	public static function parseUserArray($array) {
		$users = array();
		foreach ($array as $user) {
			$userModel = new UserModel();
			$userModel->parseUser($user);
			array_push($users, $userModel);
		}
		return $users;
	}


	function decryptString($sValue, $sSecretKey)
	{
	    return rtrim(
	        mcrypt_decrypt(
	            MCRYPT_RIJNDAEL_256, 
	            $sSecretKey, 
	            base64_decode($sValue), 
	            MCRYPT_MODE_ECB,
	            mcrypt_create_iv(
	                mcrypt_get_iv_size(
	                    MCRYPT_RIJNDAEL_256,
	                    MCRYPT_MODE_ECB
	                ), 
	                MCRYPT_RAND
	            )
	        ), "\0"
	    );
	}

}

class User extends Model {

	
	function __construct($db) {
		parent::__construct($db);
	}

	public function getAllWithPrivilege($pId) {
		$sql = "SELECT " . 
		BANKUSER_BANKUSERID . ", " .
		BANKUSER_FIRSTNAME . ", " . 
		BANKUSER_LASTNAME . ", " . 
		BANKUSER_EMAIL . ", ". 
        BANKUSER_MOBILE. ", " . 
        BANKUSER_ACTIVE . ", " .
        BANKUSER_PRIVILEGE. ", ".
        BANKUSER_USESSECUREPIN . ", ".
        BANKUSER_VERIFIED.
		" From " .  BANKUSER . 
		" WHERE " . BANKUSER_VERIFIED . "=1 AND "
		 . BANKUSER_PRIVILEGE . " = :pId";

		$query = $this->db->prepare($sql);
		$query->execute(array(':pId' => $pId));
		$users = $query->fetchAll();
		
		return UserModel::parseUserArray($users);
	}

	public function getUserByID($uId) {
		$sql = "SELECT " .
		BANKUSER_BANKUSERID . " , ".  
		BANKUSER_FIRSTNAME . " , ". 
		BANKUSER_LASTNAME . ", " . 
		BANKUSER_EMAIL . ", " .
		BANKUSER_MOBILE . ", " .
		BANKUSER_ACTIVE . ", " .
		BANKUSER_PASSWORD . ", " .
		BANKUSER_PRIVILEGE . ", " .
		BANKUSER_USESSECUREPIN . ", ".
		BANKUSER_VERIFIED.
		" From " . BANKUSER . 
		" WHERE " . BANKUSER_BANKUSERID . " =:userId";

        $query = $this->db->prepare($sql);
        $query->execute(array(':userId' => $uId));
       	$result = $query->fetchAll();
        if (isset($result) && isset($result[0])) {
        	$user = $result[0];
        	$userModel = new UserModel();
        	$userModel->parseUser($user);
        	return $userModel;
    	}
    	return null;
	}

	public function getUserByEmail($email) {
		$sql = "SELECT " . 
		BANKUSER_BANKUSERID . " , " . 
		BANKUSER_FIRSTNAME . " , ". 
		BANKUSER_LASTNAME . ", " . 
		BANKUSER_EMAIL . ", " . 
		BANKUSER_ACTIVE . ", " . 
		BANKUSER_PRIVILEGE . ", " .
		BANKUSER_PASSWORD . ", " .
		BANKUSER_USESSECUREPIN. ", ".
		BANKUSER_VERIFIED.
		" From " . BANKUSER . 
		" WHERE " . BANKUSER_EMAIL . " = :email";

		$query = $this->db->prepare($sql);
		$query->execute(array(':email' => $email));
		
		$result = $query->fetchAll();
		if (isset($result) && isset($result[0])) {
        	$user = $result[0];
        	$userModel = new UserModel();
        	$userModel->parseUser($user);
        	return $userModel;
    	}
    	return null;
	}

/**
* @todo set active = 0
* @todo password hashing
*/

	public function addUser($firstName, $lastName, $email, $mobile, $active, $password, $usesSecurePin) {
        $sql = "INSERT INTO " . BANKUSER  
        . " (" . BANKUSER_FIRSTNAME . ", " . BANKUSER_LASTNAME . ", ". BANKUSER_EMAIL . ", ". BANKUSER_MOBILE. ", " . BANKUSER_PASSWORD . ", " .BANKUSER_USESSECUREPIN. ", ". BANKUSER_VERIFIED .")
         VALUES (:fn, :ln, :email, :mobile, :p, :usesSecurePin, 0)";
        $query = $this->db->prepare($sql);


        $key = substr(hash("sha256", $email, false), 0, 15);
        $password = $this->encryptString($password, $key);


        return $query->execute(array(':fn' => $firstName,
        					 ':ln' => $lastName,
        					     ':email' => $email, 
        					     ':mobile' => $mobile,
        					       ':p' => $password,
        					       ':usesSecurePin'=> $usesSecurePin));

        //ADD UNACTIVATED ACCOUNT TO USER
        //$user = $this->getUserByEmail($email);
        //$this->bankAccountModel->addBankAccountToUser($user->userId);

	}

	public function removeUser($id)
	{
		$sql = "DELETE FROM " . BANKUSER . " WHERE " . BANKUSER_BANKUSERID . " = " . $id;
        $query = $this->db->prepare($sql);
        return $query->execute();
	}

	public function verifyUserById($userId) {
		$sql = "UPDATE " . BANKUSER . " SET " . BANKUSER_VERIFIED . " = (1) WHERE " . BANKUSER_BANKUSERID . " = " . $userId;
        $query = $this->db->prepare($sql);
        $query->execute();
	}

	public function isUserVerified($userId) {
		$currentUser = $this->getUserByID($userId);
		return $currentUser->verified;
	}

	public function addAdmin() {
        $sql = "INSERT INTO " . BANKUSER  
        . " (" . BANKUSER_FIRSTNAME . ", " . BANKUSER_LASTNAME . ", ". BANKUSER_EMAIL . ", ". BANKUSER_MOBILE. ", " . BANKUSER_PASSWORD . ", " .BANKUSER_PRIVILEGE.  ", " .BANKUSER_ACTIVE .  ", " .BANKUSER_VERIFIED . ", " .BANKUSER_USESSECUREPIN. ")
         VALUES (:fn, :ln, :email, :mobile, :p, 1, 1,1, :usesSecurePin)";
        $query = $this->db->prepare($sql);

        $firstName = 'Admin';
        $lastName = '';
        $email = 'fgbmunich@gmail.com';
        $mobile = '';
        $active = 1;
        $password = 'localmachineadmin';
        $usesSecurePin = 0;
        

        $key = substr(hash("sha256", $email, false), 0, 15);
        $password = $this->encryptString($password, $key);


        return $query->execute(array(':fn' => $firstName,
        					 ':ln' => $lastName,
        					     ':email' => $email, 
        					     ':mobile' => $mobile,
        					       ':p' => $password,
        					       ':usesSecurePin'=> $usesSecurePin ));
	}




	function encryptString($sValue, $sSecretKey)
	{
	    return rtrim(
	        base64_encode(
	            mcrypt_encrypt(
	                MCRYPT_RIJNDAEL_256,
	                $sSecretKey, $sValue, 
	                MCRYPT_MODE_ECB, 
	                mcrypt_create_iv(
	                    mcrypt_get_iv_size(
	                        MCRYPT_RIJNDAEL_256, 
	                        MCRYPT_MODE_ECB
	                    ), 
	                    MCRYPT_RAND)
	                )
	            ), "\0"
	        );
	}

	function decryptString($sValue, $sSecretKey)
	{
	    return rtrim(
	        mcrypt_decrypt(
	            MCRYPT_RIJNDAEL_256, 
	            $sSecretKey, 
	            base64_decode($sValue), 
	            MCRYPT_MODE_ECB,
	            mcrypt_create_iv(
	                mcrypt_get_iv_size(
	                    MCRYPT_RIJNDAEL_256,
	                    MCRYPT_MODE_ECB
	                ), 
	                MCRYPT_RAND
	            )
	        ), "\0"
	    );
	}

	/**
	* @todo set active = 0
	* @todo password hashing
	*/

	public function addEmployee($firstName, $lastName, $email, $mobile, $active, $password) {
		 $sql = "INSERT INTO " . BANKUSER  
        . " (" . BANKUSER_FIRSTNAME . ", " . BANKUSER_LASTNAME . ", ". BANKUSER_EMAIL . ", ". BANKUSER_MOBILE. ", " . BANKUSER_PASSWORD . ", ". BANKUSER_PRIVILEGE. ", ". BANKUSER_VERIFIED. ")
         VALUES (:fn, :ln, :email, :mobile, :p, 2, 0)";
        
        $query = $this->db->prepare($sql);

        $key = substr(hash("sha256", $email, false), 0, 15);
        $password = $this->encryptString($password, $key);


        return $query->execute(array(':fn' => $firstName,
        					 ':ln' => $lastName,
        					     ':email' => $email, 
        					     ':mobile' => $mobile,
        					       ':p' => $password ));
	}

	public function approveUserById($userId) {
		$sql = "UPDATE " . BANKUSER . " SET " . BANKUSER_ACTIVE . " = (1) WHERE " . BANKUSER_BANKUSERID . " = :uID ";
        $query = $this->db->prepare($sql);
        $query->execute(array(':uID' => $userId));
	}

	public function disapproveUserById($userId) {
		$sql = "UPDATE " . BANKUSER . " SET " . BANKUSER_ACTIVE . " = (0) WHERE " . BANKUSER_BANKUSERID . " = :uID ";
        $query = $this->db->prepare($sql);
        $query->execute(array(':uID' => $userId));
	}

	public function changePassword($userId, $password){
		$sql = "UPDATE " . BANKUSER . " SET " . BANKUSER_PASSWORD . " = :password WHERE " . BANKUSER_BANKUSERID . " = :uid";
        $query = $this->db->prepare($sql);
        $user = $this->getUserByID($userId);
        $key = substr(hash("sha256", $user->email, false), 0, 15);
        $password = $this->encryptString($password, $key);
        return $query->execute(array(':uid' => $userId, ':password' => $password));
	}
}
