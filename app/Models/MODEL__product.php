<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__product extends BaseModel {

    // в MODEL__product (примерно в началото на класа)
    public const TBL_PRODUCT_VARIATION = '_product_variation';
    public const TBL_PRODUCT_VARIATION_VALUE = '_product_variation_value';

    public function get__all_product($data = []) {

        $sql = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> select('p.*,b.*,pl.*,pm.model, GROUP_CONCAT(DISTINCT pm2.model) AS additional_models')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                -> join(self::TBL_BRAND . ' b', 'brand_id', 'left')
                //-> join(self::TBL_MODEL . ' pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_MODEL . ' pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_PRODUCT_MODEL_ADDIT_LINK . ' pam', 'pam.model_main_id=p.model_id', 'left')// Допълнителни модели
                -> join(self::TBL_MODEL . ' pm2', 'pm2.model_id = pam.model_addit_id', 'left') // самите доп модели
                // -> join(self::TBL_MODEL . ' pm2', 'pm2.model_id IN ( p.model2_id, p.model3_id)', 'left')
                // Допълнителни модели
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> groupBy('p.product_id');

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

        if (!empty($_GET['f_mainModelId'])) {
            $sql -> where('pm.model_id', $_GET['f_mainModelId']);
        }

        // филтър по подкатегория single
        if (!empty($data['categoryId'])) {
            $sql -> where('p.category_id', $data['categoryId']);
        }

        // филтър по подкатегории id с разделител _
        if (!empty($_GET['f_subCatIds'])) {
            unset($data['categoryId'], $data['subCategoryIds']);
            $parts = explode('_', $_GET['f_subCatIds']);

            // последната подкат. от масива 129_123
            $subCatLastId = end($parts);
            $sql -> where('p.category_id', $subCatLastId);
        }

        // фултър по х-ки на продукти в дадена подкатегоирия
        if (!empty($_GET['f_podCharValId'])) {
            $charValueIds = explode('_', $_GET['f_podCharValId']);
            $sql -> join(self::TBL_PRODUCT_CHARACTERISTIC . ' pc', 'pc.product_id = p.product_id', 'inner')
                    -> join(self::TBL_PRODUCT_CHAR_VALUE . ' pav', 'pav.product_characteristic_value_id = pc.product_characteristic_value_id', 'inner')
                    -> whereIn('pc.product_characteristic_value_id', $charValueIds);
        }

        if (!empty($_GET['f_year'])) {
            //$sql -> where('pmv.model_year', $_GET['f_year']);
        }

        //        if (isset($data['brandTxt'])) {
        //            $sql -> like('b.brandTxt', $data['brandTxt'], 'both');
        //        }

        if (isset($data['nalichnost']) && !empty($data['nalichnost'])) {
            $sql -> where('p.nalichnost > 0');
        }

        // филтър по подкатегория ids
        if (!empty($data['subCategoryIds'])) {
            $sql -> whereIn('p.category_id', $data['subCategoryIds']);
        }

        // филтър по главна категория
        if (!empty($data['categoryRootId'])) {
            $sql -> where('p.categoryRoot_id', $data['categoryRootId']);
        }

        if (!empty($data['productIds'])) {
            $sql -> whereIn('p.product_id', $data['productIds']);
        }

        if (isset($data['rootCategoryIds']) || !empty($data['f_rootCatId'])) {
            $sql -> where('p.categoryRoot_id', $data['f_rootCatId']);
        }

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

        // филтър по промо продукти
        if (!empty($data['promo-badge'])) {
            $sql -> where('p.badge_index =3');
        }

        // филтър по фиксирана цена
        if (!empty($data['fixPrice'])) {
            $sql -> where('p.badge_index =4');
        }

        // филтър по име и колона
        if (!empty($data['searchName'])) {
            $productName = explode(' ', $data['searchName']);

            foreach ($productName as $pName) {
                $sql -> groupStart();
                $sql -> like('p.product_name', $pName, 'both')
                        -> orLike('p.oem', $pName, 'both')
                        -> orLike('p.upc', $pName, 'both')
                        -> orLike('p.ean', $pName, 'both')
                        -> orLike('p.kod', $pName, 'both')
                        -> orLike('p.kod_dostavchik', $pName, 'both');
                $sql -> groupEnd();
            }
            // $sql -> like('p.product_name', $data['searchName'], 'both');
        }

        if (!empty($data['to'])) {

            $sql -> groupStart()
                    -> groupStart()
                    -> where("pl.is_promo IS NULL AND pl.{$data['productPriceLevel']} >=", (float) $data['from'])
                    -> orGroupStart()
                    -> where("pl.is_promo =1 AND pl.cenaPromo >=", (float) $data['from'])
                    -> groupEnd()
                    -> groupEnd()
                    -> groupStart()
                    -> where("pl.{$data['productPriceLevel']} <=", (float) $data['to'] + 0.01)
                    -> orGroupStart()
                    -> where("pl.is_promo =1 AND pl.cenaPromo <=", (float) $data['to'] + 0.01)
                    -> groupEnd()
                    -> groupEnd()
                    -> orWhere("pl.{$data['productPriceLevel']} IS NULL")
                    -> groupEnd();
        }

        // експортиране на продукти от подкатегория
        if (isset($data['xlsExport'])) {

            $sql -> select([
                'p.product_name',
                'p.model',
                'CONCAT(UPPER(SUBSTRING(b.brandTxt,1,1)),LOWER(SUBSTRING(b.brandTxt,2))) AS brandTxt',
                '(CASE WHEN p.nalichnost = 0 THEN "не" ELSE "да" END) AS nalichnost',
                'pl.' . $data['productPriceLevel']
            ]);

            if ($data['productIds']) {
                $sql -> whereIn('p.product_id', $data['productIds']);
            }
        }

        if (!empty($data['dilarUrlId'])) {
            $sql -> join(self::TBL_SPECIAL . ' ul', 'FIND_IN_SET(p.product_id, ul.productsID)', 'inner');
            $sql -> where('ul.id', $data['dilarUrlId']);
        }

        // странициране
        if (isset($data['perPage'])) {
            $sql -> limit($data['perPage'], $data['offset']);
        }

        // сортировка
        if (isset($data['sort'])) {
            $sortParts = explode(' ', $data['sort']);
            if (!empty($sortParts[0])) {
                $sql -> orderBy($sortParts[0], $sortParts[1] ?? '', false);
            }
        }
        //dd($data);
        //dd($sql -> get() -> getResultArray());
        // dd($data);
        //dd($sql -> getCompiledSelect());

        return $sql -> get() -> getResultArray();
    }

    public function getProduct($productId) {

        $query = $this -> db -> table(self::TBL_PRODUCT . ' p')
                        -> select('p.*, c.category_name, sc.category_name AS subCategory_name,pl.*,b.*,pm.model,GROUP_CONCAT(DISTINCT pm2.model) AS additional_models')
                        -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                        -> join(self::TBL_BRAND . ' b', 'brand_id', 'left')
                        -> join(self::TBL_MODEL . ' pm', 'pm.model_id=p.model_id', 'left')
                        -> join(self::TBL_PRODUCT_MODEL_ADDIT_LINK . ' pam', 'pam.model_main_id=p.model_id', 'left')// Допълнителни модели
                        -> join(self::TBL_MODEL . ' pm2', 'pm2.model_id = pam.model_addit_id', 'left') // самите доп модели
                        // -> join(self::TBL_MODEL . ' pm2', 'pm2.model_id IN ( p.model2_id, p.model3_id)', 'left') // Допълнителни модели
                        -> join(self::TBL_CATEGORY . ' c', 'category_id', 'left')
                        -> join(self::TBL_CATEGORY . ' sc', 'sc.category_id = p.category_id', 'left')
                        // -> join(self::TBL_BRANDS . ' b', 'brand_id', 'left')
                        -> where('p.product_id', $productId)
                        -> groupBy('p.product_id') // заради GROUP_CONCAT е добра практика
                        -> get() -> getRow();

        return $query;
    }

    // свързани продукти
    // свързани продукти (за карти като "Нови продукти")
    public function get__relatedProducts($productJson = []) {
        if (empty($productJson)) {
            return [];
        }

        $decoded = json_decode($productJson, true);
        if (!is_array($decoded) || empty($decoded)) {
            return [];
        }

        // Поддържа стар формат: [1,2,3] и нов формат: [{"pid":"42531","t":"a"}, ...]
        $ids = [];
        foreach ($decoded as $item) {
            if (is_array($item)) {
                $rawId = $item['pid'] ?? $item['product_id'] ?? $item['id'] ?? null;
            } else {
                $rawId = $item;
            }

            $id = intval($rawId);
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        // махни дубликати, но запази реда
        $ids = array_values(array_unique($ids));
        if (!$ids) {
            return [];
        }

        $qb = $this -> db -> table(self::TBL_PRODUCT . ' p');

        $qb -> select([
            // от p (product)
            'p.product_id',
            'p.product_name',
            'p.image',
            'p.nalichnost',
            'p.badge_index',
            // от pl (ценови нива и промо)
            'pl.cenaKKC                 AS cenaKKC',
            // добави други нива ако ползваш: 'pl.cenaB2B AS cenaB2B', ...
            'pl.is_promo                AS is_promo',
            'pl.cenaPromo               AS cenaPromo',
            'pl.zavishena_zena          AS zavishena_zena',
            'pl.is_promoKl              AS is_promoKl',
            'pl.cenaPromoKl             AS cenaPromoKl',
            'pl.zavishenaKl_zena        AS zavishenaKl_zena',
            // индивидуални цени/списъци (СА В pl!)
            'pl.klientIds_to_cenaIndiv  AS klientIds_to_cenaIndiv',
            'pl.cenaIndiv               AS cenaIndiv',
        ]);

        $qb -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id = p.product_id', 'left');

        // видимост по сайт — копирано от get__all_product
        $qb -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id = p.product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd();

        $qb -> whereIn('p.product_id', $ids);

        // запази реда на ID-тата (MySQL/MariaDB)
        $qb -> orderBy('FIELD(p.product_id,' . implode(',', $ids) . ')', '', false);

        // безопасно при join-ове
        $qb -> groupBy('p.product_id');

        return $qb -> get() -> getResultArray();
    }

    // Вариации за PDP от _product.variation_products (JSON)
    public function get__variationProductsForPdp($variationJson = [], $baseProductId = 0) {
        $empty = [
            'results' => [],
            'baseVariation' => (object) [
                'has_baseVariation' => 0,
                'has_baseVariationVal' => 0,
                'var_count' => 0,
                'var_names' => '',
            ],
        ];

        if (empty($variationJson) && empty($baseProductId)) {
            return $empty;
        }

        $data = is_array($variationJson) ? $variationJson : (json_decode($variationJson, true) ?? []);
        if (!is_array($data)) {
            $data = [];
        }

        $filtered = [];
        foreach ($data as $idx => $item) {
            if (!is_array($item)) {
                continue;
            }

            $type = mb_strtolower((string) ($item['t'] ?? 'a'));
            if ($type === 'd') {
                continue;
            }

            $pid = intval($item['pid'] ?? 0);
            if ($pid <= 0) {
                continue;
            }

            $filtered[$pid] = intval($item['pos'] ?? $idx);
        }

        $basePid = intval($baseProductId);
        if ($basePid > 0 && !isset($filtered[$basePid])) {
            $filtered[$basePid] = -1;
        }

        if (empty($filtered)) {
            return $empty;
        }

        $pIds = array_keys($filtered);

        $baseVariation = $this -> db
            -> table(self::TBL_PRODUCT_VARIATION . ' pv')
            -> select([
                'IF(COUNT(pv.product_variation_id) > 0, 1, 0) AS has_baseVariation',
                'MIN(IF(pv.product_variation_value_id IS NOT NULL, 1, 0)) AS has_baseVariationVal',
                'COUNT(pv.product_variation_id) AS var_count',
                'GROUP_CONCAT(DISTINCT LOWER(pv.variation_name) ORDER BY LOWER(pv.variation_name) ASC SEPARATOR ",") AS var_names',
            ])
            -> where('pv.product_id', $basePid)
            -> where('pv.is_active', 1)
            -> groupBy('pv.product_id')
            -> get()
            -> getRow();

        if (!$baseVariation) {
            $baseVariation = $empty['baseVariation'];
        }

        $rows = $this -> db
            -> table(self::TBL_PRODUCT . ' p')
            -> select([
                'p.product_id',
                'p.product_name',
                'p.image',
                'p.nalichnost',
                'pl.cenaKKC AS cenaKKC',
                'pl.is_promo AS is_promo',
                'pl.cenaPromo AS cenaPromo',
                'pl.zavishena_zena AS zavishena_zena',
                'pl.is_promoKl AS is_promoKl',
                'pl.cenaPromoKl AS cenaPromoKl',
                'pl.zavishenaKl_zena AS zavishenaKl_zena',
                'pl.klientIds_to_cenaIndiv AS klientIds_to_cenaIndiv',
                'pl.cenaIndiv AS cenaIndiv',
                'IF(COUNT(pv.product_variation_id) > 0, 1, 0) AS has_variation',
                'MIN(IF(pv.product_variation_value_id IS NOT NULL, 1, 0)) AS has_variationVal',
                'COUNT(pv.product_variation_id) AS var_count',
                'GROUP_CONCAT(DISTINCT LOWER(pv.variation_name) ORDER BY LOWER(pv.variation_name) ASC SEPARATOR ",") AS var_names',
                'GROUP_CONCAT(DISTINCT CONCAT(TRIM(pv.variation_name), "::", IFNULL(pv.product_variation_value_id, 0), "::", IFNULL(pvv.product_variation_text, "")) ORDER BY LOWER(pv.variation_name) ASC, pvv.product_variation_text ASC SEPARATOR "||") AS variation_pairs',
            ])
            -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id = p.product_id', 'left')
            -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id = p.product_id', 'inner')
            -> join(self::TBL_PRODUCT_VARIATION . ' pv', 'pv.product_id = p.product_id AND pv.is_active = 1', 'left')
            -> join(self::TBL_PRODUCT_VARIATION_VALUE . ' pvv', 'pvv.product_variation_value_id = pv.product_variation_value_id', 'left')
            -> groupStart()
            -> where('ps.sp_site_id', $_ENV['app.siteId'])
            -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
            -> groupEnd()
            -> whereIn('p.product_id', $pIds)
            -> groupBy('p.product_id')
            -> get()
            -> getResultArray();

        if (empty($rows)) {
            return $empty;
        }

        $baseHasVariation = intval($baseVariation -> has_baseVariation ?? 0);
        $baseHasVariationVal = intval($baseVariation -> has_baseVariationVal ?? 0);
        $baseVarCount = intval($baseVariation -> var_count ?? 0);
        $baseVarNames = (string) ($baseVariation -> var_names ?? '');

        $baseStatusConfig = [
            '11' => ['class' => 'css-status-success', 'text' => 'Базов: ОК'],
            '10' => ['class' => 'css-status-warning', 'text' => 'Базов: Изисква избор на атрибут'],
            '00' => ['class' => 'css-status-danger', 'text' => 'Базов: Няма вариации'],
        ];

        $currentStatusConfig = [
            '11' => ['class' => 'css-status-success', 'text' => 'Налични вариации'],
            '10' => ['class' => 'css-status-warning', 'text' => 'Този продукт изисква избор на атрибут'],
            '00' => ['class' => 'css-status-danger', 'text' => 'Този продукт няма вариации'],
        ];

        $results = [];
        foreach ($rows as $row) {
            $hasVariation = intval($row['has_variation'] ?? 0);
            $hasVariationVal = intval($row['has_variationVal'] ?? 0);
            $curVarCount = intval($row['var_count'] ?? 0);
            $curVarNames = (string) ($row['var_names'] ?? '');

            $statusKey = (string) $baseHasVariation . (string) $baseHasVariationVal . (string) $hasVariation . (string) $hasVariationVal;
            $bKey = substr($statusKey, 0, 2);
            $cKey = substr($statusKey, 2, 2);

            $diffCount = $baseVarCount !== $curVarCount;
            $diffNames = $baseVarNames !== $curVarNames;

            $currentStatus = match (true) {
                $bKey === '00' => $baseStatusConfig['00'],
                $diffCount => [
                    'class' => 'css-status-warning',
                    'text' => sprintf(
                        'Броят вариации не съвпада (Базов: %d / Този: %d)',
                        $baseVarCount ?: 0,
                        $curVarCount ?: 0
                    ),
                ],
                $diffNames => [
                    'class' => 'css-status-warning',
                    'text' => 'Има разминаване в имената на вариациите спрямо базовия продукт',
                ],
                $bKey === '10' => [
                    'class' => 'css-status-warning',
                    'text' => $baseStatusConfig['10']['text'] . ($cKey !== '11' ? ' и ' . mb_strtolower($currentStatusConfig[$cKey]['text'] ?? '') : ''),
                ],
                default => $currentStatusConfig[$cKey] ?? ['class' => 'css-status-secondary', 'text' => 'Неопределено състояние'],
            };

            // FE показва само "зелени" вариации според подадената логика
            if (($currentStatus['class'] ?? '') !== 'css-status-success') {
                continue;
            }

            $attributes = [];
            $pairs = array_filter(explode('||', (string) ($row['variation_pairs'] ?? '')));
            foreach ($pairs as $pair) {
                $chunks = explode('::', $pair, 3);
                if (count($chunks) < 3) {
                    continue;
                }

                $attrName = trim((string) $chunks[0]);
                $valueId = intval($chunks[1] ?? 0);
                $valueText = trim((string) ($chunks[2] ?? ''));

                if ($attrName === '' || $valueId <= 0) {
                    continue;
                }

                $attrKey = mb_strtolower($attrName);
                $attributes[$attrKey] = [
                    'name' => $attrName,
                    'value_id' => $valueId,
                    'value_text' => $valueText,
                ];
            }

            $row['variation_attributes'] = $attributes;
            $row['variation_status_class'] = $currentStatus['class'] ?? '';
            $row['variation_status_text'] = $currentStatus['text'] ?? '';
            $row['variation_pos'] = $filtered[intval($row['product_id'])] ?? 999999;
            $results[] = $row;
        }

        if (empty($results)) {
            return $empty;
        }

        usort($results, function ($a, $b) {
            return intval($a['variation_pos'] ?? 0) <=> intval($b['variation_pos'] ?? 0);
        });

        return [
            'results' => $results,
            'baseVariation' => $baseVariation,
        ];
    }

    public function get__maxPrice($data = []) {

        $sql = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> selectMax("pl.{$data['productPriceLevel']}")
                -> join(self::TBL_BRAND . ' b', 'brand_id', 'left')
                -> join('(SELECT model_id,model FROM ' . self::TBL_MODEL . ' ) as pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd();

        // филтър по подкатегория
        if (!empty($data['subCategoryIds'])) {
            $sql -> whereIn('p.category_id', $data['subCategoryIds']);
        } elseif (isset($data['categoryId'])) {
            $sql -> where('p.category_id', $data['categoryId']);
        }

        // филтър по главна категория
        if (isset($data['categoryRootId'])) {
            $sql -> where('p.categoryRoot_id', $data['categoryRootId']);
        }

        // филтър по бранд
        if (isset($data['manufactureNames'])) {
            $sql -> whereIn('b.brandTxt', $data['manufactureNames']);
        }

        if (isset($data['brandId'])) {
            $brandIds = is_array($data['brandId'])
                ? $data['brandId']
                : preg_split('/[_,]+/', (string) $data['brandId']);
            $brandIds = array_values(array_filter(array_map('intval', $brandIds)));

            if (!empty($brandIds)) {
                count($brandIds) === 1
                    ? $sql -> where('p.brand_id', $brandIds[0])
                    : $sql -> whereIn('p.brand_id', $brandIds);
            }
        }

        if (isset($data['brandTxt'])) {
            $sql -> like('b.brandTxt', $data['brandTxt'], 'both');
        }

        // филтър по промоция
        if (isset($data['promo'])) {
            $sql -> where('pl.zavishena_zena !="" and pl.is_promo = 1');
        }

        // филтър по име и колона
        if (isset($data['searchName'])) {

            $sql -> groupStart()
                    -> like('p.product_name', $data['searchName'], 'both')

                    //-> orLike('p.model', $data['searchName'])
                    -> groupEnd();
        }

        //dd($sql -> getCompiledSelect());
        //dd($sql -> get() -> getRow());

        return $sql -> get() -> getRow();
    }

    public function countProductsBySubCategory($productPriceLevel = null) {

        $result = [];

        $query = $this -> db -> table(self::TBL_PRODUCT . ' p')
                        -> select('category_id, COUNT(*) as count')
                        -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                        -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                        -> groupStart()
                        -> where('ps.sp_site_id', $_ENV['app.siteId'])
                        -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                        -> groupEnd()
                        -> where('category_id IS NOT NULL')
                        -> groupStart()
                        -> where("pl.{$productPriceLevel} IS NOT NULL")
                        -> orwhere('pl.zavishena_zena !="" and pl.is_promo = 1')
                        -> groupEnd()

                        // -> where("pl.{$productPriceLevel} IS NOT NULL")
                        -> groupBy('category_id')
                        -> get() -> getResultArray();

        //dd($result);
        // dd(array_column($query,'count','category_id'));

        return array_column($query, 'count', 'category_id');
    }

    // извличане на х-ки на single продукт

    public function getCategoryAttribute($category_id = null, object $data = null) {

        $category_id = $data -> category_id ?? $category_id;

        $r = $this -> db -> table(self::TBL_CATEGORY_ATTRIBUTE)
                        -> select([
                            'category_characteristic_id',
                            'value as category_characteristic_value'
                        ])
                        -> where('category_id', $category_id)

                        // -> where('is_visible', 1)
                        -> orderBy('ISNULL(category_char_position)')
                        -> orderBy('category_char_position', 'ASC')
                        -> get() -> getResultArray();

        return $r;
    }

    public function getProductCharacteristics($productId) {

        return $this -> db -> table(self::TBL_PRODUCT_CHARACTERISTIC . ' pa')
                        -> join(self::TBL_PRODUCT_CHAR_VALUE . ' pav', 'product_characteristic_value_id', 'left')
                        -> where('pa.product_id', $productId)
                        -> get() -> getResultArray();
    }

    // futured, on_sale, top_rated

    public function getProductByType($type = null) {

        $sql = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> where('pl.zavishena_zena !="" and pl.is_promo = 1');

        return $sql -> get() -> getResultArray();
    }

    // futured, on_sale, top_rated

    public function get_promo_all_product() {

        $sql = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id=p.product_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id=p.product_id', 'inner')
                -> groupStart()
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> groupEnd()
                -> where('zavishena_zena !=""')
                -> where('is_promo', '1');

        return $sql -> get() -> getResultArray();
    }

    // Метод за вземане на години по марка и модел за йерархичните dropdown-и
    public function getYearsByBrandAndModel($brandId, $model, $productPriceLevel) {
        $query = $this -> db -> table(self::TBL_PRODUCT . ' p')
                -> select('DISTINCT YEAR(p.created_at) as year')
                -> join(self::TBL_BRAND . ' b', 'p.brand_id = b.brand_id', 'inner')
                -> join(self::TBL_MODEL . ' pm', 'pm.model_id=p.model_id', 'left')
                -> join(self::TBL_PRODUCT_PRICE_LEVEL . ' pl', 'pl.product_id = p.product_id', 'left')
                -> join(self::TBL_PRODUCT_SITES . ' ps', 'ps.product_id = p.product_id', 'inner')
                -> where('ps.sp_site_id', $_ENV['app.siteId'])
                -> whereIn('ps.visibility_type', session() -> has('user_id') ? [0, 2] : [0, 1])
                -> where('p.brand_id', $brandId)
                -> where('pm.model', $model)
                -> where("pl.{$productPriceLevel} IS NOT NULL")
                -> orderBy('year', 'DESC');

        $results = $query -> get() -> getResultArray();

        $years = [];
        foreach ($results as $row) {
            if (!empty($row['year']) && $row['year'] > 1990) { // Филтрираме валидни години
                $years[] = [
                    'value' => $row['year'],
                    'text'  => $row['year']
                ];
            }
        }

        return $years;
    }
}
