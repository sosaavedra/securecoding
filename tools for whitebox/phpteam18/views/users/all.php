
 <?php //generate token

          $tokenModel = $this->loadModel ( 'token' );
          $token = Token::generateHashWithSalt($connectedUser->email);
          $_SESSION["csrf_token"] = $token;
          ?>

<div class="jumbotron">

<ol class="breadcrumb">
	<?php if($connectedUser->userprivilege == 2) { ?>
  <li><a href="/employees/index"><?php  $name = $connectedUser->firstName . " " .  $connectedUser->lastName;echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
  <?php } else {
				if($connectedUser->userprivilege == 1) {
	  ?>
	  <li><a href="/admin/index"><?php  $name = $connectedUser->firstName . " " .  $connectedUser->lastName;echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></a></li>
	  <?php }} ?>
	  
	<li class="active">Clients and Employees</li>
</ol>

    <div class="container">
    <h1 class="text-center">Clients & Employees</h1>
    <br>
    <br>

    <?php if(count($users) == 0) {?>

      <h2>There are neither employees nor clients currently on the system.</h2>


      <?php }
      else {
        ?>
      
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th></th>
                </tr>
            </thead>
	       <tbody>




                <?php foreach ($users as $user) { ?>
                
                <tr>
                    <td onclick="window.location.href = '<?php echo URL?>users/profile/<?php echo $user->userId; ?>';" style="cursor:pointer;"><?php echo  htmlspecialchars($user->firstName, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td onclick="window.location.href = '<?php echo URL?>users/profile/<?php echo $user->userId; ?>';" style="cursor:pointer;"><?php echo  htmlspecialchars($user->lastName, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td onclick="window.location.href = '<?php echo URL?>users/profile/<?php echo $user->userId; ?>';" style="cursor:pointer;"><?php echo  htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td onclick="window.location.href = '<?php echo URL?>users/profile/<?php echo $user->userId; ?>';" style="cursor:pointer;"><?php
                    if($user->userprivilege == 2)
                        echo "Employee";
                    if($user->userprivilege == 3)
                        echo "Client";
                      ?></td>
                      <td>

                      <?php if($user->active == 1) {?>
                             <button type="button" 
                          class="btn btn-danger btn-sm"
                            onclick="location.href='<?php echo URL;?>users/disapprove/<?php echo $user->userId . '?token='. $token;?>'">Disapprove</button>
                        <?php }
                        else { ?>
                             <button type="button" 
                          class="btn btn-warning btn-sm"
                            onclick="location.href='<?php echo URL;?>users/approve/<?php echo $user->userId . '?token='. $token;?>'">Approve</button>
                       <?php  }
                        ?>                         
                      </td>
                      
                </tr>
                </a>
                <?php } ?>
            </tbody>
        </table>

        <?php }?>

    </div>
</div>
