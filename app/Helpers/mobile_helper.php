<?php

// app/Helpers/mobile_helper.php

if (!function_exists('isMobile')) {

    function isMobile() {
        $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Check for mobile devices based on the user-agent or other logic
        $isMobile = preg_match('/(iPhone|iPad|iPod|Android|BlackBerry)/i', $useragent);

        // Дефиниране на константата, ако не е дефинирана
        if (!defined('ISMOBILE')) {
            return define('ISMOBILE', $isMobile);
        }
    }

}