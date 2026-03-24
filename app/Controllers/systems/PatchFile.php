<?php

namespace App\Controllers\systems;

use \App\Libraries\UnifiedDiffPatcher;

class PatchFile extends \App\Controllers\BaseController {

    public function __construct() {
        parent::__construct();

        $this -> UnifiedDiffPatcher = new UnifiedDiffPatcher();
    }

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

                    // return redirect() -> back();
                } else {
                    $session -> setFlashdata('msg', 'Invalid credentials');
                    return redirect() -> back() -> with('error', 'Грешни данни за влизане!');
                }
            } else {
                return view('system/VIEW__system-login', []);
                exit;
            }
        }

        $file    = $this -> request -> getFile('file');
        $message = '';

        $validation = $this -> validateForm();
        if ($this -> request -> getPost() && $validation -> withRequest($this -> request) -> run() && $file -> isValid() && !$file -> hasMoved()) {

            $folder = WRITEPATH . '/uploads/uploadsPatch/';
            // създаване на папка ако не съществува
            if (!is_dir($folder)) {
                mkdir($folder, 0777, TRUE);
            }

            $filename = $file -> getName();
            $file -> move($folder, $filename);
            $dest     = $folder . $filename;

            // Run the Patching process
            $diff = $this -> UnifiedDiffPatcher;
            $p    = 0; // -p parameter of the unix patch command
            //Validate the patch, and display debug informations
            $ret  = $diff -> validatePatch($dest, $p, true);

            if ($ret !== false) {
                $diff -> processPatch($dest, $p); //Apply the patch without displaying debug informations.
                $message .= '<br>' . '<span style="color:green">Patch файла е приложен успешно</span>';
            } else {
                $message .= '<br>' . '<span style="color:white;background: red;">Error рапорт(операцията patching e прекратена):</span>';
                $message .= implode("\n", $diff -> getError());
            }

            unlink($dest);
        } else {
            $message .= $validation -> listErrors();
        }

        $data = [
            'page_title' => 'Добавяне на Patch',
            'message'    => $message ?? '',
            'create_btn' => '<button type="submit" form="form_patch" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Запазване"> Качване и актуалиране</button>',
        ];

        return view('system/VIEW__patchFile', $data);
    }
}
