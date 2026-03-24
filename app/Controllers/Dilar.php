<?php

namespace App\Controllers;

use \App\Controllers\ApiQurier;
use App\Models\UserModel;
use App\Models\MODEL__category;
use App\Models\MODEL__account;
use App\Models\MODEL__cenovaLista;

class Dilar extends BaseController {

    public function __construct() {
        parent::__construct();

        if (session() -> has('user_id')) {
            header("Location:" . base_url());
            exit();
        }

        $this -> cartSession    = $this -> getSessionProperty();
        $this -> ApiQurier      = new ApiQurier();
        $this -> userModel      = new UserModel();
        $this -> MODEL__account = new MODEL__account();
    }

    public function index() {

        $categoryModel = new MODEL__category();
        $categories    = $categoryModel -> getCategories();
        $category_tree = $this -> buildCategoryTree(null, $categories);

        $user_id      = session('user_id');
        $user         = $this -> userModel -> find($user_id);
        $customerData = $this -> MODEL__account -> get__klient($user_id);

        $cenovaListaModel = new MODEL__cenovaLista();
        $allCenovaLista   = $cenovaListaModel -> getAllCenovaLista();

        $data = [
            'title'                   => '',
            'addGlobalJS'             => $this -> addGlobalJS(),
            'addJS'                   => $this -> addJS(),
            'user'                    => $user,
            'customerData'            => $customerData,
            'allCenovaLista'          => $allCenovaLista,
            'categories'              => $category_tree,
            'cartSession'             => $this -> cartSession,
            'cartSessionProductsList' => $this -> cartSession['products'],
            // views
            'views'                   => $this -> get_views(),
        ];

        return view("{$this -> theme}/dilar/VIEW__dilar", $data);
    }

    // изпращане на формата заявка за дилър
    public function sendForm() {
        $form = $this -> request -> getPost();

        if (empty($form)) {
            return false;
        }
        $br = '<br>';

        $message = 'Фирмени данни' . $br .
                'Фирма: ' . $form['company'] . $br .
                'Мол: ' . $form['mol'] . $br .
                'Булстат: ' . $form['bulstat'] . $br .
                'Град: ' . $form['city'] . $br .
                'Адрес по регистрация: ' . $form['address'] . $br .
                //'Пощенски код: ' . $form['company'] . $br .
                'Tелефон: ' . $form['phone'] . $br .
                'Email: ' . $form['email'] . $br . $br .
                'Съгласил се с декларацията за поверителност:  (да)' . $br;

        $sendEmail = $this -> sendEmail(null, 'Заявка за дилър', $message, true);

        $response = [
            'success'    => true,
            'message'    => "Вашата заявка за дилър е приета.",
            'errMessage' => empty($sendEmail) ? "<br><br>Имейла не беше изпратен. Възможни причини са липсва системен дилърски email или не е валиден." : ''
        ];

        return $this -> response -> setJSON($response);
    }

    public function addJS() {
        $theme = 'js/theme/electro';

        // JS Implementing Plugins 
        $plugins = ["$theme/loadPlugins"];

        $global = ["$theme/global"];

        $default = ["$theme/pages/dilar/dilarForm"];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);
    }
}
