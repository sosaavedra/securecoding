<div class="jumbotron">

	<ol class="breadcrumb">
      <li><a href="/users/index"><?php $name = $connectedUser->firstName . " " .  $connectedUser->lastName; echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');?></a></li>
      <li class="active">Make a Transfer</li>
    </ol>
	
      <div class="container">
        <div>
          <h2>Make a Transfer</h2>
          <?php //generate token

          $tokenModel = $this->loadModel ( 'token' );
          $token = Token::generateHashWithSalt($connectedUser->email);
          $_SESSION["csrf_token"] = $token;
          ?>
          <br>
          <br>
          <div class="col-md-5">
          <h4>Please enter TAN Number <?php if (isset($_SESSION["tan"])) echo "'<strong>". $_SESSION["tan"] . "</strong>'"?></h4>
          <?php if($_POST != NULL) { ?>
            <form action="<?php echo URL . 'users/submitTransaction'?>" method='POST'>
              <br>

              <?php if($error == 3){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="tan">Please enter a TAN number</label>
              <?php  }?>
              <input type='text' name='tan' id='tan' placeholder='TAN' class='form-control' />
              <?php if($error == 3){?> </div> <?php }?>

              <br>

              <?php if($error == 2){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="amount">Please enter an amount of money to transfer</label>
              <?php  }?>
              <input type='number' name='amount' id='amount' placeholder='Amount to transfer' class='form-control' />
              <?php if($error == 2){?> </div> <?php }?>

              <br>

              <?php if($error == 3){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="accountNumber">Please enter an account number to send the money to</label>
              <?php  }?>
              <input type='text' name='accountNumber' id='accountNumber' placeholder='Target account number' class='form-control' />
              <?php if($error == 3){?> </div> <?php }?>

              <textarea type='text' name='description' id='description' placeholder='Description' class='form-control' style="resize:vertical;"></textarea>
              <br>
              <input type="hidden" name="token" id="token" class="form-control" value="<?php echo $token;?>"/>
              <button type='submit' class='btn btn-success'>Submit</button>




            	
            </form>
            <? } else {
              ?>

               <form action="<?php echo URL . 'users/submitTransaction'?>" method='POST'>
                <input type='text' name='tan' id='tan' placeholder='TAN' class='form-control' />
                <br>
                <input type='text' name='accountNumber' id='accountNumber' placeholder='Target account number' class='form-control' />
                <br>
                <input type='number' name='amount' id='amount' placeholder='Amount to transfer' class='form-control' />
                <br>
                <textarea type='text' name='description' id='description' placeholder='Description' class='form-control' style="resize:vertical;"></textarea>
                <br>
                <input type="hidden" name="token" id="token" class="form-control" value="<?php echo $token;?>"/>
                <button type='submit' class='btn btn-success'>Submit</button>
              </form>


              <?php }?>
          </div>

          <div class="col-md-1"><h3>OR</h3></div>

          <div class="col-md-5">
             <div class="col-md-12">
                <h4>Upload a <strong>Batch</strong> file</h4>
                <br>
                <strong>Please follow the following format</strong>
                <mark>
                <br/>[TAN number]
                <br/>[Account] [Amount]
                <br/>[Account] [Amount]
                <br/>.
                <br/>.
                <br>etc
                <br>
                <br>
                </mark>
            </div>
            <br>
            <br>
            <form enctype="multipart/form-data" action="<?php echo URL . 'users/uploadTransactionFile'?>" method='POST'>
              <div class="col-md-9">
                <input type='file' name='file' id='file' placeholder='File' class='form-control' />
                <input type="hidden" name="token" id="token" class="form-control" value="<?php echo $token;?>"/>
             
              </div>
               <div class="col-md-3">
                <button type='submit' class='btn btn-success'>upload</button>
              </div>
            </form>

           
          </div>


           
        </div>
    </div>
<br>
<br>
<br>
<br>
