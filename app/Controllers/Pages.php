<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MODEL__category;
use App\Models\MODEL__xml;
use App\Models\MODEL__account;
use App\Models\MODEL__setings;

class Pages extends BaseController {

    public function __construct() {
        parent::__construct();

        $this -> cartSession     = $this -> getSessionProperty();
        $this -> userModel       = new UserModel();
        $this -> MODEL__account  = new MODEL__account();
        $this -> MODEL__setings  = new MODEL__setings();
        $this -> MODEL__category = new MODEL__category();
        $this -> MODEL__xml      = new MODEL__xml();
    }

    public function index($page = '') {

        $data = [
            'title'       => '',
            'addGlobalJS' => $this -> addGlobalJS(),
            'addJS'       => $this -> addJS(),
            'addCSS'      => $this -> addCSS(),
            // views
            'views'       => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return view("{$this -> theme}/pages/VIEW__" . $page, $data);
    }

    public function addCSS() {
        $page   = 'css/pages/';
        $vendor = 'vendor';

        return [
            "$page/global"
        ];
    }

    public function addJS() {
        $theme  = 'js/theme/electro';
        $vendor = 'vendor';

        // JS Implementing Plugins 

        $plugins = [
            "$theme/loadPlugins"
        ];

        $global = ["$theme/global"];

        $default = [];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);
    }
}
