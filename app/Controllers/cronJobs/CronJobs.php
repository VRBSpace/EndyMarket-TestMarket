<?php

namespace App\Controllers\cronJobs;

use CodeIgniter\Controller;
use CodeIgniter\CLI\CLI;
use App\Models\BaseModel;
use \App\Controllers\BaseController;
use App\Models\MODEL__xml;

//  top -icd1
// pkill -f /path/to/script.php    ili pkill -o за спиране на всички задачи cron
class CronJobs extends Controller {

    protected $baseModel;
    protected $db;

    public function __construct() {
        //parent::__construct();

        $this -> BaseModel = new BaseModel();
        // Limit it to CLI requests only using built-in CodeIgniter function
        if (!is_cli()) {
            exit('Only CLI access allowed');
        }

        // Load the BaseModel and database connection
        $this -> baseModel = new BaseModel();
        $this -> db        = \Config\Database::connect();

        $this -> MODEL__xml = new MODEL__xml();
    }

    private function productLevelMapping($data = '') {
        return match ($data) {
            'Цена В' => 'cenaB',
            'Цена А' => 'cenaA',
            'Цена С' => 'cenaKl',
            'Цена Кл' => 'cenaKl',
            'cenaKKC' => 'cenaKKC',
            'cenaPromo' => 'cenaPromo',
            'Специална' => 'cenaSpec',
            default => null
        };
    }

    public function cron_exp_xml() {

        $clientModel = new \App\Models\KlientModel();

        $usersSite = $this -> db -> table($this -> baseModel::TBL_USERSITE)
                        -> select('id,bulstat,xml_conf_json')
                        -> get() -> getResultArray();

        if (empty($usersSite)) {
            CLI::write('No data found.', 'yellow');
            return;
        }

        foreach ($usersSite as $user) {
            $xmlConf_json = json_decode($user['xml_conf_json'], true) ?? null;

            if (empty($xmlConf_json) || empty($user['bulstat'])) {
                continue;
            }

            $arrayKeys = array_keys($xmlConf_json)[0];

            if (is_array($xmlConf_json[$arrayKeys])) {
                $productPriceLevel = $this -> productLevelMapping($clientModel -> getcenovaNivoByUserId($user['id']));

                $this -> MODEL__xml -> export($xmlConf_json[$arrayKeys], $user, $productPriceLevel);
            }
        }
    }
}
