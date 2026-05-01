<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__global extends BaseModel {

    //бр на всички продукти 

    function count__all_product($data = []) {

        $sql = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> select('p.product_id')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                -> join(self::TBL_BRAND . ' b', 'brand_id', 'left')
                -> join(self::TBL_MODEL . ' pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_PRODUCT_MODEL_ADDIT_LINK . ' pam', 'pam.model_main_id=p.model_id', 'left')// Допълнителни модели
                -> join(self::TBL_MODEL . ' pm2', 'pm2.model_id = pam.model_addit_id', 'left') // самите доп модели
                // -> join(self::TBL_MODEL . ' pm2', 'pm2.model_id IN ( p.model2_id, p.model3_id)', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> groupBy('p.product_id');

        if (!empty($data['subCategoryIds'])) {
            $sql -> whereIn('category_id', $data['subCategoryIds']);
        } elseif (!empty($data['categoryId'])) {
            $sql -> where('category_id', $data['categoryId']);
        }

        if (!empty($data['productIds'])) {
            $sql -> whereIn('p.product_id', $data['productIds']);
        }

        if (!empty($data['brandId']) || !empty($data['f_brandId'])) {
            $brandFilter = $data['f_brandId'] ?? $data['brandId'];
            $brandIds = is_array($brandFilter)
                ? $brandFilter
                : preg_split('/[_,]+/', (string) $brandFilter);
            $brandIds = array_values(array_filter(array_map('intval', $brandIds)));

            if (!empty($brandIds)) {
                count($brandIds) === 1
                    ? $sql -> where('p.brand_id', $brandIds[0])
                    : $sql -> whereIn('p.brand_id', $brandIds);
            }
        }

        if (!empty($data['f_subCatIds'])) {
            //$sql -> whereIn('category_id', explode(',', $data['f_subCatIds']));
        }


        if (!empty($_GET['f_mainModelId'])) {
            $sql -> where('pm.model_id', $_GET['f_mainModelId']);
        }

        $selectedCharValueIds = $data['productCharValueIds'] ?? [];
        if (empty($selectedCharValueIds) && !empty($_GET['f_podCharValId'])) {
            $selectedCharValueIds = explode('_', $_GET['f_podCharValId']);
        }

        if (!empty($selectedCharValueIds)) {
            $matchedProductIds = $this->resolveCharacteristicFilteredProductIds((array) $selectedCharValueIds);

            if (empty($matchedProductIds)) {
                $sql->where('1 = 0');
            } else {
                $sql->whereIn('p.product_id', $matchedProductIds);
            }
        }

        if (isset($data['rootCategoryIds']) || !empty($data['f_rootCatId'])) {
            $sql -> where('p.categoryRoot_id', $data['f_rootCatId']);
        }

//        if (!empty($_GET['f_p'])) {
//            $sql -> whereIn('p.product_id', explode('_', $_GET['f_p']));
//        }
        // филтър по промоция
        if (isset($data['promo'])) {
            //
            $sql -> where('pl.zavishena_zena !="" and pl.is_promo = 1');

            // 
        } else {
            $sql -> groupStart();
            $sql -> where("pl.{$data['productPriceLevel']} IS NOT NULL"); // ако има цена
            $sql -> orwhere('pl.zavishena_zena !="" and pl.is_promo = 1');
            $sql -> groupEnd();
        }

        // филтър по нови продукти
        if (!empty($data['new'])) {
            $sql -> where('p.badge_index =1');
        }

        // филтър по очаквани продукти
        if (!empty($data['feature'])) {
            $sql -> where('p.badge_index =2');
        }

        // филтър по фиксирана цена
        if (!empty($data['fixPrice'])) {
            $sql -> where('p.badge_index =4');
        }

        if (!empty($data['searchName'])) {
            $sql -> like("p.product_name", $data['searchName'], 'both') -> orWhere('pm.model', $data['searchName']);
        }

        if (!empty($data['dilarUrlId'])) {
            $sql -> join(self::TBL_SPECIAL . ' ul', 'FIND_IN_SET(p.product_id, ul.productsID)', 'inner');
            $sql -> where('ul.id', $data['dilarUrlId']);
        }

        //d($data);
        //dd($sql ->  getCompiledselect());
        //   var_dump($sql ->  getCompiledselect());
        //     die();

        return $sql -> countAllResults();
    }
}
