<?php

namespace App\Controllers;

class Coocie extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function save() {
// 1. Взимаме данните от POST заявката
    $consentJson = $this->request->getPost('consent');
  

    // 2. Подготвяме данните за запис
    $data = [
        'ip' => $this->request->getIPAddress(),
        'consent'  => $consentJson,

        'user_agent' => $this->request->getUserAgent()->getAgentString(),
        'created_at' => date('Y-m-d H:i:s')
    ];

    // 3. Записваме в базата данни
    $db = \Config\Database::connect();
    $db->table('cookie_consents')->insert($data);
    
    // Записваме и бисквитка за браузъра (за 1 година)
    setcookie('uc_v4', $consentJson, time() + (86400 * 365), "/");

    }

   
}
