<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__orderDetail extends BaseModel {

    public function getOrderDetailByOrderId($order_id) {

        $extractedKeys = $this -> db -> table(self::TBL_SITE_ORDER)
                        -> select('JSON_KEYS(JSON_UNQUOTE(JSON_EXTRACT(product_json, "$"))) AS product_keys')
                        -> where('order_id', $order_id)
                        -> get() -> getFirstRow();

        if (empty($extractedKeys)) {
            return [];
        }

        $productIds = implode(',', json_decode($extractedKeys -> product_keys, true));

        $query2 = $this -> db -> table(self::TBL_SITE_ORDER . ' as o')
                        -> join(self::TBL_PRODUCT . ' p', "FIND_IN_SET(p.product_id, '$productIds')", 'left')
                        //-> join(self::TBL_PRODUCT_PRICE_LEVEL . ' sp', 'product_id', 'left')
                        -> where('o.order_id', $order_id)
                        -> get() -> getResultArray();

        //dd( $query2 );
        return $query2;
    }

    public function insertData($data) {
        //return $this -> db -> table(self::TBL_SITE_ORDERDETAIL) -> insertBatch($data);
    }
}
