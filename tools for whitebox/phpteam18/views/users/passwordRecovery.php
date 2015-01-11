<div class="jumbotron">
      <div class="container">
        <h1>Password Recover</h1>
        <div>
          <form action="<?php echo URL . 'users/recoverPassword'?>" method='POST'>
            <input type='email' name='email' id='email' placeholder='Email' class='form-control' autocomplete='off'/>
            <br/>
            <button type='submit' class='btn btn-success'>Recover password</button>
          </form>
        </div>
      </div>
    </div>
      <hr>
    </div>