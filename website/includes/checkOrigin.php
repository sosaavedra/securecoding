<?php 
        $isHttps = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off';
        if ($isHttps)
        {
                header('Strict-Transport-Security: max-age=31536000'); // FF 4 Chrome 4.0.211 Opera 12
        }

        header('X-Frame-Options: SAMEORIGIN');
?>