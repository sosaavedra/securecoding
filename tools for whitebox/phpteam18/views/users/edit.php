
    <div class="jumbotron">

    <ol class="breadcrumb">
      <li><a href="/users/index"><?php $name = $user->firstName . " " .  $user->lastName;echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');?></a></li>
      <li class="active">Edit Profile Information</li>
    </ol>
      <div class="container">
        <h1>Edit Profile Information</h1>
        <div>
          <form action="<?php echo URL . 'users/save'?>" method='POST'>
            <input type='text' name='firstName' id='firstName' placeholder='First Name' class='form-control' value='<?php echo  htmlspecialchars($user->firstName, ENT_QUOTES, 'UTF-8');?>'/>
            <input type='text' name='lastName' id='lastName' placeholder='Last Name' class='form-control' value='<?php echo  htmlspecialchars($user->lastName, ENT_QUOTES, 'UTF-8');?>'/>
            <input type='email' name='email' id='email' placeholder='Email' class='form-control' autocomplete='off' disabled value='<?php echo  htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8');?>'/>
            <input type='password' name='password' id='password' placeholder='Password' class='form-control'/>
            <input type='tel' name='mobile' id='mobile' placeholder='Mobile' class='form-control' autocomplete='off' value='<?php echo  htmlspecialchars($user->mobile, ENT_QUOTES, 'UTF-8');?>'/>
            <br/>
            <button type='submit' class='btn btn-success'>Save</button>
          </form>
        </div>
      </div>
    </div>
      <hr>
    </div>
