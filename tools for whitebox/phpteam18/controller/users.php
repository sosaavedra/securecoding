<?php
class Users extends Controller {
	function __construct() {
		parent::__construct ();
		$this->model = $this->loadModel ( 'user' );
	}

  	public function index() {

    	$user = Controller::getConnectedUser($this->db);
	    if (isset($user) && $user->userprivilege == 3) {
			$transactionModel = new Transaction($this->db);
			$transactions_outgoing = $transactionModel->transactionHistoryForUser($user->userId);
			$transactions_ingoing = $transactionModel->ingoingTransfersTransactionsForUser($user->userId);
			require 'views/head.php';
			require 'views/navigation.php';
			require 'views/users/index.php';
			require 'views/footer.php';
	    } else {
	      	header('location:'. URL);
	    }
  	}

	public function passwordRecovery() {

	    $user = Controller::getConnectedUser($this->db);
	    if (isset($user)) {
	    	header('location:'. URL);
	    } else {
	    	require 'views/head.php';
	    	require 'views/navigation.php';
	    	require 'views/users/passwordRecovery.php';
	    	require 'views/footer.php';
	    } 
  	}

  	public function recoverPassword() {
	    $user = Controller::getConnectedUser($this->db);
	    if (isset($user)) {
	    	header('location:'. URL);
	    } else {
	    	$email = $_POST ["email"];
			if (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
				$_SESSION['message'] = "Invalid email format!";
	    		header('location:'. URL);
				return;
			}
	    	$tokenModel = $this->loadModel ( 'token' );
	    	$user = $this->model->getUserByEmail($email);
	    	if (isset($user)) {
		    	$key = $tokenModel->createTokenForUserIdAndIsPassword($user->userId, $user->email, true);
		    	Controller::sendMail($email, 'password reset', 
		    		'Kindly find attached the link to reset your password ' 
		    		. URL . 'users/changePassword?key='
		    		. $key . '&userId=' . $user->userId . ' Please ignore this email if you have not requested to reset the password'
		    	 	. ' as this link will expire in 10 min. Admin');
		    	$_SESSION['message'] = 'Check your mail';
	    	} else {
	    		$_SESSION['message'] = 'email not found';
	    	}
	    	header('location:'. URL);
	    } 
  	}

	public function changePassword() {
  		$user = Controller::getConnectedUser($this->db);
	    if (isset($user)) {
	    	header('location:'. URL);
	    } else {
	    	$key = $_GET ["key"];
	    	$userId = $_GET["userId"];
	    	if (isset($key) && isset($userId)) {
				$tokenModel = $this->loadModel ( 'token' );
	    		$token = $tokenModel->getTokenByTokenAndIsPassword($key, true);
	    		if (isset($token)) {
	    			//validate token
	    			$datetime2 = Time();
    				$tokenTime = strtotime($token->createdDate);
    				$diff = ($datetime2 - $tokenTime);
    				if ($diff < 900) {
			    		if ($token->userId == $userId) {
			    	
							$clietIP = $_SERVER['REMOTE_ADDR'];
							$clietDomain = $_SERVER['REMOTE_ADDR'];
							
			    			$session_name = 'PHPSESSID'; // Set a custom session name
						    $secure = true; // Set to true if using https.
						    $httponly = true; // This stops javascript being able to access the session id. 
						    ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
						    $cookieParams = session_get_cookie_params(); // Gets current cookies params.
						    
						    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
						    session_name($session_name); // Sets the session name to the one set above.
						    session_start(); // Start the php session
						    session_regenerate_id(true); // regenerated the session, delete the old one. 
							
							$_SESSION ["userDomain"] = $clietIP;
							$_SESSION ["userIP"] = $clietIP;
							$_SESSION ["changePasswordUserId"] = $userId;
							//$_SESSION ["tan"] = $sessionTan->tanSequenceId;
							//$tanNumber = $sessionTan->tanSequenceId;
			    			require 'views/head.php';
		      				require 'views/navigation.php';
		      				require 'views/users/changePassword.php';
		      				require 'views/footer.php';
		      				return;
			    		}
			    	} else {
    	 				$tokenModel->deleteToken($userId, true);
			    	}
		    	}
	    	}
	    	header('location:'. URL);
	    }
  	}

