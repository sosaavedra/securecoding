<?php
class Transactions extends Controller {
	function __construct() {
		parent::__construct ();
		$this->model = $this->loadModel ( 'transaction' );
	}
    public function approve($transactionId, $profileUserId) {		
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

      $connectedUser = Controller::getConnectedUser($this->db);
      if (isset($connectedUser)) {
        $userModel = new User($this->db);
        $user = $userModel->getUserById($profileUserId);


        $transactionModel= new Transaction($this->db);
        $transactionInstance = $transactionModel->getTransactionByRequestId($transactionId);

        $bankAccountModel= new BankAccount($this->db);
        $transferStatus = $bankAccountModel->transferMoney($transactionInstance->fromAccount, $transactionInstance->toAccount, $transactionInstance->transferAmount);

		if($transferStatus == ACCOUNT_TRANSFER_FAIL) {
			
			$error_message = "Insufficient Funds.";
		}
		else {
			$transactionModel->approveTransactionRequest($transactionId);			
		}	
		include_once 'controller/users.php';
		$users_controller = new Users();
		$users_controller->profile($profileUserId,$error_message);
      }
      else {
        header('location:' . URL);
      }
    }


    public function disapprove($transactionId, $profileUserId) {
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
      
      $connectedUser = Controller::getConnectedUser($this->db);
      if (isset($connectedUser)) {
        $userModel = new User($this->db);
        $user = $userModel->getUserById($profileUserId);

        $transactionModel= new Transaction($this->db);
        $transactionModel->denyTransactionRequest($transactionId);


        include_once 'controller/users.php';
        $users_controller = new Users();
        $users_controller->profile($profileUserId);
      }
      else {
        header('location:' . URL);
      }
    }

}
