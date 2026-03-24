<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__brand extends BaseModel {

    public function get__brands() {
    
        $r = $this -> db -> table(self::TBL_BRAND)
                        -> where('brandTxt !=', '')
                        //-> orderBy('brandTxt', 'ASC')
                        -> orderBy('brand_sort_order', 'ASC')
                        -> get() -> getResultArray();

        //d($r);
        return $r;
    }

    public function get__brandsByIds(array $brandIds) {
        $ids = array_values(array_filter(array_map('intval', $brandIds)));
        if (empty($ids)) {
            return [];
        }

        return $this -> db -> table(self::TBL_BRAND)
                        -> whereIn('brand_id', $ids)
                        -> where('brandTxt !=', '')
                        -> orderBy('brand_sort_order', 'ASC')
                        -> get() -> getResultArray();
    }

    public function getBrandNameByID($id) {
        return $this -> select('brand_name')
                        -> where('brand_id', $id)
                        -> get()
                        -> getRow('brand_name');
    }
}
