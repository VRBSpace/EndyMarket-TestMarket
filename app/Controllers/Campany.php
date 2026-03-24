<?php

namespace App\Controllers;

use \App\Models\MODELpop__klient;
use \App\Models\MODEL__global;

class POPup_klient extends \App\Controllers\BaseController {

    public function __construct() {

        parent::__construct();

        $this -> MODEL__global    = new MODEL__global();
        $this -> MODELpop__klient = new MODELpop__klient();
    }

    // отваряне на модал ф категории в модал ф. продукт
    // ======================================================================
    public function open($id) {// ajax
        $data = [
            // 'addCSS'       => $this -> addCSS(),
            'addJS' => $this -> addJS(),
            'list'  => $this -> MODELpop__klient -> get__klient($id),
                //'country_list' => $this -> MODEL__global -> get__country()
        ];

        return json_encode(view("{$this -> theme}/modalForm/spisak/VIEWpop__klient", $data), JSON_UNESCAPED_UNICODE);
    }

    public function save() {// ajax
        $form                        = $this -> request -> getVar('form-klient');
        $form['klientData_json'] = json_encode($this -> request -> getVar('form-json'), JSON_UNESCAPED_UNICODE);

        $response = $this -> MODELpop__klient -> save_klient($form);

        if ($response == 1) {
            $this -> returnResponse(true, $form['klient_name'], lang('popup/LANGpop__klient.returnResponse.ok.newKlient'), 1);
        } elseif ($response == 2) {
            $this -> returnResponse(true, $form['klient_name'], lang('popup/LANGpop__klient.returnResponse.ok.editKlient'), 1);
        }

        return redirect() -> back();
    }

    public function delete($id = null) {// ajax
        $this -> MODELpop__klient -> remove($id);

        return json_encode('');
    }

    public function addCSS() {
        return [
            ''
        ];
    }

    // зареждане на js файлове в footer на html страноцата
    // ====================================================
    public function addJS() {

        $global = [
            'js/_Global/ajax_config',
        ];

        $plugins = [];

        $default = ['js/popupForm/popup_klient'];
        $modals  = [];

        return array_merge($plugins, $global, $default, $modals);
    }

}

//return $this -> response -> setJSON(json_encode($data['selectOptions'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