  	public function resetPassword() {
  		session_start();
  		$tokenInSession = $_SESSION["csrf_token"];
      	unset($_SESSION["csrf_token"]);

		if (isset($_SESSION["changePasswordUserId"])) {
            if ($_SERVER['REMOTE_ADDR'] != $_SESSION["userIP"]) {
            	unset ( $_SESSION ['changePasswordUserId'] );
				//unset ( $_SESSION ['tan'] );
				unset ( $_SESSION["userIP"] );
				session_unset ();
				session_destroy ();
			    header('location:' . URL);
                return;
            }

            if($_POST["token"] == NULL) {
				$_SESSION['message'] = "Invalid token!";
				header ( 'location:' . URL );
				return;
			}

			if($_POST["token"] != $tokenInSession){
				$_SESSION['message'] = "Invalid token!";
				header ( 'location:' . URL );
				return;
			}

            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            if (!isset($password) || !isset($confirmPassword) || $confirmPassword != $password) {
            	$_SESSION['message'] = 'password & confirm password do not match';
            	require 'views/head.php';
  				require 'views/navigation.php';
  				require 'views/users/changePassword.php';
  				require 'views/footer.php';
  				return;
            }

			$uppercaseRegex='/[A-Z]/';  //Uppercase
	   		$lowercaseRegex='/[a-z]/';  //lowercase
	   		$specialCharsRegex='/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
	   		$numberRegex='/[0-9]/';  //numbers

	   		if (!(preg_match($uppercaseRegex, $password) && preg_match($lowercaseRegex, $password)
	   			&& preg_match($specialCharsRegex, $password) && preg_match($numberRegex, $password) && strlen($password) >= 8)) {
	   			$_SESSION['message'] = 'password should be >= 8 characters with at least 1 digit, 1 uppercase, 1 lowercase and 1 special character';
				require 'views/head.php';
  				require 'views/navigation.php';
  				require 'views/users/changePassword.php';
  				require 'views/footer.php';
  				return;
	   		}

            $userId = $_SESSION["changePasswordUserId"];
        	$this->model->changePassword($userId, $password);
        	$tokenModel = $this->loadModel('token');
        	$tokenModel->deleteToken($userId, true);
       		unset ( $_SESSION ['changePasswordUserId'] );
			unset ( $_SESSION ['tan'] );
			unset ( $_SESSION["userIP"] );
			session_unset ();
			session_destroy ();
       		$message = 'Password changed successfully';
       		include_once 'controller/home.php';
	        $home = new Home();
	        $home->index($message);			
        	return;
        }
        header('location:' . URL);
  	}
  	
	public function show() {
    	$user = Controller::getConnectedUser ( $this->db );
    	if (isset ( $user )) {
      		require 'views/head.php';
      		require 'views/navigation.php';
      		require 'views/users/edit.php';
      		require 'views/footer.php';
    	} else {
      		header ( 'location:' . URL );
    	}
  	}

	public function create($error = null) {
		require 'views/head.php';
		require 'views/navigation.php';
		require 'views/users/create.php';
		require 'views/footer.php';
	}

	public function edit() {
		// get user id and user info
		$user = Controller::getConnectedUser ( $this->db );
		if (isset ( $user )) {
			require 'views/head.php';
			require 'views/navigation.php';
			require 'views/users/edit.php';
			require 'views/footer.php';
		} else {
			header ( 'location:' . URL );
		}
	}


    public function all() {
      $connectedUser = Controller::getConnectedUser($this->db);
      if (isset($connectedUser)) {
        if ($connectedUser->userprivilege < 3) {
          $users = $this->model->getAllWithPrivilege(3);
          $users = array_merge($users, $this->model->getAllWithPrivilege(2));
          require 'views/head.php';
          require 'views/navigation.php';
          require 'views/users/all.php';
          require 'views/footer.php';
        } else {
        header('location:' . URL . 'users/show');
        }
      } else {
        header('location:' . URL);
      }
    }
    

