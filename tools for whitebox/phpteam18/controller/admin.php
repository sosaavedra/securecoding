<?php 

class Admin extends Controller {

	function __construct () {
      parent::__construct();
      $this->model = $this->loadModel('user');
  	}

  	function index() {
  		$connectedUser = Controller::getConnectedUser($this->db);
  		if (isset($connectedUser)) {
  			if ($connectedUser->userprivilege == 1) {

        		require 'views/head.php';
        		require 'views/navigation.php';
        		require 'views/admin/index.php';
        		require 'views/footer.php';
  				return;	
  			}
  		}
  		header('location:' . URL . 'users/show');
  	}
	
}