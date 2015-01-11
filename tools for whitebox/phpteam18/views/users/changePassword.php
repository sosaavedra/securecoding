 <?php //generate token

          $tokenModel = $this->loadModel ( 'token' );
          $token = Token::generateHashWithSalt($connectedUser->email);
          $_SESSION["csrf_token"] = $token;
?>

<div class="jumbotron">
      <div class="container">
        <h1>Password Recover</h1>
        <div>
          <form action="<?php echo URL . 'users/resetPassword' ?>" method='POST'>
             <input type="hidden" name="token" id="token" class="form-control" value="<?php echo $token;?>"/>
             
            <input type='password' name='password' id='password' placeholder='Password' class='form-control' autocomplete='off'/>
            <br/>
            <input type='password' name='confirmPassword' id='confirmPassword' placeholder='Confirm Password' class='form-control' autocomplete='off'/>
            <br/>
            <button type='submit' class='btn btn-success'>Change</button>
          </form>
        </div>
      </div>
    </div>
      <hr>
    </div>