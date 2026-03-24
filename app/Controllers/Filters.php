<?php

namespace App\Controllers;

use \App\Controllers\BaseController;
use \App\Models\MODEL__product;
use \App\Models\MODEL__global;
use App\Models\MODEL__filter;
use \App\Libraries\Pagination;

class Filters extends BaseController {

    public function __construct() {
        parent::__construct();

        $this -> Pagination_lib = new Pagination();
        $this -> MODEL__filter  = new MODEL__filter();
        $this -> MODEL__global  = new MODEL__global();
        $this -> MODEL__product = new MODEL__product();
    }

    // извл на данните при кликване на атрибутите за подкатегории
    public function index($categoryId = null) {
        $perPage = $this -> request -> getVar('perPage') ?? 20;
        $page    = max(1, $this -> request -> getVar('page'));
        $offset  = max(0, ((int) $page - 1) * $perPage);
        $sort    = $this -> request -> getVar('sort');

        $productAttrIds      = $this -> request -> getVar('productAttrIds');
        $productCharValueIds = $this -> request -> getVar('productCharValueIds');
        $productModels       = $this -> request -> getVar('productModels');
        $categoryRootId      = $this -> request -> getVar('categoryRootId');
        $rootCategoryIds     = $this -> request -> getVar('rootCategoryIds');
        $subCategoryIds      = $this -> request -> getVar('subCategoryIds');

        $is_promo         = $this -> request -> getVar('is_promo');
        $manufactureNames = $this -> request -> getVar('manufactureNames');
        $brandTxt         = $this -> request -> getVar('brandTxt');
        $searchName       = $this -> request -> getVar('searchName');
        $nalichnost       = $this -> request -> getVar('nalichnost');

        $from = $this -> request -> getVar('from') ?? 0;
        $to   = $this -> request -> getVar('to');
        // $brandId           = $this -> request -> getVar('brandId');
        //$model             = $this -> request -> getVar('model');

        // Филтрите в shop са еднакви за всички (публична версия)
        $productPriceLevel = 'cenaKKC';

        // масив с атрибути на продукти спрямо id
        if (!empty($productAttrIds)) {
            $productAttrIds = array_unique(array_merge(...array_map(fn($item) => explode(',', $item), $productAttrIds)));
        }

        if (!empty($productCharValueIds)) {
            $productCharValueIds = array_reduce($productCharValueIds, function ($result, $id) {
                $result[$id] = $id;
                return $result;
            }, []);
        }

        $subCategoryAttr = $this -> MODEL__filter -> get__subCategoryAttr($categoryId);
        $productAttr     = $this -> MODEL__filter -> get__productAttr(
                [
                    'productCharValueIds' => $productCharValueIds,
                    'productAttrIds'      => $productAttrIds,
                    'productPriceLevel'   => $productPriceLevel,
                    'subCategoryAttr'     => $subCategoryAttr,
                    'manufactureNames'    => $manufactureNames
        ]);

        $commonArg = [
            'promo'               => $is_promo ?? null,
            'searchName'          => $searchName ?? null,
            'categoryId'          => empty($categoryId) ? null : $categoryId,
            'categoryRootId'      => $categoryRootId,
            'rootCategoryIds'     => $rootCategoryIds ?? null,
            'subCategoryIds'      => $subCategoryIds ?? null,
            'productCharValueIds' => $productCharValueIds,
            'subCategoryAttr'     => $subCategoryAttr,
            'productAttrIds'      => $productAttrIds ?? null,
            'manufactureNames'    => $manufactureNames ?? null,
            'productModels'       => $productModels,
            'brandTxt'            => $brandTxt ?? null,
            'sort'                => $sort ?? null,
            'nalichnost'          => $nalichnost ?? null,
            'from'                => $from,
            'to'                  => $to ?? null,
            'productPriceLevel'   => $productPriceLevel,
            'offset'              => $offset,
            'page'                => $page,
            'perPage'             => $perPage,
        ];

        $products = $this -> MODEL__product -> get__all_product($commonArg);

        $brands = $this -> MODEL__filter -> get__brands(
                [
                    'searchName'          => $searchName,
                    'categoryRootId'      => $categoryRootId,
                    'categoryId'          => $categoryId,
                    'productPriceLevel'   => $productPriceLevel,
                    'productCharValueIds' => $productCharValueIds,
                    'productAttrIds'      => $productAttrIds
                ]
        );

        $countTotalProducts = $this -> MODEL__global -> count__all_product($commonArg);
        $pagination         = $this -> Pagination_lib -> generate(['total_pages' => ceil($countTotalProducts / max(1, $perPage)),] + $commonArg);

        $json = [
            'brands'             => $brands,
            'products'           => $products,
            'productAttr'        => $productAttr,
            'productPriceLevel'  => $productPriceLevel,
            'pagination'         => $pagination,
            'countShowProducts'  => count($products),
            'countTotalProducts' => $countTotalProducts
        ];

        $view_brands   = view("{$this -> theme}/shop/filter/VIEW__shop-filter-manufacture", ['brands' => $brands, 'filter_categoryId' => empty($categoryId) ? null : $categoryId]);
        $view_prodicts = view("{$this -> theme}/shop/VIEW__shop-productsGrid", $json);
        $view_attr     = view("{$this -> theme}/shop/filter/VIEW__shop-filter-productAttr", [
            'productAttr'       => $productAttr,
            'subCategoryAttr'   => $subCategoryAttr,
            'filter_categoryId' => empty($categoryId) ? null : $categoryId
        ]);

        $matchedTable = '';
        if (preg_match('/<div id="js-filter-productAttr"([\s\S]*)>(.*?)<\/div>/s', $view_attr, $matches)) {
            $matchedTable = $matches[0];
        }

        //return $this -> response -> setJSON(view('shop/VIEW__shop-productsList', $json));
        return json_encode(['brands' => $view_brands, 'products' => $view_prodicts, 'productAttr' => trim($matchedTable)]);
    }
}
