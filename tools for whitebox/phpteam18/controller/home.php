<?php

date_default_timezone_set('Europe/Berlin');

class Home extends Controller {
    public function index($message = null, $isError = null) {
		require 'views/head.php';
        require 'views/navigation.php';
        require 'views/home/index.php';
        require 'views/footer.php';
    }
}
