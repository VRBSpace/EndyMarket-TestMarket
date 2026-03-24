<?php

namespace App\Controllers\systems;

use \CILogViewer\CILogViewer;

class LogViewer extends \App\Controllers\BaseController {

    public function __construct() {
        parent::__construct();

        $this -> logViewer = new CILogViewer();
    }

    // view =================================
    public function index() {
        $logout = $this -> request -> getVar('logout');

        if ($logout) {
            session() -> destroy();
            return view('system/VIEW__system-login', []);
        }

        if (empty(session() -> has('system_login_user'))) {
            if ($this -> request -> getMethod() === 'post') {
                $session = session();

                $username       = $this -> request -> getVar('username');
                $password       = $this -> request -> getVar('password');
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// parola 'pasS@123#5'

                if ($username == 'admin@123' && password_verify($password, '$2y$10$/OwRHZwLkHKSvlNHGhxOnOl4HNoIpvUEgjvTDKC7fgUU9WVxHBIRq')) {
                    $session -> set([
                        'system_login_user' => $username,
                        'isLoggedIn'        => true,
                    ]);
                    
                    return redirect() -> to('systems/LogViewer');
                     
                } else {
                    $session -> setFlashdata('msg', 'Invalid credentials');
                    return redirect() -> back() -> with('error', 'Грешни данни за влизане!');
                }
            } else {
                echo view('system/VIEW__system-login', []);
            }
        } else {
            return $this -> logViewer -> showLogs($this -> request);
        }
    }
}