    public function approve($userId) {
		$connectedUser = Controller::getConnectedUser ( $this->db );
		$tokenInSession = $_SESSION["csrf_token"];
		unset($_SESSION["csrf_token"]);
		
		if (!isset ( $connectedUser ) || $connectedUser->userprivilege == 3 ) {
			header ( 'location:' . URL );
			return;
		} 

		if($_GET ["token"] == NULL) {
			$_SESSION['message'] = "Invalid token!";
			header ( 'location:' . URL );
			return;
		}

		if($_GET["token"] != $tokenInSession){
			$_SESSION['message'] = "Invalid token!";
			header ( 'location:' . URL );
			return;
		}

		$this->model->approveUserById($userId);

		$user = $this->model->getUserById($userId);
		$user_email = $user->email;
		if($user->userprivilege == 3) {
			$bAccount = new BankAccount($this->db);
			$bAccount->activateUserBankAccount($userId);
			
			if($user->usesSecurePin == 0){
				
				$tanModel = new Tan($this->db);
				$activeTanList = $tanModel->getAllActiveTanNumbersByUserID($userId);
				$activeTanListCount = count($activeTanList);
				if($activeTanListCount == 0) { 
			  	//If the user already has some active tans, don't generate new tans for him
					$tanList = $tanModel->addTanNumbersToUser($userId);
					$destFile = ROOT . 'private/temp/' . $this->generateRandomString() . '.pdf';
					Controller::createPDF('<html>'. $tanList . '</html>', $user->password, $destFile);
					Controller::sendMail($user_email, 'Account approved','Dear ' . $user->firstName . ',<br><br> Kindly find attached your tan list. Use your FGB account credentials to access it. <br><br>Best Regards,<br>Free Gold Bank GmbH', $destFile);
				}
			}else{
				$securePin = new SecurePin($this->db);
				$pin = $securePin->getPinForUser($user->userId)->securePin;
				
				$destFile = ROOT . 'private/temp/' . $this->generateRandomString() . '.pdf'; 
				Controller::createPDF('<html>'. $pin . '</html>', $user->password, $destFile);

				Controller::sendMail($user_email, 'Account approved','Dear ' . $user->firstName . ',<br><br> Kindly find attached your Smart Card PIN.<br><br>Best Regards,<br>Free Gold Bank GmbH ', $destFile);
				$dir = ROOT . "private/temp/" . $user->userId . "/";
				shell_exec('cp -rf '. ROOT . 'libs/SmartCardSim/src/ ' . $dir);
				shell_exec('echo "class Config { protected static String email = \"'. $user->email .'\"; }" >  '. $dir .'Config.java');
				$simFile = $dir . 'FGB-SCS.jar';
				shell_exec('javac '. $dir .'*.java');
				shell_exec('cd '. $dir . '; jar -cvfm ' . $simFile . ' META-INF/MANIFEST.MF *.class');
				Controller::sendMail($user_email, 'Smart Card Sim - SCS','Dear ' . $user->firstName . ',<br><br> Kindly find attached your Smart Card Sim.<br><br>Best Regards,<br>Free Gold Bank GmbH ', $simFile);
				shell_exec('rm -rf '. $dir);
			}
						
		}
		$this->all();
    }

