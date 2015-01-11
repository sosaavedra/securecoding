<?php 

class TransactionModel{
	public $requestId;
	public $userId;
	public $createDate;
	public $fromAccount;
	public $toAccount;
	public $transferAmount;
	public $description;
	/*
	 * status 1 is for pending
	 * status 2 is for approved
	 * status 3 is for denied
	 * these are not defined in DB
	 */
	public $status;
	
	public function parseTransaction($transaction) {
	
		if (isset($transaction->logId))
			$this->requestId = $transaction->logId;
		if (isset($transaction->createDate))
			$this->createDate = $transaction->createDate;
		if (isset($transaction->originAccountNumber))
			$this->fromAccount = $transaction->originAccountNumber;
		if (isset($transaction->targetAccountNumber))
			$this->toAccount = $transaction->targetAccountNumber;
		if(isset($transaction->transferAmount))
			$this->transferAmount= $transaction->transferAmount;
		if(isset($transaction->userId))
			$this->userId = $transaction->userId;
		if (isset($transaction->status))
			$this->status = $transaction->status;
		if(isset($transaction->description))
			$this->description= $transaction->description;

	}
	
	public static function parseTransactionArray($array) {
		$transactions = array();
		foreach ($array as $transaction) {
			$transactionModel = new TransactionModel();
			$transactionModel->parseTransaction($transaction);
			array_push($transactions, $transactionModel);
		}
		return $transactions;
	}
}

class Transaction extends Model{	
	
	function __construct($db) {
		parent::__construct($db);
	}
		
	public function transactionHistoryForUser($userId){
		 $sql = "select " . DATATRANSACTIONLOG_LOGID .
		  ", " . DATATRANSACTIONLOG_USERID .
		  ", " . DATATRANSACTIONLOG_CREATEDDATE . 
		 ", " . DATATRANSACTIONLOG_ORIGINACCOUNTNUMBER . 
		 ", " . DATATRANSACTIONLOG_TARGETACCOUNTNUMBER .
		 ", " . DATATRANSACTIONLOG_STATUS . 
		 ", " . DATATRANSACTIONLOG_TRANSFERAMOUNT . 
		 ", " . DATATRANSACTIONLOG_DESCRIPTION .  
		 " from " . DATATRANSACTIONLOG . 
		 " where " . DATATRANSACTIONLOG_USERID .
		 " = :userID";
		 
		 $query = $this->db->prepare($sql);
		 $query->execute(array(':userID' => $userId));
		 $transactions = $query->fetchAll();

		 return TransactionModel::parseTransactionArray($transactions);
	}

	public function ingoingTransfersTransactionsForUser($userId){
		$bankAcModel = new BankAccount($this->db);
		$bankAccountInstance = $bankAcModel->getBankAccountByAccountHolderID($userId);

		 $sql = "select " . DATATRANSACTIONLOG_LOGID .
		  ", " . DATATRANSACTIONLOG_USERID .
		  ", " . DATATRANSACTIONLOG_CREATEDDATE . 
		 ", " . DATATRANSACTIONLOG_ORIGINACCOUNTNUMBER . 
		 ", " . DATATRANSACTIONLOG_TARGETACCOUNTNUMBER .
		 ", " . DATATRANSACTIONLOG_STATUS . 
		 ", " . DATATRANSACTIONLOG_TRANSFERAMOUNT .  
		 ", " . DATATRANSACTIONLOG_DESCRIPTION .  
		 " from " . DATATRANSACTIONLOG . 
		 " where " . DATATRANSACTIONLOG_TARGETACCOUNTNUMBER .
		 " = :userBankAccNo";
		 
		 $query = $this->db->prepare($sql);
		 $query->execute(array(':userBankAccNo' => $bankAccountInstance->accountNumber));
		 $transactions = $query->fetchAll();

		 return TransactionModel::parseTransactionArray($transactions);
	}
	
	/*public function processTransactionRequest($userId, $amount, $toAccount)
	{
		$bankAccount = new BankAccount($db);
		$bankAccountModel = $bankAccount->getBankAccountByAccountHolderID($userId);
		
		if($bankAccountModel->balance < $amount)
			return BANK_TRANSACTION_FAIL . NOT_ENOUGH_BALANCE;
		
		if($bankAccountModel->active = 0)
			return BANK_TRANSACTION_FAIL . BANK_ACCOUNT_DEACTIVATED;
		
		//if toaccount and fromaccount are same then return error
		
		$logId = $this->addTransactionRequest($userId, $amount, $bankAccountModel->accountNumber, $toAccount);
		
		if($amount <= 10000)
			return $this->approveTransactionRequest($logId);
			
		return PENDING_APPROVAL;
	}*/
	
