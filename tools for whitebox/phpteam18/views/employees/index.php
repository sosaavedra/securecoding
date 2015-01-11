<div class="jumbotron" >
	<ol class="breadcrumb">
		<li class="active"><?php $name = $user->firstName . " " .  $user->lastName;echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');?></li>
	</ol>
  <div class="container">
        <h5 class="text-center">
			<a  href='<?php echo URL?>users/all'>List Users</a>
        </h5>
  </div>
</div>
