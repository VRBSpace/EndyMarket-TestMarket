<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__order extends BaseModel {

    public function getOrdersByKlientId($klient_id) {
        return $this -> db -> table(self::TBL_SITE_ORDER)
                        -> where('klient_id', $klient_id)
                        -> orderBy('order_id', 'DESC')
                        -> get() -> getResultArray();
    }

    public function getOrderByOrderId($orderId) {
        return $this -> db -> table(self::TBL_SITE_ORDER)
                        -> where('order_id', $orderId)
                        -> get() -> getRow();
    }

    public function getOrderDocumentsByOrderId($orderId) {
        return $this -> db -> table(self::TBL_SITE_ORDER)
                        -> select('doc_files_path')
                        -> where('order_id', $orderId)
                        -> get() -> getRow('doc_files_path');
    }

    public function getOrderStatus() {
        return $this -> db -> table(self::TBL_SP_STATUS)
                        -> get() -> getResultArray();
    }

    public function insertData($data) {
        $this -> db -> table(self::TBL_SITE_ORDER) -> insert($data);
        return $this -> db -> insertID();
    }

    public function upsertData($data, $isEmpty = false) {
        // $isEmpty - ако е изчистена цялата количка

        if ($isEmpty && empty($data)) {
            return;
        }

        if ($isEmpty == true) {
            $this -> db -> table(self::TBL_SITE_ORDERCART) -> where('klient_id', $data) -> delete();
            return;
        }

        $this -> db -> table(self::TBL_SITE_ORDERCART) -> upsert($data);
    }
}
