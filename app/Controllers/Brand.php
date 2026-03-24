<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MODEL__brand;

class Brand extends BaseController {

    public function __construct() {
        parent::__construct();

        $this -> MODEL__brand = new MODEL__brand();
    }

    public function index() {

        $brands = $this -> MODEL__brand -> get__brands();

        $data = [
            'title'       => 'Valpers',
            'addGlobalJS' => $this -> addGlobalJS(),
            'addJS'       => $this -> addJS(),
            'addCSS'      => $this -> addCSS(),
            'brands'      => $brands,
            // views
            'views'       => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return view("{$this -> theme}/shop/VIEW__brand", $data);
    }

    // зареждане на css файлове в footer на html страноцата
    // ====================================================
    public function addCSS() {
        $page = 'css/pages/';

        return [
            "$page/global",
            "$page/home",
        ];
    }

    // зареждане на js файлове в footer на html страноцата
    // ====================================================
    public function addJS($isList = null) {
        $theme = 'js/theme/electro';

        // JS Implementing Plugins 
        $plugins = ["$theme/loadPlugins"];

        $global = ["$theme/global"];

        $default = ["$theme/pages/cart/cart"];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);
    }
}
