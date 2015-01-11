<div class="jumbotron" >


<ol class="breadcrumb">
      <li class="active"><?php $name = $user->firstName . " " .  $user->lastName; echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');?></li>
    </ol>


  <div class="container">
  <h1 class="text-center"><?php echo htmlspecialchars($user->firstName . " " . $user->lastName, ENT_QUOTES, 'UTF-8');?></h1>
 		<h5 class="text-center">
 			<a href='<?php echo URL?>users/edit'>Edit Profile Information</a> | 
			<a href='<?php echo URL?>users/createtransaction'>Make a Transfer</a> 
		</h5>
		<?php 
		$bAccountModel = new BankAccount($this->db);
    	$bankAc = $bAccountModel->getBankAccountByAccountHolderID($user->userId);
		?>
		<address>
		<strong><?php echo htmlspecialchars($user->firstName . " " . $user->lastName, ENT_QUOTES, 'UTF-8');?></strong><br>
  		<a href="mailto:<?php echo $user->email;?>"><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8');?></a><br>
  		<abbr title="Phone">P:</abbr> <?php echo htmlspecialchars($user->mobile, ENT_QUOTES, 'UTF-8'); ?>
  	</address>

  	<address>
  		<strong>Account Details</strong><br>
  		<?php echo $bankAc->accountNumber;?><br>
  		<?php echo $bankAc->balance;?> €<br>
	</address>

	<address>
  		<strong>Created</strong><br>
  		<?php echo $bankAc->createdDate;?> <br>
	</address>

	<address>
  		<strong>Last Modified</strong><br>
  		<?php echo $bankAc->modifiedDate;?><br>
	</address>


	<hr>
    
		<?php if(count($transactions_outgoing) != 0) { ?>
		<legend></legend>
		<address>
  			<strong>Outgoing Transfers</strong><br>
		</address>

			
		<table class="table table-hover">
			<tr>
				<th>Time of Transaction</th>
				<th>Source Account</th>
				<th>Source Account Holder </th>
				<th>Target Account</th>
				<th>Target Account Holder </th>
				<th>Transfer Amount (€)</th>
				<th>Description</th>
				<th>Status</th>
				
			</tr>

			<?php 


			foreach ($transactions_outgoing as $t) { 

				$sourceAccountName = $user->firstName . " " . $user->lastName;
				$targetAccountHolder = $bAccountModel->getAccountOwnerByAccountNumber($t->toAccount);
				$targetAccountName = $targetAccountHolder->firstName . " " . $targetAccountHolder->lastName;

				?>



			<tr>
				<td><?php echo htmlspecialchars($t->createDate, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($t->fromAccount, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($sourceAccountName, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($t->toAccount, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($targetAccountName, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($t->transferAmount, ENT_QUOTES, 'UTF-8'); ?> </td>
				<td><?php echo htmlspecialchars($t->description, ENT_QUOTES, 'UTF-8'); ?> </td>
				<td>
				<?php 

				if($t->status == 1) {
					echo "Pending";
				}
				else {
					if($t->status == 2) {
						echo "Approved";
					}
					else {
						echo "Denied";
					}
				}


				?></td>






			</tr>

			<?php } ?>



		</table>

		

		<?php  } ?>

		<?php if(count($transactions_ingoing) != 0) { ?>
		<legend></legend>
		<address>
  			<strong>Ingoing Transfers</strong><br>
		</address>

			
		<table class="table table-hover">
			<tr>
				<th>Time of Transaction</th>
				<th>Source Account</th>
				<th>Source Account Holder </th>
				<th>Target Account</th>
				<th>Target Account Holder </th>
				<th>Transfer Amount (€)</th>
				<th>Description</th>
				<th>Status</th>
				
			</tr>

			<?php 


			foreach ($transactions_ingoing as $t) { 

				$targetAccountName = $user->firstName . " " . $user->lastName;
				$sourceAccountHolder = $bAccountModel->getAccountOwnerByAccountNumber($t->fromAccount);
				$sourceAccountName = $sourceAccountHolder->firstName . " " . $sourceAccountHolder->lastName;


				?>

			<tr>
				<td><?php echo htmlspecialchars($t->createDate, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($t->fromAccount, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($sourceAccountName, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($t->toAccount, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($targetAccountName, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars($t->transferAmount, ENT_QUOTES, 'UTF-8'); ?> </td>
				<td><?php echo htmlspecialchars($t->description, ENT_QUOTES, 'UTF-8'); ?> </td>
				<td>
				<?php 

				if($t->status == 1) {
					echo "Pending";
				}
				else {
					if($t->status == 2) {
						echo "Approved";
					}
					else {
						echo "Denied";
					}
				}


				?></td>





			</tr>

			<?php } ?>



		</table>

		<?php } ?>

  </div>
</div>
