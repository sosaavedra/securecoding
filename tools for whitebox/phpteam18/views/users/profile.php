
 <?php //generate token

          $tokenModel = $this->loadModel ( 'token' );
          $token = Token::generateHashWithSalt($connectedUser->email);
          $_SESSION["csrf_token"] = $token;
          ?>
          
<div class="jumbotron" >

<ol class="breadcrumb">
  <li><a href="/"><?php $name = $connectedUser->firstName . " " .  $connectedUser->lastName; echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
  <li><a href="/users/all">Clients and Employees</a></li>
  <li class="active"><?php $name = $user->firstName . " " .  $user->lastName; echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');?></li>
</ol>



  <div class="container">
	   <?php if(isset($error_message)) {?>
			<script type="text/javascript">alert('<?php echo $error_message; ?>')</script>
			<?php }?>
    <h1 class="text-center"><?php echo htmlspecialchars($user->firstName . " " . $user->lastName, ENT_QUOTES, 'UTF-8');?></h1>
    <h5 class="text-center">
    <?php 
    if($connectedUser->userprivilege < 3) {
    	if($user->userprivilege == 3) { 
    		$bAccountModel = new BankAccount($this->db);
    		$bankAc = $bAccountModel->getBankAccountByAccountHolderID($user->userId);
    }?>
    <?php 
    	if($user->userprivilege == 2)
            echo "Employee";
        if($user->userprivilege == 3)
            echo "Client";
    ?>
	</h5>



	


	<?php if($user->active == 1) {?>
            <button type="button" class="center-block btn btn-danger btn-sm" 
                     onclick="location.href='<?php echo URL;?>users/disapprove/<?php echo $user->userId  . '?token='. $token;?>'">Disapprove</button>
                     &nbsp;
            <?php if($user->userprivilege == 3)
            {?>
            <form action="<?php echo URL.'users/loadAmount'?>" method="POST">
				<input type="number" name="amount" id="amount" class='form-control' />
				<input type="hidden" name="userId" id="userId" value="<?php echo $user->userId; ?>" class='form-control' />
				<button type='submit' class='btn btn-success'>Load Amount</button>
            </form>
            <?php
			}
            ?>
                        <?php }
              			else { ?>
            <button type="button" class="center-block btn btn-warning btn-sm "
                    onclick="location.href='<?php echo URL;?>users/approve/<?php echo $user->userId  . '?token='. $token;?>'">Approve</button>
                       <?php  } ?>


	<address>
		<strong><?php echo htmlspecialchars($user->firstName . " " . $user->lastName, ENT_QUOTES, 'UTF-8');?></strong><br>
  		<a href="mailto:<?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8');?>"><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8');?></a><br>
  		<abbr title="Phone">P:</abbr> <?php echo htmlspecialchars($user->mobile, ENT_QUOTES, 'UTF-8'); ?>
  	</address>

  	<?php if($user->userprivilege == 3) { ?>
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

	<?php } ?>




    <?php if($user->userprivilege == 3) { ?>
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

				<td>
					<?php if($t->status == 1) {?>
                             <button type="button" 
                          class="btn btn-success btn-sm"
                            onclick="location.href='<?php echo URL;?>transactions/approve/<?php echo $t->requestId;?>/<?php echo $user->userId . '?token='. $token;?>'">Approve</button>
                        <?php }
                        else { ?>
                             <button type="button" 
                          class="btn btn-success btn-sm" disabled="disabled"
                            onclick="location.href='<?php echo URL;?>transactions/approve/<?php echo $t->requestId;?>/<?php echo $user->userId . '?token='. $token;?>'">Approve</button>
                       <?php  }
                        ?>
				</td>

				<td>
					<?php if($t->status == 1) {?>
                             <button type="button" 
                          class="btn btn-danger btn-sm"
                            onclick="location.href='<?php echo URL;?>transactions/disapprove/<?php echo $t->requestId;?>/<?php echo $user->userId . '?token='. $token;?>'">Disapprove</button>
                        <?php }
                        else { ?>
                             <button type="button" 
                          class="btn btn-danger btn-sm" disabled="disabled"
                            onclick="location.href='<?php echo URL;?>transactions/disapprove/<?php echo $t->requestId;?>/<?php echo $user->userId . '?token='. $token;?>'">Disapprove</button>
                       <?php  }
                        ?>
				</td>





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

				<td>
					<?php if($t->status == 1) {?>
                             <button type="button" 
                          class="btn btn-success btn-sm"
                            onclick="location.href='<?php echo URL;?>transactions/approve/<?php echo $t->requestId . '?token='. $token;?>/<?php echo $user->userId;?>'">Approve</button>
                        <?php }
                        else { ?>
                             <button type="button" 
                          class="btn btn-success btn-sm" disabled="disabled"
                            onclick="location.href='<?php echo URL;?>transactions/approve/<?php echo $t->requestId . '?token='. $token;?>/<?php echo $user->userId;?>'">Approve</button>
                       <?php  }
                        ?>
				</td>

				<td>
					<?php if($t->status == 1) {?>
                             <button type="button" 
                          class="btn btn-danger btn-sm"
                            onclick="location.href='<?php echo URL;?>transactions/disapprove/<?php echo $t->requestId . '?token='. $token;?>/<?php echo $user->userId;?>'">Disapprove</button>
                        <?php }
                        else { ?>
                             <button type="button" 
                          class="btn btn-danger btn-sm" disabled="disabled"
                            onclick="location.href='<?php echo URL;?>transactions/disapprove/<?php echo $t->requestId . '?token='. $token;?>/<?php echo $user->userId;?>'">Disapprove</button>
                       <?php  }
                        ?>
				</td>





			</tr>

			<?php } ?>



		</table>

		<?php  } } }?>
  </div>
</div>
