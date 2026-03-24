<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__filter extends BaseModel {

    // фълтър от дясно за производител

    public function get__brands($data = []) {

        $query = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> select('b.brandTxt,b.brand_id,GROUP_CONCAT(DISTINCT p.product_id) AS product_ids,pm.model,pm.model_id,GROUP_CONCAT(DISTINCT pm2.model ORDER BY pm2.model ASC) AS additional_models')
                -> join(self::TBL_BRAND . ' b', 'brand_id', 'inner')
                -> join(self::TBL_MODEL . ' pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_PRODUCT_MODEL_ADDIT_LINK . ' pam', 'pam.model_main_id=p.model_id', 'left')// Допълнителни модели
                -> join(self::TBL_MODEL . ' pm2', 'pm2.model_id = pam.model_addit_id', 'left') // самите доп модели
                //-> join(self::TBL_MODEL . ' pm2', 'pm2.model_id IN ( p.model2_id, p.model3_id)', 'left') 
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> where('b.brandTxt IS NOT NULL')
                -> orderBy('b.brandTxt', 'ASC')
                -> groupBy('b.brand_id');

        if (isset($data['productModels'])) {
            $data['to'] = null;

            $query -> whereIn('pm.model', is_array($data['productModels']) ? $data['productModels'] : array($data['productModels']));
        }

        if (isset($data['brandId'])) {
            $brandIds = is_array($data['brandId'])
                ? $data['brandId']
                : preg_split('/[_,]+/', (string) $data['brandId']);
            $brandIds = array_values(array_filter(array_map('intval', $brandIds)));

            if (!empty($brandIds)) {
                count($brandIds) === 1
                    ? $query -> where('p.brand_id', $brandIds[0])
                    : $query -> whereIn('p.brand_id', $brandIds);
            }
        }

        // филтър по клон категории (самата + всички деца)
        if (!empty($data['subCategoryIds'])) {
            $query -> whereIn('p.category_id', $data['subCategoryIds']);
        } elseif (!empty($data['categoryId'])) {
            // филтър по единична подкатегория
            $query -> where('p.category_id', $data['categoryId']);
        }

        // филтър по главна категория
        if (!empty($data['categoryRootId'])) {
            $query -> where('p.categoryRoot_id', $data['categoryRootId']);
        }

        if (!empty($data['productCharValueIds'])) {
            $subQuery = $this -> db -> table(self::TBL_PRODUCT_CHARACTERISTIC . ' pa')
                    -> select('pa.product_id, COUNT(DISTINCT pa.product_characteristic_value_id) as characteristic_count')
                    -> join(self::TBL_PRODUCT . ' p', 'product_id', 'inner')
                    -> where('p.category_id', $data['categoryId'])
                    -> whereIn('pa.product_characteristic_value_id', $data['productCharValueIds'])
                    -> groupBy('pa.product_id')
                    -> having('COUNT(DISTINCT pa.product_characteristic_value_id) =', count($data['productCharValueIds']));

            $validProductIds = array_column($subQuery -> get() -> getResultArray(), 'product_id');

            if (!empty($validProductIds)) {
                $query -> whereIn('p.product_id', $validProductIds);
            }
        }

        // филтър по промоция
        if (isset($data['promo'])) {
            //
            $query -> where('pl.zavishena_zena !="" and pl.is_promo = 1');
            // 
        } else {
            $query -> groupStart();
            $query -> where("pl.{$data['productPriceLevel']} IS NOT NULL"); // ако има цена
            $query -> orwhere('pl.zavishena_zena !="" and pl.is_promo = 1');
            $query -> groupEnd();
        }

        // филтър по име и колона
        if (!empty($data['searchName'])) {
            $query -> like('p.product_name', $data['searchName'], 'both')
                    -> where("pl.{$data['productPriceLevel']} IS NOT NULL");
        }

        //dd($query -> getCompiledSelect());
        return $query -> get() -> getResultArray();
    }

    // фълтър по модел към марка
    public function get__brandsModels($data = []) {
        $query = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> select([
                    'pl.*',
                    'b.brandTxt',
                    'b.brand_id',
                    'pmv.model_year',
                    'pm.model',
                    'pm.model_id',
                    'MAX(pm.image_model) AS image_model',
                    // Множество години от модификации
                    'GROUP_CONCAT(DISTINCT pmv.model_year ORDER BY pmv.model_year ASC) AS yearsArr',
                    // ID на продукти с този модел
                    'GROUP_CONCAT(DISTINCT p.product_id) AS productIds',
                    // Допълнителни модели - JSON формат
                    'CONCAT("[", 
                            GROUP_CONCAT(
                                DISTINCT CONCAT(
                                    "{",
                                        "\"name\":", JSON_QUOTE(pm2.model), ",",
                                        "\"img\":", JSON_QUOTE(pm2.image_model),
                                    "}"
                                )
                                ORDER BY pm2.model ASC
                            ), 
                        "]") AS additional_models_json',
                    // Списък от имена на доп. модели
                    'GROUP_CONCAT(DISTINCT pm2.model ORDER BY pm2.model ASC) AS additional_models',
                    'COUNT(DISTINCT pm2.model) AS count_additional_models'
                ])
                // === JOIN-и ===
                -> join(self::TBL_BRAND . ' b', 'b.brand_id = p.brand_id', 'inner')
                -> join(self::TBL_MODEL . ' pm', 'pm.model_id = p.model_id', 'left')
                // Доп. модели – през линк
                -> join(self::TBL_PRODUCT_MODEL_ADDIT_LINK . ' pam', 'pam.model_main_id = p.model_id', 'left')
                -> join(self::TBL_MODEL . ' pm2', 'pm2.model_id = pam.model_addit_id', 'left')
                -> join(self::TBL_PRODUCT_MODELVAR . ' pmv', 'pm.model_id = pmv.model_id', 'left')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id = p.product_id', 'left')
                // Само продукти, които са видими на текущия сайт
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id = p.product_id', 'inner')

                // === видимост по сайт и потребител ===
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> where('pm.model IS NOT NULL')
                -> groupBy('pm.model_id')
                -> orderBy('pm.model', 'ASC');

        if (!empty($data['brandId']) || !empty($data['f_brandId'])) {
            $brandFilter = $data['brandId'] ?? $data['f_brandId'];
            $brandIds = is_array($brandFilter)
                ? $brandFilter
                : preg_split('/[_,]+/', (string) $brandFilter);
            $brandIds = array_values(array_filter(array_map('intval', $brandIds)));

            if (!empty($brandIds)) {
                count($brandIds) === 1
                    ? $query -> where('b.brand_id', $brandIds[0])
                    : $query -> whereIn('b.brand_id', $brandIds);
            }
        }

        if (!empty($data['categoryId'])) {
            $query -> where('p.category_id', $data['categoryId']);
        }

        if (!empty($data['rootCategoryIds']) || !empty($data['f_rootCatId'])) {
            $query -> where('p.categoryRoot_id', $data['rootCategoryIds'] ?? $data['f_rootCatId']);
        }

        if (!empty($data['searchName'])) {
            $query -> like('p.product_name', $data['searchName'], 'both');
        }

        if (!empty($data['productPriceLevel'])) {
            $priceCol = $data['productPriceLevel'];
            $query -> groupStart()
                    -> where("pl.$priceCol IS NOT NULL") // ако има цена
                    -> orwhere('pl.zavishena_zena !="" and pl.is_promo = 1')
                    -> groupEnd();
        }
        //dd($query -> getCompiledSelect() );
        //dd($query -> get() -> getResultArray());
        return $query -> get() -> getResultArray();
    }

    public function get__product_to_category($data = []) {

        $query = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> select('c.*,p.product_id, p.categoryRoot_id, p.category_id,p.brand_id,pm.model')
                -> join(self::TBL_CATEGORY . ' c', 'category_id', 'left')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                -> join(self::TBL_MODEL . ' pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> orderBy('p.product_id ', 'ASC');

        if (!empty($_GET['f_brandId'])) {
            $brandIds = array_values(
                array_filter(
                    array_map(
                        'intval',
                        preg_split('/[_,]+/', (string) $_GET['f_brandId'])
                    )
                )
            );

            if (!empty($brandIds)) {
                count($brandIds) === 1
                    ? $query -> where('p.brand_id', $brandIds[0])
                    : $query -> whereIn('p.brand_id', $brandIds);
            }
        }

//        if (!empty($_GET['f_models']) && isset($_GET['catToModel'])) {
//            $query -> groupStart()
//                    -> whereIn('pm.model', explode('_', $_GET['f_models']))
//                    //-> orWhereIn('pm.model', explode('_', $_GET['f_modelAdditional']))
//                    -> groupEnd();
//        }

        if (!empty($data['productPriceLevel'])) {
            $query -> groupStart()
                    -> where("pl.{$data['productPriceLevel']} IS NOT NULL") // ако има цена
                    -> orwhere('pl.zavishena_zena !="" and pl.is_promo = 1')
                    -> groupEnd();
        }

        //dd($query -> get() -> getResultArray());
        //dd($query -> getCompiledSelect());
        return $query -> get() -> getResultArray();
    }

    public function get__subCategoryAttr($category_id) {

        $query = $this -> db -> table(self::TBL_CATEGORY_ATTRIBUTE)
                        -> where('category_id', $category_id)
                        -> where('is_visible  ', 1)
                        -> orderBy('ISNULL(category_char_position)')
                        -> orderBy('category_char_position ', 'ASC')
                        -> get() -> getResultArray();

        return $query;
    }

    public function get_characteristic_filtersBySubcategories($subCatIds = []) {
        
        if (empty($subCatIds)) {
            return [];
        }

        $query = $this -> db -> table(self::TBL_CATEGORY_ATTRIBUTE)
                        -> select('category_characteristic_id')
                        -> where('category_id', (int) end($subCatIds))
                        -> where('is_visible', 1)
                        -> get() -> getResultArray();

        $category_characteristic_ids = array_map('intval', array_column($query, 'category_characteristic_id'));

        if (empty($category_characteristic_ids)) {
            return [];
        }

        $query = $this -> db -> table(self::TBL_PRODUCT_CHARACTERISTIC . ' pc')
                        -> select(' p.product_id,
                                    cc.category_characteristic_id,
                                    cc.value AS characteristic_name,
                                    pcv.product_characteristic_value_id,
                                    pcv.product_characteristic_text
                                   ')
                        -> join(self::TBL_PRODUCT . ' p', 'product_id', 'inner')
                        -> join(self::TBL_PRODUCT_CHAR_VALUE . ' pcv', 'product_characteristic_value_id', 'left')
                        -> join(self::TBL_CATEGORY_ATTRIBUTE . ' cc', 'category_characteristic_id', 'left')
                        -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                        -> whereIn('p.category_id', $subCatIds)
                        -> whereIn('pc.category_characteristic_id', $category_characteristic_ids)
                        -> groupStart()
                        -> where('ps.sp_site_id', $_ENV['app.siteId'])
                        -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                        -> groupEnd()
                        -> groupBy(['cc.category_characteristic_id', 'pcv.product_characteristic_value_id'])
                        -> orderBy('ISNULL(category_char_position)')
                        -> orderBy('category_char_position', 'ASC')
                        -> orderBy('ABS(pcv.product_characteristic_text)', 'ASC')
                        -> orderBy('pcv.product_characteristic_text', 'ASC')
                        -> get() -> getResultArray();

        // Групиране по характеристика + броене на продукти в една стъпка
        $filters    = [];
        $productIds = [];
        foreach ($query as $row) {
            $charId = $row['category_characteristic_id'];
            $valId  = $row['product_characteristic_value_id'];
            $prodId = $row['product_id'];

            // Инициализация на характеристиката
            if (!isset($filters[$charId])) {
                $filters[$charId]['name']   = $row['characteristic_name'];
                $filters[$charId]['values'] = [];
            }

            // Инициализация на стойността
            if (!isset($filters[$charId]['values'][$valId])) {
                $filters[$charId]['values'][$valId] = [
                    'value_id'      => $valId,
                    'value_text'    => $row['product_characteristic_text'],
                    'product_count' => 0,
                    'product_ids'   => []
                ];
            }

            // Броим уникалните продукти
            $filters[$charId]['values'][$valId]['product_count']++;

            // добавяне на ID на продукта, ако не е добавен
            if (!in_array($prodId, $filters[$charId]['values'][$valId]['product_ids'])) {
                $productIds[] = $prodId;
            }
        }

        return ['filters' => $filters, 'productIds' => $productIds];
    }

    public function get_characteristic_filtersByProductIds(array $productIds = []) {
        $ids = array_values(array_filter(array_map('intval', $productIds)));
        if (empty($ids)) {
            return [];
        }

        // Взимаме само видимите характеристики за продуктите в текущия списък
        $query = $this -> db -> table(self::TBL_PRODUCT_CHARACTERISTIC . ' pc')
                        -> select(' p.product_id,
                                    cc.category_characteristic_id,
                                    cc.value AS characteristic_name,
                                    pcv.product_characteristic_value_id,
                                    pcv.product_characteristic_text
                                   ')
                        -> join(self::TBL_PRODUCT . ' p', 'product_id', 'inner')
                        -> join(self::TBL_PRODUCT_CHAR_VALUE . ' pcv', 'product_characteristic_value_id', 'left')
                        -> join(self::TBL_CATEGORY_ATTRIBUTE . ' cc', 'category_characteristic_id', 'left')
                        -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                        -> whereIn('p.product_id', $ids)
                        -> where('cc.is_visible', 1)
                        -> groupStart()
                        -> where('ps.sp_site_id', $_ENV['app.siteId'])
                        -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                        -> groupEnd()
                        -> groupBy(['cc.category_characteristic_id', 'pcv.product_characteristic_value_id'])
                        -> orderBy('ISNULL(category_char_position)')
                        -> orderBy('category_char_position', 'ASC')
                        -> orderBy('ABS(pcv.product_characteristic_text)', 'ASC')
                        -> orderBy('pcv.product_characteristic_text', 'ASC')
                        -> get() -> getResultArray();

        // Групиране по характеристика + броене на продукти
        $filters    = [];
        $productIds = [];
        foreach ($query as $row) {
            $charId = $row['category_characteristic_id'];
            $valId  = $row['product_characteristic_value_id'];
            $prodId = $row['product_id'];

            if (!isset($filters[$charId])) {
                $filters[$charId]['name']   = $row['characteristic_name'];
                $filters[$charId]['values'] = [];
            }

            if (!isset($filters[$charId]['values'][$valId])) {
                $filters[$charId]['values'][$valId] = [
                    'value_id'      => $valId,
                    'value_text'    => $row['product_characteristic_text'],
                    'product_count' => 0,
                    'product_ids'   => []
                ];
            }

            $filters[$charId]['values'][$valId]['product_count']++;
            if (!in_array($prodId, $filters[$charId]['values'][$valId]['product_ids'])) {
                $filters[$charId]['values'][$valId]['product_ids'][] = $prodId;
            }
            if (!in_array($prodId, $productIds)) {
                $productIds[] = $prodId;
            }
        }

        return ['filters' => $filters, 'productIds' => $productIds];
    }
}
