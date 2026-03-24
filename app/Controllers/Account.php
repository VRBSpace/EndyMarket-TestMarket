<?php

namespace App\Controllers;

use \App\Controllers\ApiQurier;
use App\Models\UserModel;
use App\Models\MODEL__account;

class Account extends BaseController {

    private const PASSWORD_POLICY_MESSAGE = 'Паролата трябва да е минимум 8 символа и да съдържа малка буква, главна буква, цифра и специален символ.';

    public function __construct() {
        parent::__construct();

        $this -> cartSession    = $this -> getSessionProperty();
        $this -> ApiQurier      = new ApiQurier();
        $this -> userModel      = new UserModel();
        $this -> MODEL__account = new MODEL__account();
    }

    public function index() {

        $user_id      = session('user_id');
        $user         = $this -> userModel -> find($user_id);
        $customerData = $this -> MODEL__account -> get__klient($user_id);

        $data = [
            'title'        => '',
            'addGlobalJS'  => $this -> addGlobalJS(),
            'addCSS'       => $this -> addCSS(),
            'addJS'        => $this -> addJS(),
            'user'         => $user,
            'customerData' => $customerData,
            // views
            'views'        => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        if (empty(session() -> has('user_id'))) {
            return view("{$this -> theme}/account/VIEW__acc-login", $data);
        }

        return view("{$this -> theme}/account/VIEW__account", $data);
    }

    public function login() {
        $email_bustat = trim($this -> request -> getPost('email_bustat'));
        $password     = trim($this -> request -> getPost('password'));

        //$this -> setSessionProperty([]);

        $user = $this -> userModel -> getUserByEmailOrBustat($email_bustat);

        if ($user && password_verify($password, $user['password'])) {
            // Успешен вход
            session() -> set('user_id', $user['id']);
            session() -> set('username', $user['username']);
            session() -> set('is_password_changed', $user['is_password_changed']);

            $this -> auto_update_session_product_inCart();

            return redirect() -> to('/');
        } else {
            // Невалидни данни
            return redirect() -> back() -> with('error', 'Грешни данни за влизане!');
        }
    }

    public function register() {
        if ($this -> request -> getMethod() !== 'post') {
            $data = [
                'title'       => '',
                'addGlobalJS' => $this -> addGlobalJS(),
                'addCSS'      => $this -> addCSS(),
                'addJS'       => $this -> addJS(),
                // views
                'views'       => $this -> get_views(),
            ];

            foreach ($this -> global() as $key => $val) {
                $data[$key] = $val;
            }

            return view("{$this -> theme}/account/VIEW__acc-register", $data);
        }

        $fullName         = trim((string) $this -> request -> getPost('full_name'));
        $phone            = trim((string) $this -> request -> getPost('phone'));
        $email            = strtolower(trim((string) $this -> request -> getPost('email')));
        $password         = trim((string) $this -> request -> getPost('password'));
        $password_confirm = trim((string) $this -> request -> getPost('password_confirm'));

        if ($fullName === '' || mb_strlen($fullName) < 3) {
            return redirect() -> back() -> withInput() -> with('error', 'Моля въведете име и фамилия.');
        }

        $phoneDigits = preg_replace('/\D+/', '', $phone);
        if (empty($phoneDigits) || strlen($phoneDigits) < 6) {
            return redirect() -> back() -> withInput() -> with('error', 'Моля въведете валиден телефон.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect() -> back() -> withInput() -> with('error', 'Моля въведете валиден email.');
        }

        if (!$this -> isPasswordStrong($password)) {
            return redirect() -> back() -> withInput() -> with('error', self::PASSWORD_POLICY_MESSAGE);
        }

        if ($password !== $password_confirm) {
            return redirect() -> back() -> withInput() -> with('error', 'Паролите не съвпадат.');
        }

        $db = db_connect();

        $existsInUser = (bool) $db -> table('users_site') -> where('email_login', $email) -> countAllResults();
        $existsInKlient = (bool) $db -> table('_klient') -> where('email', $email) -> countAllResults();

        if ($existsInUser || $existsInKlient) {
            return redirect() -> back() -> withInput() -> with('error', 'Вече съществува регистрация с този email.');
        }

        $username = $fullName;

        try {
            $db -> transBegin();

            $maxUserRow = $db -> table('users_site')
                    -> selectMax('id', 'max_id')
                    -> get()
                    -> getRowArray();
            $userId     = (int) ($maxUserRow['max_id'] ?? 0) + 1;

            if ($userId <= 0) {
                throw new \RuntimeException('Неуспешно генериране на id за потребител.');
            }

            $isUserInserted = $db -> table('users_site') -> insert([
                'id'                  => $userId,
                'username'            => $username,
                'email_login'         => $email,
                'password'            => password_hash($password, PASSWORD_DEFAULT),
                'is_password_changed' => 1,
                'active'              => 1,
                'bulstat'             => null,
            ]);

            if (!$isUserInserted) {
                $dbError = $db -> error();
                throw new \RuntimeException('users_site: ' . ($dbError['message'] ?? 'неуспешен insert'));
            }

            $isKlientInserted = $db -> table('_klient') -> insert([
                'klient_id'       => $userId,
                'gensoft_firm_id' => $userId,
                'zenaNivo'        => 'Цена Кл',
                'klient_name'     => $fullName,
                'klient_tel'      => $phone,
                'email'           => $email,
                'bulstat'         => null,
                'isActive'        => 'Y',
            ]);

            if (!$isKlientInserted) {
                $dbError = $db -> error();
                throw new \RuntimeException('_klient: ' . ($dbError['message'] ?? 'неуспешен insert'));
            }

            if ($db -> transStatus() === false) {
                throw new \RuntimeException('Неуспешно записване в базата.');
            }

            $db -> transCommit();
        } catch (\Throwable $e) {
            $db -> transRollback();
            return redirect() -> back() -> withInput() -> with('error', 'Регистрацията не беше успешна: ' . $e -> getMessage());
        }

        session() -> set('user_id', $userId);
        session() -> set('username', $username);
        session() -> set('is_password_changed', 1);

        $this -> auto_update_session_product_inCart();

        return redirect() -> to(route_to('Account-index') . '#customer-tab') -> with('message', 'Регистрацията е успешна. Попълнете фирмените данни.');
    }

    public function logout() {
        session() -> remove('user_id');
        session() -> remove('username');
        // clear session 
        $this -> setSessionProperty([]);
        return redirect() -> to('/');
    }

    public function changePassword() {
        $loggedUserId = $this -> getSessionAccount();
        $password     = trim($this -> request -> getVar('password'));
        $password_confirm = trim($this -> request -> getVar('password_confirm'));
        $email        = trim($this -> request -> getVar('email'));
//dd($this -> request -> getVar());

        if ($password !== '' && !$this -> isPasswordStrong($password)) {
            return $this -> response -> setJSON([
                'success' => false,
                'message' => self::PASSWORD_POLICY_MESSAGE,
            ]);
        }

        if ($password !== '' && $password !== $password_confirm) {
            return $this -> response -> setJSON([
                'success' => false,
                'message' => 'Паролите не съвпадат.',
            ]);
        }

        $userModel = new UserModel();

        $response = $userModel -> updateUserPassword($loggedUserId, $password, $email);

        if ($password !== '') {
            session() -> set('is_password_changed', 1);
        }

        $jsonMsg = [
            'success' => is_bool($response),
            'message' => is_bool($response) === true ? 'Данните за вход са променени успешно!' : $response['err'],
        ];

        return $this -> response -> setJSON($jsonMsg);
    }

    private function isPasswordStrong(string $password): bool {
        return (bool) preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password);
    }

    public function changeCustomerData() {

        $loggedUserId = $this -> getSessionAccount();
        $form         = $this -> request -> getVar('form-customerData');
        $phone        = trim((string) ($form -> klient_tel ?? ''));
        $phoneDigits  = preg_replace('/\D+/', '', $phone);

        if (empty($phoneDigits) || strlen($phoneDigits) < 6) {
            return $this -> response -> setJSON([
                'success' => false,
                'message' => 'Телефонът е задължителен и трябва да е валиден.',
            ]);
        }

        $form -> klient_id = $loggedUserId;

        $response = $this -> MODEL__account -> save_klient_data($form);
        $isSuccess = !(is_array($response) && isset($response['err']));
        $errMsg    = is_array($response) ? ($response['err'] ?? null) : null;

        $jsonMsg = [
            'success' => $isSuccess,
            'message' => $isSuccess ? 'Данните на потребителя са обновени.' : ($errMsg ?: 'Възникна грешка с обновяването!'),
        ];

        return $this -> response -> setJSON($jsonMsg);
    }

    // данни за доставка обекти
    public function changeDeliveryData() {

        $loggedUserId = $this -> getSessionAccount();
        $data         = $this -> request -> getVar();

        $filteredData = array_filter((array) $data, function ($value) {
            return !empty($value -> lice_zaKont);
        });

        $form['klient_obekt_json'] = !empty($filteredData) ? json_encode($filteredData, JSON_UNESCAPED_UNICODE) : NULL;

        $form['klient_id'] = $loggedUserId;

        $this -> MODEL__account -> save_klient_deliveryData($form);

        $response = [
            'success' => true,
            'message' => 'Данните за доставка са запазени.',
        ];

        return $this -> response -> setJSON($response);
    }

    // tyrseне на град  за autocomplete, когато е до адрес
    public function get_speeedyOficeData() {
        $siteId   = $this -> request -> getVar('siteId');
        $location = 'location/office/' . $siteId;
        $jsonData = ['unsetCountryId'];

        $jsonResponse = json_encode(json_decode($this -> ApiQurier -> speedyRequest($location, $jsonData)));

        return $jsonResponse;
    }

    public function addCSS() {
        $page = 'css/pages/';

        return [
            "$page/global",
        ];
    }

    public function addJS() {
        $theme = 'js/theme/electro';

        $plugins = ["$theme/loadPlugins"];

        $global = ["$theme/global"];

        $default = ["$theme/pages/account/account"];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);
    }
}
