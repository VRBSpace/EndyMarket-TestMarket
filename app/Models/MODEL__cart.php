<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__cart extends BaseModel {

    public function get__cart_productByUserId($userId = null) {
        if (empty($userId)) {
            return null;
        }


        $productData = $this -> db -> table(self::TBL_SITE_ORDERCART)
                        -> select('product_json')
                        -> where('klient_id', $userId)
                        -> get() -> getRow();

        $productIds = array_keys(json_decode($productData -> product_json ?? '[]', true) ?? []);

        if (empty($productIds)) {
            return null;
        }

        $sql = $this -> db -> table(self::TBL_PRODUCT . ' p')
                        -> join(self::TBL_SITE_ORDERCART . ' oc', "oc.klient_id=$userId", 'inner')
                        -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'product_id', 'left')
                        -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                        -> join('(SELECT model_id,model FROM ' . self::TBL_MODEL . ' ) as pm', 'pm.model_id=p.model_id', 'left')
                        -> where('ps.sp_site_id', $_ENV['app.siteId'])
                        -> whereIn('p.product_id', $productIds)
                        -> get() -> getResultArray();

        return $sql;
    }
}
