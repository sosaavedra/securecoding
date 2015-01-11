

    <div class="jumbotron">
      <div class="container">
        <h2>Employee Registration</h2>
        <div class="col-md-4"> </div>
        <div class="col-md-4">

        <?php if($_POST != NULL) { ?>
          <form action="<?php echo URL . 'employees/register'?>" method='POST'>


            <?php if($error == 1){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="firstName">Please enter your first name</label>
             <?php  }?>
              <input type='text' value="<?php echo $_POST["firstName"]; ?>" name='firstName' id='firstName' placeholder='First Name' class='form-control' required/>
            <?php if($error == 1){?> </div> <?php }?>


            <br>


            <?php if($error == 2){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="lastName">Please enter your last name</label>
            <?php  }?>
            <input type='text' value="<?php echo $_POST["lastName"]; ?>" name='lastName' id='lastName' placeholder='Last Name' class='form-control' required/>
            <?php if($error == 2){?> </div> <?php }?>


            <br>

           
            <?php if($error == 3){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="email">Please enter your e-mail address</label>
            <?php  }?>
            <?php if($error == 6){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="email">Please enter a valid e-mail address</label>
            <?php  }?>
            <?php if($error == 9){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="email">You're e-mail already exists on our system!</label>
            <?php  }?>
            <input type='email' value="<?php echo $_POST["email"]; ?>" name='email' id='email' placeholder='Email' class='form-control' autocomplete='off' required/>
            <?php if($error == 3 || $error == 6 || $error == 9){?> </div> <?php }?>
            
            <br>


            <?php if($error == 4){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="password">Please enter your password</label>
            <?php  }?>
            <?php if($error == 7){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="password">
                Please make sure your password<br>
                * contains at least (1) upper case letter<br>
                * contains at least (1) lower case letter<br>
                * contains at least (1) number<br>
                * contains at least (1) special character from these <br>!@#$%^&amp;*()\-_=+{};:,&lt;.><br>
                * is at least (8) characters in length<br>
                </label>
            <?php  }?>
            <input type='password' value="<?php echo $_POST["password"]; ?>" name='password' id='password' placeholder='Password' class='form-control' required/>
            <?php if($error == 4 || $error == 7){?> </div> <?php }?>


            <br>


            <?php if($error == 5){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="confirmPassword">Please confirm your password by re-entering the same password again</label>
            <?php  }?>
            <input type='password' value="<?php echo $_POST["confirmPassword"]; ?>" name='confirmPassword' id='confirmPassword' placeholder='Confirm Password' class='form-control' required/>
            <?php if($error == 5){?> </div> <?php }?>

            
            <br>



            <?php if($error == 8){?>
              <div class="form-group has-error has-feedback">
                <label class="control-label" for="confirmPassword">Please enter a valid cell number</label>
            <?php  }?>
            <input type='tel' name='mobile' value="<?php echo $_POST["mobile"]; ?>" id='mobile' placeholder='Mobile' class='form-control' autocomplete='off'/>
            <?php if($error == 8){?> </div> <?php }?>

            
            <br/>

            
            <button type='submit' class='btn btn-success center-block'>Register</button>
          </form>
          <?php } else { ?>
           <form action="<?php echo URL . 'employees/register'?>" method='POST'>
            <input type='text' name='firstName' id='firstName' placeholder='First Name' class='form-control' required/>
            <br>
            <input type='text' name='lastName' id='lastName' placeholder='Last Name' class='form-control' required/>
            <br>
            <input type='email' name='email' id='email' placeholder='Email' class='form-control' autocomplete='off' required/>
            <br>
            <input type='password' name='password' id='password' placeholder='Password' class='form-control' required/>
            <br>
            <input type='password' name='confirmPassword' id='confirmPassword' placeholder='Confirm Password' class='form-control' required/>
            <br>
            <input type='tel' name='mobile' id='mobile' placeholder='Mobile' class='form-control' autocomplete='off'/>
            <br/>
            <button type='submit' class='btn btn-success center-block'>Register</button>
          </form>


          <?php }?>
        </div>
        <div class="col-md-4"> </div>
      </div>
    </div>
      <hr>
    </div>
