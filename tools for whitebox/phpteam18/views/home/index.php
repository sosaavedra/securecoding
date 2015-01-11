
    <div class="jumbotron" style="background-image:url('http://cdn-2.thejameslist.com/data/images/14368900_large3.jpg');background-repeat: no-repeat;
background-position: center; background-size: cover;">
      <div class="container">
        <h1 style="color: #FFF;">Welcome to FGB</h1>
      </div>
    </div>
    <div class="container">
      <div class="row">
		  <?php if (!isset($_SESSION['userId'])) { ?>
		  
		   <div class="col-md-4">
          <h2>Careers</h2>
          <p>Join our Staff as an employee</p>
          <p><a class="btn btn-default" href="<?php echo URL;?>employees/create" role="button">Join us &raquo;</a></p>
       </div>
       <!--
        <div class="col-md-4">
          <h2>Stock market</h2>
          <p>Buy one get one free only @ our bank :D</p>
        </div>-->
       
        <?php } ?>
      </div>

