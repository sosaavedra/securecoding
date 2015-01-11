<?php
$use_sts = true;
    if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        header('Strict-Transport-Security: max-age=31536000');
    } elseif ($use_sts) {
        header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
        die();
        return;
    }
    
require 'config/config.php';
require 'libs/application.php';
require 'libs/controller.php';

$app = new Application();
