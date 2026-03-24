<?php

namespace App\Models;

use CodeIgniter\Model;
use \App\Entities\ENT__webSite;

//use \App\Controllers\ApiQurier;

class MODEL__apiQurier extends Model {

    protected $tbl_site = '_sp_site';

    public function __construct() {
        parent::__construct();

        // $this -> ApiQurier    = new ApiQurier();
        $this -> ENT__webSite = new ENT__webSite();
    }

    // извл на оданните за куриерите за доставка еконт, спееди
    function get__auth($arg = []) {

        $row = $this -> ENT__webSite -> get__site($this -> db, $arg['siteId']);
        $db  = \Config\Database::connect($this -> ENT__webSite -> get__siteAuth($row));

        $user = $pass = null;
        if ($row['site_platf'] == 'opencart') { // от ел магазин
            $result = $db -> table('setting')
                    -> select('key, value')
                    -> like('key', ($arg['type'] == 'speedy' ? 'speedy' : 'econt') . '_user', 'both')
                    -> orLike('key', ($arg['type'] == 'speedy' ? 'speedy' : 'econt') . '_pass', 'both')
                    -> get()
                    -> getResult();

            foreach ($result as $row) {
                if (strpos($row -> key, '_user') !== false) {
                    $user = $row -> value;
                } elseif (strpos($row -> key, '_pass') !== false) {
                    $pass = $row -> value;
                }
            }
        }

        return ['login' => $user, 'password' => $pass];
    }

}
