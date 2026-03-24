<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MODEL__category;
use App\Models\MODEL__xml;
use App\Models\MODEL__account;
use App\Models\MODEL__cenovaLista;

class Xml extends BaseController {

    public function __construct() {
        parent::__construct();

        $this -> cartSession     = $this -> getSessionProperty();
        $this -> userModel       = new UserModel();
        $this -> MODEL__account  = new MODEL__account();
        $this -> MODEL__category = new MODEL__category();
        $this -> MODEL__xml      = new MODEL__xml();
    }

    public function index() {

        if (empty(session() -> has('user_id'))) {
            return redirect() -> to(route_to('Account-index'));
        }

        $user_id      = session('user_id');
        $user         = $this -> userModel -> find($user_id);
        $customerData = $this -> MODEL__account -> get__klient($user_id);

        $data = [
            'title'        => '',
            'addGlobalJS'  => $this -> addGlobalJS(),
            'addJS'        => $this -> addJS(),
            'user'         => $user,
            'customerData' => $customerData,
            // views
            'views'        => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return view("{$this -> theme}/xml/VIEW__xml", $data);
    }

    public function autocomplete($arg = '') {

        $filter_name = $this -> request -> getVar('filter_name');

        $response = $this -> MODEL__xml -> autocomplete($arg, $filter_name);
        //dd($response);
        return json_encode($response);
    }

    public function xmlExport() {
        $form              = $this -> request -> getVar('form');
        $productPriceLevel = $this -> getResolvedProductPriceLevel();
        $user_id           = session('user_id');
        $user              = $this -> userModel -> find($user_id);

        $this -> MODEL__xml -> export($form, $user, $productPriceLevel);
    }

    public function csvExport() {
        $form              = $this -> request -> getVar('form');
        $productPriceLevel = $this -> getResolvedProductPriceLevel();
        $user_id           = session('user_id');
        $user              = $this -> userModel -> find($user_id);

        $this -> MODEL__xml -> csv_export($form, $user, $productPriceLevel);
    }

    public function addJS() {
        $theme = 'js/theme/electro';

        $plugins = ["$theme/loadPlugins"];

        $global = ["$theme/global"];

        $default = ["$theme/pages/xml/xml"];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);
    }
}
