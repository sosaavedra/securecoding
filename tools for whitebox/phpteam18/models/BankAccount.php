<?php


class BankAccountModel {
	public $accountNumber = -1;
	public $accountHolder;
	public $createdDate;
	public $modifiedDate;
	public $balance;
	public $active;

	public function parseAccount($account) {
		if(isset($account)){
		if (isset($account->accountNumber))
			$this->accountNumber = $account->accountNumber;
		if (isset($account->accountHolder))
			$this->accountHolder = $account->accountHolder;
		if (isset($account->createdDate))
			$this->createdDate = $account->createdDate;
		if (isset($account->modifiedDate))
			$this->modifiedDate = $account->modifiedDate;
		if (isset($account->balance))
			$this->balance = $account->balance;
		if (isset($account->active))
			$this->active = $account->active;
		}
	}
}


class BankAccount extends Model {
	private $userModel;
	
	function __construct($db) {
		parent::__construct($db);
		$this->userModel = new User($db);
	}

	public function getBankAccountByNumber($accountNumber) {
		
		$sql = "SELECT " . ACCOUNT_ACCOUNTNUMBER . ", " .
			ACCOUNT_ACCOUNTHOLDER . ", " .
			ACCOUNT_CREATEDDATE . ", " .
			ACCOUNT_MODIFIEDDATE . ", " .
			ACCOUNT_BALANCE . ", " .
			ACCOUNT_ACTIVE .
			" FROM " . ACCOUNT . " WHERE " . ACCOUNT_ACCOUNTNUMBER . " =:accId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':accId' => $accountNumber));
        $bankAccounts = $query->fetchAll();
        $bankAccount = $bankAccounts[0];     

        $bankAccountModel = new BankAccountModel();
        $bankAccountModel->parseAccount($bankAccount);        
        return $bankAccountModel;
	}

	public function getBankAccountByAccountHolderID($accountHolderId) {
		$sql = "SELECT " . ACCOUNT_ACCOUNTNUMBER . ", " .
			ACCOUNT_ACCOUNTHOLDER . ", " .
			ACCOUNT_CREATEDDATE . ", " .
			ACCOUNT_MODIFIEDDATE . ", " .
			ACCOUNT_BALANCE . ", " .
			ACCOUNT_ACTIVE .
			" FROM " . ACCOUNT . " WHERE " . ACCOUNT_ACCOUNTHOLDER . " = :accHolderId";
        $query = $this->db->prepare($sql);
        $query->execute(array(':accHolderId' => $accountHolderId));
        $bankAccounts = $query->fetchAll();
        $bankAccount = $bankAccounts[0];
        
        $bankAccountModel = new BankAccountModel();
        $bankAccountModel->parseAccount($bankAccount);
        return $bankAccountModel;
	}

	public function getAccountOwnerByAccountNumber($accountNumber) {

		$bankAccount = $this->getBankAccountByNumber($accountNumber);
		//echo "getAccountOwnerByAccountNumber():->" . $bankAccount->accountHolder;
		$userID = $bankAccount->accountHolder;
		return $this->userModel->getUserByID($userID);
	}

	public function addBankAccountToUser($owner) {
		$generatedAccountNumber = "SB-" . $owner;
		$initialBalance = 0;
		//$currentDateTime = date("Y-m-d H:i:s");

        $sql = "INSERT INTO " . ACCOUNT .
        	" ( " .
        	ACCOUNT_ACCOUNTNUMBER . ", " .
        	ACCOUNT_ACCOUNTHOLDER . ", " .
        	ACCOUNT_ACTIVE . ", " .
			ACCOUNT_BALANCE .
			" ) VALUES (:acc , :o , :actv, :b)";

		//echo $sql;
        $query = $this->db->prepare($sql);
        $query->execute(
        	array(
        	':acc' => $generatedAccountNumber,
        	':o' => $owner,
        	':actv' => FALSE,
        	':b' => $initialBalance
        	));

	}

	public function activateUserBankAccount($userID) {
		$accId = $this->getBankAccountByAccountHolderID($userID)->accountNumber;
		$sql = "UPDATE " . ACCOUNT .
		" SET " . ACCOUNT_ACTIVE ." = 1 WHERE " . ACCOUNT_ACCOUNTNUMBER . " = :accN";
        $query = $this->db->prepare($sql);
        $query->execute(array(':accN' => $accId));
	}

	public function deactivateUserBankAccount($userID) {
		$accId = $this->getBankAccountByAccountHolderID($userID)->accountNumber;
		$sql = "UPDATE " . ACCOUNT .
		" SET " . ACCOUNT_ACTIVE ." = 0 WHERE " . ACCOUNT_ACCOUNTNUMBER . " = :accN";
        $query = $this->db->prepare($sql);
        $query->execute(array(':accN' => $accId));
	}

	private function updateBalance($accountNumber, $newBalance) {
		$sql = "UPDATE " . ACCOUNT .
		" SET " . ACCOUNT_BALANCE ." = :newB WHERE " . ACCOUNT_ACCOUNTNUMBER . " = :accN";
        $query = $this->db->prepare($sql);
        $query->execute(array(':newB' => $newBalance, ':accN' => $accountNumber));
	}

	public function withdrawMoneyFromBankAccount($accountNumber, $withdrawAmount) {
		$currentAccount = $this->getBankAccountByNumber($accountNumber);
		if($currentAccount->accountNumber == -1)
		{
			return ACCOUNT_DOES_NOT_EXIST; 
		}
		$currentAccountBalance = $currentAccount->balance;

		if($currentAccountBalance < $withdrawAmount) {
			// Not enough money in balance!
			return ACCOUNT_INSUFFICIENT_BALANCE;
		}
		else {
			$newBalance = $currentAccountBalance - $withdrawAmount;
			$this->updateBalance($accountNumber, $newBalance);
			return ACCOUNT_WITHDRAW_DONE;
		}
	}

	public function depositMoneyToBankAccount($accountNumber, $depositAmount) {
		$currentAccount = $this->getBankAccountByNumber($accountNumber);
		if($currentAccount->accountNumber == -1){
			
			return ACCOUNT_DOES_NOT_EXIST;}
		$currentAccountBalance = $currentAccount->balance;
		$newBalance = $currentAccountBalance + $depositAmount;
		$this->updateBalance($accountNumber, $newBalance);
		
		return ACCOUNT_DEPOSIT_DONE;
	}

	public function removeAccount($id)
	{
		$sql = "DELETE FROM " . ACCOUNT . " WHERE " . ACCOUNT_ACCOUNTHOLDER . " = " . $id;
        $query = $this->db->prepare($sql);
        return $query->execute();
	}


	public function transferMoney($senderAccountNumber, $receiverAccountNumber, $amount) {
		$receiverAccount = $this->getBankAccountByNumber($receiverAccountNumber);
		if($receiverAccount->accountNumber == -1)
		{
			return ACCOUNT_TRANSFER_FAIL.ACCOUNT_DOES_NOT_EXIST;
		}
		$withdrawStatus = $this->withdrawMoneyFromBankAccount($senderAccountNumber, $amount);
			if($withdrawStatus == ACCOUNT_INSUFFICIENT_BALANCE)
				return ACCOUNT_TRANSFER_FAIL;
			
		$depositStatus = $this->depositMoneyToBankAccount($receiverAccountNumber, $amount);
		if($depositStatus == ACCOUNT_DOES_NOT_EXIST)
			return ACCOUNT_TRANSFER_DONE.ACCOUNT_FOREIGN_TARGET;
		else return ACCOUNT_TRANSFER_DONE;
	}

}
