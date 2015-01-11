<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo URL;?>">Free Gold Bank - FGB</a>
        </div>
        <div class="navbar-collapse collapse">
      <?php 
          //if (session_status() == PHP_SESSION_NONE) {

            session_start();
          //}
            if (isset($_SESSION['userId'])) { ?>
            
              <form class='navbar-form navbar-right'>
              <div class='form-group'><a href='<?php echo URL;?>users/logout' class='btn btn-success button-link' style='float:right;'>Logout</a>
              </div>
              </form>
              <div class='navbar-form navbar-right' style='color:white;'>
				  
			<?php
			
				$userModel = new User($this->db);
				$loggedInUser = $userModel->getUserById($_SESSION['userId']);
				
				if($loggedInUser->userprivilege == 1) { 
					?>
					<a href='<?php echo URL;?>admin/index' style="text-decoration:none;color:#f0ad4e">
					<?php echo htmlspecialchars($loggedInUser->firstName ." " . $loggedInUser->lastName, ENT_QUOTES, 'UTF-8'); ?>
					</a>
					
					<?php

				} else {

					if($loggedInUser->userprivilege == 2) { ?>
					
						<a href='<?php echo URL;?>employees/index' style="text-decoration:none;color:#f0ad4e">
						<?php echo htmlspecialchars($loggedInUser->firstName ." " . $loggedInUser->lastName, ENT_QUOTES, 'UTF-8');?>
						</a>
					<?php
					}
					else {
						?>
						
						<a href='<?php echo URL;?>users/index' style="text-decoration:none;color:#f0ad4e">
						<?php echo htmlspecialchars($loggedInUser->firstName ." " . $loggedInUser->lastName, ENT_QUOTES, 'UTF-8');?>
						</a>
						<?php
					}
				}
              ?>
              
              
              
              </div>
      <?php } else { ?>
              <form class='navbar-form navbar-right' role='form' role='form' action='<?php echo URL;?>users/login' method='POST'>
              <div class='form-group'>
              <input type='email' placeholder='Email' class='form-control' id='email' name='email' required>
              </div>
              <div class='form-group'>
              <input type='password' placeholder='Password' class='form-control' id='password' name='password' required>
              </div>
              <button type='submit' class='btn btn-success'>Sign in</button>
              <a href='<?php echo URL;?>users/create' class='btn btn-success button-link'>Register</a>
              <a href='<?php echo URL;?>users/passwordRecovery' style="color:#f0ad4e" class=''>forgot password?</a>
              </form>
            <?php } ?>
        </div>
      </div>
    </div>
    <?php 

    if (isset($_SESSION['message'])) {?>
      <div class="alert alert-info" role="alert" style='margin-top: 51px;margin-bottom: 0px;'>
        <strong>Message: </strong>
        <?php echo $_SESSION['message']; unset($_SESSION['message']);?>
      </div>
    <?php }?>

    <?php 

    if (isset($message)){
      if(isset($isError) && $isError!=NULL)
      {
        if($isError)
        {?>
          <div class="alert alert-info" role="alert" style='margin-top: 51px;margin-bottom: 0px;background-color:#F2DEDE'>
            <strong><?php echo $message;?></strong>
          </div>
          <?php
        }
      }
      else{?>
        <div class="alert alert-info" role="alert" style='margin-top: 51px;margin-bottom: 0px;background-color:#DFF0D8'>
            <strong><?php echo $message;?></strong>
          </div>
      <?php
      }
    }
  ?>