	public function addTransactionRequest($userId, $amount, $fromAccount, $toAccount, $status, $description)
	{
		$sql = "INSERT INTO " . DATATRANSACTIONLOG . "(" . DATATRANSACTIONLOG_USERID . ", " . DATATRANSACTIONLOG_ORIGINACCOUNTNUMBER . 
		", " . DATATRANSACTIONLOG_TARGETACCOUNTNUMBER . ", " . DATATRANSACTIONLOG_TRANSFERAMOUNT . ", " . DATATRANSACTIONLOG_STATUS . ", " . DATATRANSACTIONLOG_DESCRIPTION . ") values(:userId, :fromAccount, :toAccount, :amount, :status, :desc)";
		
		$query = $this->db->prepare($sql);
		$query->execute(array(':userId' => $userId, ':fromAccount' => $fromAccount, ':toAccount' => $toAccount, ':amount' => $amount, ':status' => $status, ':desc' => $description));
						
	}
	
	public function approveTransactionRequest($requestId){
		$currentTransactionModel = $this->getTransactionByRequestId($requestId);
		
		if($currentTransactionModel->status <> 1)
			return BANK_TRANSACTION_FAIL . BANK_TRANSACTION_ALREADY_PROCESSED;
		
		/*$bankAccount = new BankAccount($db);
		$bankAccountModel = $bankAccount->getBankAccountByAccountHolderID($currentTransactionModel->userId);
		
		if($bankAccountModel->balance < $currentTransactionModel->transferAmount)
			return BANK_TRANSACTION_FAIL . NOT_ENOUGH_BALANCE;*/
		/*
		if($bankAccountModel->active == 0)
			return BANK_TRANSACTION_FAIL . BANK_ACCOUNT_DEACTIVATED;*/
		//add DB column for transaction type : debit or credit
		$sql = "UPDATE datatransactionlog SET status = 2 where logid = :requestid";
		
		$query = $this->db->prepare($sql);
		$query->execute(array(':requestid' => $requestId));
		
		
		return BANK_TRANSACTION_COMPLETE;
	}
	
	public function denyTransactionRequest($requestId){
		
		$currentTransactionModel = $this->getTransactionByRequestId($requestId);
		if($currentTransactionModel->status != 1)
			return BANK_TRANSACTION_FAIL . BANK_TRANSACTION_ALREADY_PROCESSED;
		
		$sql = "UPDATE " . DATATRANSACTIONLOG . " SET " . DATATRANSACTIONLOG_STATUS ." = 3 WHERE " . DATATRANSACTIONLOG_LOGID . " = :requestID";
		
		$query = $this->db->prepare($sql);
		$query->execute(array(':requestID' => $requestId));
		
		return BANK_TRANSACTION_DENIED;
	}
	
	public function viewAllPendingTransactionRequest()
	{
		$sql = "SELECT " . DATATRANSACTIONLOG_LOGID . ", " . DATATRANSACTIONLOG_CREATEDDATE . 
		", " . DATATRANSACTIONLOG_USERID . ", " . DATATRANSACTIONLOG_ORIGINACCOUNTNUMBER . 
		", " . DATATRANSACTIONLOG_TARGETACCOUNTNUMBER . ", " . DATATRANSACTIONLOG_TRANSFERAMOUNT . ", " . DATATRANSACTIONLOG_DESCRIPTION . 
		", " . DATATRANSACTIONLOG_STATUS . " from " . DATATRANSACTIONLOG . " where " . DATATRANSACTIONLOG_STATUS . " = 1";
		
		$query = $this->db->prepare($sql);
		$query->execute();
		
		$transaction = $query->fetchAll();
		
		return TransactionModel::parseTransactionArray($transaction);
	}
	
	/*
	private function updateTransactionRequest($logId, $status)
	{
		$sql = "update datatransactionlog set status = :status where logid = :logid";
		$query = $this->db->prepare($sql);
		$query->execute(array(':status' => $status, ':logid' => $logid));
	}*/
	
	public function getTransactionByRequestId($requestId)
	{
		$sql = "select " . DATATRANSACTIONLOG_LOGID . ", " . DATATRANSACTIONLOG_CREATEDDATE . ", " . DATATRANSACTIONLOG_USERID . 
		", " . DATATRANSACTIONLOG_ORIGINACCOUNTNUMBER . ", " . DATATRANSACTIONLOG_TARGETACCOUNTNUMBER . 
		", " . DATATRANSACTIONLOG_TRANSFERAMOUNT . ", " . DATATRANSACTIONLOG_STATUS . ", " . DATATRANSACTIONLOG_DESCRIPTION .  
		" from " . DATATRANSACTIONLOG . " where " . DATATRANSACTIONLOG_LOGID . " = :logId";
		
		$query = $this->db->prepare($sql);
		$query->execute(array(':logId' => $requestId));
		$transactions = $query->fetchall();
		$transaction = $transactions[0];
		$transactionModel = new TransactionModel();
		$transactionModel->parseTransaction($transaction);
		return $transactionModel;
	}
}
?>
