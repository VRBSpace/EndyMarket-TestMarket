<?php

if (!function_exists('setingGlob')) {

    function setingGlob($key = null, $value = null) {

        $setting = service('settings');

        if (empty($key)) {
            return $setting;
        }

        // Getting the value?
        if (count(func_get_args()) === 1) {
            return json_decode($setting -> get($key)[0], true);
        }

        // Setting the value
        // $setting->set($key, $value);
    }

}