    function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
    	return $randomString;
	}

    public function disapprove($userId) {
    	$connectedUser = Controller::getConnectedUser ( $this->db );
		$tokenInSession = $_SESSION["csrf_token"];
		unset($_SESSION["csrf_token"]);
		if (!isset ( $connectedUser ) || $connectedUser->userprivilege == 3 ) {
			header ( 'location:' . URL );
			return;
		}
				
		if($_GET ["token"] == NULL) {
			$_SESSION['message'] = "Invalid token!";
			header ( 'location:' . URL );
			return;
		}

		if($_GET["token"] != $tokenInSession){
			$_SESSION['message'] = "Invalid token!";
			header ( 'location:' . URL );
			return;
		}
		
     	$this->model->disapproveUserById($userId);
      	//include_once 'models/bankaccount.php';
     	$user = $this->model->getUserById($userId);
      	if($user->userprivilege == 3) {
        	$bAccount = new BankAccount($this->db);
        	$bAccount->deactivateUserBankAccount($userId);
      	}

      	$this->all();
    }

	public function login() {

		if (! isset ( $_POST ["email"] ) || strlen($_POST ["email"]) < 6) {
			if(! isset($_POST ["email"])){
				$message = 'Please enter an email.';
			}
			else {
				$message = 'Invalid email.';
			}
			include_once 'controller/home.php';
	        $home = new Home();
	        $home->index($message, true);
        	return;
		}

		if (! isset ( $_POST ["password"] ) || strlen($_POST ["password"]) < 8) {
			if(! isset($_POST ["password"])){
				$message = 'Please enter a password.';
			}
			else {
				$message = 'Wrong password.';
			}
			include_once 'controller/home.php';
	        $home = new Home();
	        $home->index($message, true);
        	return;
		}
		
		
		
		$email = $_POST ["email"];
		if (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
			$message = "Invalid email format!";
			include_once 'controller/home.php';
	        $home = new Home();
	        $home->index($message, true);
        	return;
		}

		$user = $this->model->getUserByEmail ( $email );

		$password = $_POST ["password"];

		
		if ($password == $user->password) {
			if ($user->active == 1) {

				$session_name = 'PHPSESSID'; // Set a custom session name
			    $secure = true; // Set to true if using https.
			    $httponly = true; // This stops javascript being able to access the session id. 
			    ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
			    $cookieParams = session_get_cookie_params(); // Gets current cookies params.
			    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
			    session_name($session_name); // Sets the session name to the one set above.
			    session_start(); // Start the php session
			    session_regenerate_id(true); // regenerated the session, delete the old one. 

				$clietIP = $_SERVER['REMOTE_ADDR'];
				$clietDomain = $_SERVER['REMOTE_ADDR'];
				$_SESSION ["userDomain"] = $clietIP;
				$_SESSION ["userIP"] = $clietIP;
				$_SESSION ["userId"] = $user->userId;				
				if ($user->userprivilege == 3)
					header ( 'location: ' . URL . 'users/index' );
				else if ($user->userprivilege == 2)
					header ( 'location: ' . URL . 'employees/index' );
				else if ($user->userprivilege == 1)
					header ( 'location: ' . URL . 'admin/index' );
			} else {
				$message = 'not active yet';
				include_once 'controller/home.php';
		        $home = new Home();
		        $home->index($message);
	        	return;
			}
		} else {
			$message = 'Wrong email/password';
			include_once 'controller/home.php';
	        $home = new Home();
	        $home->index($message);
        	return;
		}
	}
	public function logout() {
		session_start ();
		unset ( $_SESSION ['userId'] );
		unset ( $_SESSION ['tan'] );
		unset ( $_SESSION["userIP"] );
		// session_write_close();
		session_unset ();
		session_destroy ();
		include_once 'controller/home.php';
        $home = new Home();
        $message = "session closed";
        $home->index($message);
		header ( 'location:' . URL);
	}

	public function register() {
		$connectedUser = Controller::getConnectedUser ( $this->db );
		if (isset ( $connectedUser )) {
			session_start ();
			unset ( $_SESSION ['userId'] );
			unset ( $_SESSION ['tan'] );
			unset ( $_SESSION["userIP"] );
			// session_write_close();
			session_unset ();
			session_destroy ();
		}
		
		if ($_POST["firstName"] == NULL)
		{
			$this->create(1);
			return;
		}
		if ($_POST["lastName"] == NULL)
		{
			$this->create(2);
			return;
		}
		if ($_POST["email"] == NULL)
		{
			$this->create(3);
			return;
		}
		if ($_POST["password"] == NULL)
		{
			$this->create(4);
			return;
		}
		if ($_POST["confirmPassword"] == NULL)
		{
			$this->create(5);
			return;
		}
		$password = $_POST ["password"];

		//Strong password check
		$uppercaseRegex='/[A-Z]/';  //Uppercase
   		$lowercaseRegex='/[a-z]/';  //lowercase
   		$specialCharsRegex='/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
   		$numberRegex='/[0-9]/';  //numbers

   		if (!(preg_match($uppercaseRegex, $password) && preg_match($lowercaseRegex, $password)
   			&& preg_match($specialCharsRegex, $password) && preg_match($numberRegex, $password) && strlen($password) >= 8))
   		{
   			$this->create(7);
			return;
   		}

		$confirmPassword = $_POST ["confirmPassword"];
		if ($password != $confirmPassword) {
			$this->create(5);
			return;
		}
		$email = $_POST ["email"];
				
		if (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
			$this->create(6);
			return;
		}

		if ($_POST["mobile"] != NULL)
		{
			if(!is_numeric($_POST["mobile"]))
			{
				$this->create(8);
				return;
			}
		}
		
		$useSCS = 1;
		if($_POST["useSCS"] !== "SCS"){
			$useSCS = 0;
		}
		
		$queryExecuted = $this->model->addUser ( $_POST ["firstName"], $_POST ["lastName"], $email, $_POST ["mobile"], 0, $password, $useSCS);
		
		// include_once 'models/bankaccount.php';
		$bankAcc = new BankAccount ( $this->db );
		$user = $this->model->getUserByEmail ( $email );
		$bankAcc->addBankAccountToUser ( $user->userId );


		$tokenModel = $this->loadModel ( 'token' );
		$key = $tokenModel->createTokenForUserIdAndIsPassword($user->userId, $user->email, false);
		Controller::sendMail($email,'Welcome to FGB, '.$user->firstName, 
		'Dear  '. $user->firstName . ',<br><br><br>
		This is an automatic response sent to those who wish to register
		an email address with FGB in order to verify that this is a valid address. 
		<br><br><br> 
		To register the following email address('.$user->email.')to "'.$user->firstName . ' ' . $user->lastName.'"
		please follow the link below (It will expire after 10 minutes):
		<br><br> ' 
		. URL . 'users/confirm_verification?key='.$key . '&userId=' . $user->userId . 
		'<br><br><br> If you\'re not '.$user->firstName . ' '.$user->lastName .' or didn\'t register for an FGB account, you can ignore this email.'
		. ' <br><br>
		Best Regards,<br>FGB');
		

		if ($queryExecuted){
			if($useSCS == 1){			
			$securePin = $this->loadModel('securepin');
			$securePin->addSecurePin($user->userId, rand(100000,999999));
			}
		}
    	else 
    	{
    		$this->create(9);
			return;
		}
		include_once 'controller/home.php';
        $home = new Home();
        //$message = "Thank you for your interest in FGB. You will receive an email with your TAN list once your account is activated.";
        $message = "Thank you for your interest in FGB. We sent you an email, please read it at the moment to complete the registration process. If you read your email later than 10 minutes your registration will be aborted.";
        $home->index($message);
		
	}

	public function confirm_verification() {
		$connectedUser = Controller::getConnectedUser ( $this->db );
		if (isset ( $connectedUser )) {
			session_start ();
			unset ( $_SESSION ['userId'] );
			unset ( $_SESSION ['tan'] );
			unset ( $_SESSION["userIP"] );
			// session_write_close();
			session_unset ();
			session_destroy ();
		}
		$tokenModel = $this->loadModel ( 'token' );
		$userModel = $this->loadModel ( 'user' );
		$bankAccountModel = $this->loadModel ( 'bankAccount' );
	    	$key = $_GET ["key"];
	    	$userId = (int)$_GET["userId"];
	    	$user = $userModel->getUserById($userId);
	    	if (isset($key)) {
	    		$token = $tokenModel->getTokenByTokenAndIsPassword($key, false);
	    		if (isset($token)) {
	    			//validate token
	    			$datetime2 = Time();
    				$tokenTime = strtotime($token->createdDate);
    				$diff = ($datetime2 - $tokenTime);
    				$message = "";
    				if ($diff < 600) {
        				$userModel->verifyUserById($userId);
        				if($user->userprivilege == 3){
        					$message = "Thank you for your interest in FGB. Your account is now verified. You will receive an email with your TAN list once your account is activated.";
        					include_once 'controller/home.php';
							$home = new Home();
							$home->index($message);
        				}
        				else
        				{
        					$message = "Thank you for your interest in FGB. Your account is now verified. You will be able to log into your account once approved by the system.";
        					include_once 'controller/home.php';
							$home = new Home();
							$home->index($message);
        				}
			    	} else {

			    		if(!$userModel->isUserVerified($userId)){
			    			if($user->userprivilege == 3){
				    			$bankAccountModel->removeAccount($userId);
				    		}
				    		$userModel->removeUser($userId);
	    	 			}
	    	 			$tokenModel->deleteTokenByToken($key);
    	 				$message = "Sorry, this token has expired.";
    	 				include_once 'controller/home.php';
						$home = new Home();
						$home->index($message, true);
			    	}
		    	}
		    	else
		    	{
		    		$message = "Sorry, this token has expired.";
		    		include_once 'controller/home.php';
					$home = new Home();
					$home->index($message, true);
		    	}
	    	}
			if(isset($_SESSION)){
				unset ( $_SESSION ['userId'] );
				unset ( $_SESSION ['tan'] );
				unset ( $_SESSION["userIP"] );
				session_unset ();
				session_destroy ();
			}
			
  	}
	

	public function profile($userId, $error_message = null) {
		$connectedUser = Controller::getConnectedUser ( $this->db );
		if (isset ( $connectedUser )) {
			$user = $this->model->getUserById ( $userId );
			
			$transactionModel = new Transaction ( $this->db );
			 $transactions_outgoing = $transactionModel->transactionHistoryForUser($user->userId);
			$transactions_ingoing = $transactionModel->ingoingTransfersTransactionsForUser($user->userId);
      
			require 'views/head.php';
			require 'views/navigation.php';
			require 'views/users/profile.php';
			require 'views/footer.php';
		} else {
			header ( 'location:' . URL );
		}
	}
	public function createTransaction() {
		$connectedUser = Controller::getConnectedUser ( $this->db );
		if (isset ( $connectedUser )) {
			if ($connectedUser->userprivilege == 3) {
				
				if($connectedUser->usesSecurePin !== 1){
					$tan = new Tan ( $this->db );
					$tanModelArray = $tan->getAllActiveTanNumbersByUserID ( $connectedUser->userId );
				
					if (! empty ( $tanModelArray )) {
					// TODO might have to clear session
					
						$sessionTans = array_values ( $tanModelArray );
						$sessionTan = $sessionTans[0];
						$_SESSION ["tan"] = $sessionTan->tanSequenceId;
					//$tan->updateTanToInactive ( $sessionTan->tanSequenceId, $connectedUser->userId );
						
					}
					
					require 'views/head.php';
					require 'views/navigation.php';
					require 'views/users/createtransaction.php';
					require 'views/footer.php';
					return;
				}
				
			}

			$_SESSION['message'] = 'please obtain new tan numbers';
			header ( 'location:' . URL . 'users/index' );
		} else {
			header ( 'location:' . URL . 'users/index' );
			return;
		}
	}

	public function submitTransaction() {
		$connectedUser = Controller::getConnectedUser ( $this->db );
		$tokenInSession = $_SESSION["csrf_token"];
		unset($_SESSION["csrf_token"]);
		if (isset ( $connectedUser )) {
			if ($connectedUser->userprivilege == 3) {
				if ($_POST ["accountNumber"]==NULL || $_POST ["amount"] == NULL || $_POST ["tan"] == NULL) {

					$_SESSION['message'] = "Required field missing";
					header ( 'location:' . URL . 'users/createtransaction' );
					return;
				} else {
					if($_POST ["token"] == NULL)
					{
						$_SESSION['message'] = "Invalid token!";
						header ( 'location:' . URL );
						return;
					}
					else
					{

						if($_POST["token"] != $tokenInSession){
							$_SESSION['message'] = "Invalid token!";
							header ( 'location:' . URL );
							return;
						}
						else
						{
							
							$transaction = new Transaction ( $this->db );
							$tan = new Tan ( $this->db );
							$bankAccount = new BankAccount ( $this->db );
							$hasTanMatched = false;
							
							//Check if user uses simulator
							if($connectedUser->usesSecurePin)
							{
								$securePin = new SecurePin($this->db);
								$pin = $securePin->getPinForUser($connectedUser->userId)->securePin;
								//echo " pin :" . $pin. " ";
								$tans = $tan->getAllowedSimulatorTan($pin, $_POST ["accountNumber"].$_POST ["amount"], $connectedUser->email);
								
								foreach($tans as $eachTan){
									//echo $eachTan. "  ";
									if($eachTan == $_POST ["tan"]){
										$hasTanMatched = true;
										break;
									}
								}
								//echo "  the submitted tan : ".$_POST ["tan"];
								//return;
								
							}else{
								$dbTan = $tan->getTanByTanSequenceIdAndUser ( $connectedUser->userId, $_SESSION ['tan'] );

								if($dbTan->tan == $_POST ["tan"]){
									$tan->updateTanToInactive ( $_SESSION ['tan'] , $connectedUser->userId );
									$hasTanMatched = true;
								}

							}
												
							$receiverAccount = $bankAccount->getBankAccountByNumber($_POST ["accountNumber"]);
							if($receiverAccount->accountNumber == -1)
							{

								$_SESSION['message'] = 'Receiver account is invalid';
								header ( 'location:' . URL . 'users/createtransaction' );
								unset ( $_SESSION ['tan'] );
								return; 
							}			
							if ($hasTanMatched) {					
								$userAccountNumber = $bankAccount->getBankAccountByAccountHolderID ( $connectedUser->userId )->accountNumber;

								if ($_POST ["amount"] < 10000) {
									$transferStatus = $bankAccount->transferMoney ( $userAccountNumber, $_POST ["accountNumber"], $_POST ["amount"] );
									
									if ($transferStatus == ACCOUNT_TRANSFER_DONE) {
										
										$_SESSION['message'] = 'Transfer request processed';
										$transaction->addTransactionRequest ( $connectedUser->userId, $_POST ["amount"], $userAccountNumber, $_POST ["accountNumber"], 2, $_POST["description"] );
										header ( 'location:' . URL . 'users/createtransaction' );
										unset ( $_SESSION ['tan'] );
										return;
									} else if ($transferStatus == ACCOUNT_TRANSFER_DONE . ACCOUNT_FOREIGN_TARGET) {

										$_SESSION['message'] = 'Transfer request processed';
										$transaction->addTransactionRequest ( $connectedUser->userId, $_POST ["amount"], $userAccountNumber, $_POST ["accountNumber"], 2, $_POST["description"] );
										header ( 'location:' . URL . 'users/createtransaction' );
										unset ( $_SESSION ['tan'] );
										return;
									}
									else {
									$_SESSION['message'] = 'message=insufficient funds';
									header ( 'location:' . URL . 'users/createtransaction');
									unset ( $_SESSION ['tan'] );
									return;
								}
								} else {
									$_SESSION['message'] = 'Transfer request submitted';
									$transaction->addTransactionRequest ( $connectedUser->userId, $_POST ["amount"], $userAccountNumber, $_POST ["accountNumber"], 1, $_POST["description"] );
									header ( 'location:' . URL . 'users/createtransaction' );
									unset ( $_SESSION ['tan'] );
									return;
								}
								
								// TODO returns to which page
							} else {						
								$_SESSION['message'] = 'tan did not match';
								header ( 'location:' . URL . 'users/createtransaction' );
								unset ( $_SESSION ['tan'] );
								return;
								// TODO returns to which page
							}

						}

					}
					

					
				}
			}
		}
	}

	public function uploadTransactionFile() {
		$connectedUser = Controller::getConnectedUser ( $this->db );
		$tokenInSession = $_SESSION["csrf_token"];
		unset($_SESSION["csrf_token"]);
		if (isset ( $connectedUser )) {
			if ($connectedUser->userprivilege == 3) {
				
				if($_POST ["token"] == NULL) {
					$_SESSION['message'] = "Invalid token!";
					header ( 'location:' . URL );
					return;
				}

				if($_POST["token"] != $tokenInSession){
					$_SESSION['message'] = "Invalid token!";
					header ( 'location:' . URL );
					return;
				}

				if($_FILES["file"]["size"] > 2097152)
				{

					$_SESSION['message'] = 'Max file size is 2 MB';
					header ( 'location:' . URL . 'users/createtransaction' );						
					return;
				}
				
				//$target_dir = $_SERVER ['DOCUMENT_ROOT'] . "/uploads/";
				
				//$fileName = $connectedUser->userId;				
				$target_dir = $_FILES ["file"] ["tmp_name"];
				//$uploadOk = 1;
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$fileType = finfo_file($finfo, $target_dir); 
				//echo $target_dir;

				if($fileType != 'text/plain')
				{

					$_SESSION['message'] = 'Nice try. This time upload a text file';
					header ( 'location:' . URL . 'users/createtransaction' );						
					return;
				}									
				
 				$outputJson = shell_exec("cd ".ROOT."libs/; ./a.out '" . $target_dir . "'");
 								
				$decodedJson = json_decode($outputJson);
				$decodedTan = $decodedJson->tan;
							
								
				$transaction = new Transaction ( $this->db );
				$tan = new Tan ( $this->db );
				$bankAccount = new BankAccount ( $this->db );
				
				$hasTanMatched = false;
				//check if user uses SCS
				//echo "uses pin ?". $connectedUser->usesSecurePin . " " ;
				//return;
				if($connectedUser->usesSecurePin ==1)
					{
						$securePin = new SecurePin($this->db);
						$pin = $securePin->getPinForUser($connectedUser->userId)->securePin;
						$key="";
						
						foreach($decodedJson->details as $tran)
						{
							$key .= $tran->account;
							$key .= $tran->amount;
						}
						$tans = $tan->getAllowedSimulatorTan($pin, $key, $connectedUser->email);
						/*echo "pin ". $pin;
						echo "key" . $key;
						echo "mail " . $connectedUser->email;
						echo "Decoded tan ".$decodedTan;
						var_dump($tans);*/
						foreach($tans as $eachTan) { 
							if($eachTan == $decodedTan){
								$hasTanMatched = true;
								break;
							}						
						}
					} else {
						$dbTan = $tan->getTanByTanSequenceIdAndUser ( $connectedUser->userId, $_SESSION ['tan'] );
						if($dbTan == $decodedTan){
							$tan->updateTanToInactive ( $_SESSION ['tan'] , $connectedUser->userId );
							$hasTanMatched = true;
						}				
					}
								//return;
				//$dbTan = $tan->getTanByTanSequenceIdAndUser ( $connectedUser->userId, $_SESSION ['tan'] );
				if (!$hasTanMatched) {

					$_SESSION['message'] = 'tan did not match';
					header ( 'location:' . URL . 'users/createtransaction');
					return;					
				}				
				
				$userAccountNumber = $bankAccount->getBankAccountByAccountHolderID ( $connectedUser->userId )->accountNumber;
				
				foreach($decodedJson->details as $tran)
				{
					$receiverAccount = $bankAccount->getBankAccountByNumber($tran->account);
					//echo "Receiver account";
					var_dump($receiverAccount);
					if($receiverAccount->accountNumber == -1){						
						continue;
					}								
					
					if (floatval($tran->amount) < 10000) {
						$transferStatus = $bankAccount->transferMoney ( $userAccountNumber, $tran->account, $tran->amount );
							//echo " Status : ".$transferStatus;
						if ($transferStatus == ACCOUNT_TRANSFER_DONE) {
							$transaction->addTransactionRequest ( $connectedUser->userId, $tran->amount, $userAccountNumber, $tran->account, 2 );
							continue;
						} else if ($transferStatus == ACCOUNT_TRANSFER_DONE . ACCOUNT_FOREIGN_TARGET) {
							$transaction->addTransactionRequest ( $connectedUser->userId, $tran->amount, $userAccountNumber, $tran->account, 2 );
							continue;
						} else {
							continue;
						}
					} else {
						$transaction->addTransactionRequest ( $connectedUser->userId, $tran->amount, $userAccountNumber, $tran->account, 1 );
						continue;
					}
				}
					//return;
				$_SESSION['message'] = 'processed';
				header ( 'location:' . URL . 'users/createtransaction');
				unset ( $_SESSION ['tan'] );
				return;	
			}
		}
	}
	public function loadAmount()
	{		
		$connectedUser = Controller::getConnectedUser ( $this->db );
		
		if (isset ( $connectedUser )) {			
			if ($connectedUser->userprivilege == 1 || $connectedUser->userprivilege == 2) {					
				if(isset($_POST["amount"]) && isset($_POST["userId"]))
				{
					if(is_numeric($_POST["amount"]) && is_numeric($_POST["userId"]))
					{
						$amount = $_POST["amount"];
						$userId = $_POST["userId"];
					if($amount < 1 || $amount >100000)
					{
						$_SESSION['message'] = 'Invalid amount.Maximum upto 100000';
						header('location:' . URL . 'users/profile/'.$userId);
						return;
					}
					
					$bankAccount = new BankAccount($this->db);
										
					$bankAccountModel=$bankAccount->getBankAccountByAccountHolderID($userId);					
					
						if(	$bankAccountModel->accountNumber != -1)
						{
							$result = $bankAccount->depositMoneyToBankAccount($bankAccountModel->accountNumber, $amount);
						
							if($result == ACCOUNT_DEPOSIT_DONE){
								$_SESSION['message'] = 'deposit done';						
								header('location:'.URL.'users/profile/'.$userId);
								return;
							}else{
								$_SESSION['message'] = 'an error occured';
								header('location:'.URL.'users/profile/'.$userId);
								return;
							}						
						}else{
							$_SESSION['message'] = 'account could not be loaded';
							header('location:'.URL.'users/profile/'.$userId);
							return;
						}
					}else{
						$_SESSION['message'] = 'Input not in correct format';
						header('location:' . URL . 'users/all');
						return;
					}								
				}else{
						$_SESSION['message'] = 'No Input provided';
						header('location:' . URL . 'users/all');
						return;
					}
			 }else{

				$_SESSION['message'] = 'Operation not supported';
				header('location:' . URL . 'users/all');
				return;
			}
		}
	}
}
