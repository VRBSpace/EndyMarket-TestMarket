<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__account extends BaseModel {

    protected $table = self::TBL_KLIENT; // Име на таблицата в базата данни

    public function get__klient($id = '') {
        if (is_numeric($id)) {

            return $this -> where('klient_id', $id) -> get() -> getRow();
        }
    }

    public function save_klient_data($data = []) {

        try {
            $this -> db -> transStart();
            
            $result = $this -> upsert($data);

            $this -> db -> transComplete();
            return $result;
        } catch (\Exception $e) {
            $this -> db -> transRollback();
            return ['err' => $e -> getMessage()];
        }
    }

    public function save_klient_deliveryData($data = []) {

        try {
            $this -> db -> transStart();

            $result = $this -> upsert($data);

            $this -> db -> transComplete();

            return $result;
        } catch (\Exception $e) {
            $this -> db -> transRollback();
            return ['err' => $e -> getMessage()];
        }
    }

//    public function remove_klient($id) {
//        if ($id) {
//            $delete = $this -> db -> table($this -> tbl_category)
//                    -> where('category_id', $id)
//                    -> delete();
//
//            // изтриване на подкатегориите към дадена категория
//            $this -> db -> table($this -> tbl_category)
//                    -> where('parent_id', $id)
//                    -> delete();
//
//            return ($delete == true) ? true : false;
//        }
//    }
}
