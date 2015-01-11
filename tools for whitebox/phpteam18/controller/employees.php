<?php

class Employees extends Controller {

  function __construct () {
      parent::__construct();
      $this->model = $this->loadModel('user');
  }

  public function index() {
    $user = Controller::getConnectedUser($this->db);
      if (isset($user) && $user->userprivilege != 3 ) {
      	//Warning validate!!!!!
        require 'views/head.php';
        require 'views/navigation.php';
        require 'views/employees/index.php';
        require 'views/footer.php';
      } else {
        header('location:'. URL);
      }
    }

  public function create($error = null) {
    require 'views/head.php';
    require 'views/navigation.php';
    require 'views/employees/create.php';
    require 'views/footer.php';
  }

	public function register() {
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



		$queryExecuted = $this->model->addEmployee ( $_POST ["firstName"], $_POST ["lastName"], $email, $_POST ["mobile"], 0, $password );
		if ($queryExecuted){}
    	else 
    	{
    		$this->create(9);
			return;
		}


		$user = $this->model->getUserByEmail ( $email );

		$tokenModel = $this->loadModel ( 'token' );
		$key = $tokenModel->createTokenForUserIdAndIsPassword($user->userId, $user->email, true);
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




		include_once 'controller/home.php';
        $home = new Home();
        $message = "Thank you for your interest in FGB. We sent you an email, please read it at the moment to complete the registration process. If you read your email later than 10 minutes your registration will be aborted.";
        //$message = "Thank you for your interest in working with us. You will be notified through the email you provided once approved.";
        $home->index($message);
	}

}
