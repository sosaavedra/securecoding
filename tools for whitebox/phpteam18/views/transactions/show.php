<!DOCTYPE html>
<html lang="en">
  <?php include('head.php');?>
  <body>
  <?php include('navigation.php');?>
    <div class="jumbotron">
      <div class="container">
        <h1>Create Transaction</h1>
        <div>
          <form >
            <input type='text' name='firstName' id='firstName' placeholder='First Name' class='form-control'/>
            <input type='text' name='lastName' id='lastName' placeholder='Last Name' class='form-control'/>
            <input type='email' name='email' id='email' placeholder='Email' class='form-control' autocomplete='off'/>
            <input type='password' name='password' id='password' placeholder='Password' class='form-control'/>
            <input type='password' name='confirmPassword' id='confirmPassword' placeholder='Confirm Password' class='form-control'/>
            <input type='tel' name='mobile' id='mobile' placeholder='Mobile' class='form-control' autocomplete='off'/>
            <br/>
            <button type='submit' class='btn btn-success'>Transfer</button>
          </form>
        </div>
      </div>
    </div>
      <hr>
    <?php include('footer.php');?>
    </div>
  </body>
</html>