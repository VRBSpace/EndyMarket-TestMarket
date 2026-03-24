<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__cenovaLista extends BaseModel {

    public function getAllCenovaLista() {
        $q = $this -> db -> table(self::TBL_ZENOVA_LISTA)
                        -> select('id, offersName')
                        -> where('is_file', null)
                        -> get() -> getResultArray();

        return $q;
    }

    public function getCenovaListaById($id) {
        $q = $this -> db -> table(self::TBL_ZENOVA_LISTA)
                        -> where('id', $id)
                        -> get() -> getRow();

        return $q;
    }

    public function get__products_fromCenova($data = []) {

        $q = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'product_id', 'left')
                -> join(self::TBL_CATEGORY . ' sc', 'sc.category_id = p.category_id', 'left')
                -> join(self::TBL_BRAND . ' b', 'brand_id', 'left')
                -> join('(SELECT model_id,model FROM ' . self::TBL_MODEL . ' ) as pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> whereIn('p.product_id', $data['productIds'])
                -> where("pl.{$data['productPriceLevel']} IS NOT NULL"); // ако има цена
        // филтър по име и колона
        if (!empty($data['searchName'])) {
            $q -> groupStart();
            $q -> like('p.product_name', $data['searchName'], 'both')
                    -> orLike('b.brandTxt', $data['searchName'], 'both');
            $q -> groupEnd();
        }

        if (isset($data['nalichnost']) && !empty($data['nalichnost'])) {
            $q -> where('p.nalichnost > 0');
        }

        if (!empty($data['to'])) {
            $q -> groupStart();
            $q -> where("pl.{$data['productPriceLevel']} >=", (float) $data['from'])
                    -> where("pl.{$data['productPriceLevel']} <=", (float) $data['to'] + 0.01)
                    -> orWhere("pl.{$data['productPriceLevel']} IS NULL");
            $q -> groupEnd();
        }

        if (!empty($data['sort'])) {
            $q -> orderBy($data['sort']);
        } else {
            $q -> orderBy('FIELD(p.product_id, ' . implode(',', $data['productIds']) . ')');
        }

        //dd($q -> getCompiledSelect());
        // dd($q -> get() -> getResultArray());
        return $q -> get() -> getResultArray();
    }
}
