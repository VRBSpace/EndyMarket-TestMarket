<?php

namespace App\Controllers;

use App\Models\MODEL__category;
use App\Models\MODEL__product;
use App\Models\MODEL__brand;
use App\Models\MODEL__filter;
use App\Models\MODEL__cenovaLista;
use App\Models\MODEL__setings;

class Home extends BaseController {

    public function __construct() {
        parent::__construct();

        helper('file');
   
        $this -> MODEL__setings     = new MODEL__setings();
        $this -> MODEL__product     = new MODEL__product();
        $this -> MODEL__cenovaLista = new MODEL__cenovaLista();
        $this -> MODEL__category    = new MODEL__category();
        $this -> MODEL__brand       = new MODEL__brand();
        $this -> MODEL__filter      = new MODEL__filter();
     
    }

    public function index() {
        // $new_products = [];
        // $new_productsArg = [];
        
        // $new_productsArg = [
        //     // 'categoryId'        => $categoryId,
        //     // 'categoryRootId'    => $categoryRootId,
        //     // 'searchName'        => $searchName,
        //     // 'promo'             => $promo,
        //     // 'sort'              => $sort,
        //     // 'to'                => $f_priceRange[1] ?? null,
        //     // 'from'              => $f_priceRange[0] ?? null,
        //     // 'page'              => $page,
        //     // 'nalichnost'        => $f_isInstock,
        //     // 'dilarUrlId'        => $dilarUrlId,
        //     // 'brandTxt'          => $brandTxt ?? null,
        //     // 'models'            => $f_models ?? null,
        //     // 'subCategoryIds'    => $f_subCatIds,
        //     // 'brandId'           => $brandId ?? null,
        //     // 'productPriceLevel' => $productPriceLevel,
        //     // 'offset'            => $offset,
        //     'perPage'           => 12
        // ];
     
        $brands         = $this -> MODEL__brand -> get__brands();
        $product_models = $this -> MODEL__filter -> get__brandsModels();
        $sales_products = $this -> MODEL__product -> getProductByType('is_onsale');

        // На начална страница всички виждат публичната (гост) версия
        $productPriceLevel = 'cenaKKC';
        $latest_products = [];

        // Взимаме подреден списък с ID от оферта _ofer_special.id = 8
        $specialOfferId = 8;
        $specialRow     = db_connect() -> table('_ofer_special') -> where('id', $specialOfferId) -> get() -> getRowArray();
        $specialIds     = array_values(array_filter(array_map('intval', preg_split('/[^0-9]+/', $specialRow['productsID'] ?? ''))));

        if (!empty($specialIds)) {
            $latest_products = $this -> MODEL__product -> get__all_product([
                'productIds'        => $specialIds,
                'productPriceLevel' => $productPriceLevel,
                'sort'              => 'FIELD(p.product_id,' . implode(',', $specialIds) . ')',
                'perPage'           => count($specialIds),
                'offset'            => 0
            ]);
        }

        // Получаване на промо продукти (по badge)
        $promo_products = $this -> MODEL__product -> get__all_product([
            'perPage' => 15,
            'promo-badge' => true,
            'sort' => 'p.product_id DESC',
            'productPriceLevel' => $productPriceLevel,
            'offset' => 0
        ]);

        // Получаване на намалени продукти от ценова листа _ofer_special.id = 10
        $sale_products = [];
        $promoOfferId  = 10;
        $promoRow      = db_connect() -> table('_ofer_special') -> where('id', $promoOfferId) -> get() -> getRowArray();
        $promoIds      = array_values(array_filter(array_map('intval', preg_split('/[^0-9]+/', $promoRow['productsID'] ?? ''))));

        if (!empty($promoIds)) {
            $sale_products = $this -> MODEL__product -> get__all_product([
                'productIds'        => $promoIds,
                'productPriceLevel' => $productPriceLevel,
                'sort'              => 'FIELD(p.product_id,' . implode(',', $promoIds) . ')',
                'perPage'           => count($promoIds),
                'offset'            => 0
            ]);
        }

        // Допълнителни категории
        $categorySections = [
            ['key' => 'cosmetics', 'title' => 'Козметика', 'categoryRootId' => 771, 'categoryId' => 771],
            ['key' => 'garden_flowers', 'title' => 'Градина и цветя', 'categoryRootId' => 766, 'categoryId' => 766],
            ['key' => 'hair_care', 'title' => 'Грижа за коса', 'categoryRootId' => 521, 'categoryId' => 521],
            ['key' => 'home_needs', 'title' => 'Домашни потреби', 'categoryRootId' => 767, 'categoryId' => 767],
            ['key' => 'for_kids', 'title' => 'За детето', 'categoryRootId' => 768, 'categoryId' => 768],
            ['key' => 'pc_periphery', 'title' => 'Компютърна периферия', 'categoryRootId' => 772, 'categoryId' => 772],
            ['key' => 'furniture', 'title' => 'Мебели', 'categoryRootId' => 773, 'categoryId' => 773],
            ['key' => 'flooring', 'title' => 'Подови настилки', 'categoryRootId' => 775, 'categoryId' => 775],
            ['key' => 'souvenirs', 'title' => 'Сувенири и декорация', 'categoryRootId' => 778, 'categoryId' => 778],
            ['key' => 'tableware', 'title' => 'Съдове за хранене', 'categoryRootId' => 186, 'categoryId' => 186],
        ];

        // събиране на целите клонове за всяка секция (родител + всички подкатегории)
        $allCategories = $this -> MODEL__category -> getCategories();
        $byParent      = [];
        foreach ($allCategories as $_cat) {
            $byParent[$_cat['parent_id']][] = $_cat;
        }

        $categoryProducts = [];
        foreach ($categorySections as $_section) {
            $branchIds = $this -> collectCategoryBranchIds($_section['categoryId'], $byParent);
            $categoryProducts[$_section['key']] = $this -> MODEL__product -> get__all_product([
                'perPage' => 15,
                'subCategoryIds' => $branchIds, // включва родителя и наследниците
                'categoryId' => null, // да не ограничаваме само до един id
                'sort' => 'p.product_id DESC',
                'productPriceLevel' => $productPriceLevel,
                'offset' => 0
            ]);
        }

        $productModelsTree  = $this -> buildModelByBrand($brands, $product_models);
        $slideshow_images   = $this -> MODEL__setings -> get__slideshow_images();
        $banerBlock1_images = $this -> MODEL__setings -> get__banerBlock1_images();

        $data               = [
            'title'              => '',
            'addGlobalJS'        => $this -> addGlobalJS(),
            'addJS'              => $this -> addJS(),
            'addCSS'             => $this -> addCSS(),
            'latest_products'    => $latest_products,
            'promo_products'     => $promo_products,
            'sale_products'      => $sale_products,
            'categorySections'   => $categorySections,
            'categoryProducts'   => $categoryProducts,
            'sales_products'     => $sales_products,
            'productModelsTree'  => array_column($productModelsTree, null, 'brand_id'),
            'slideshow_images'   => $slideshow_images ?? [],
            'banerBlock1_images' => $banerBlock1_images ?? [],
            '_sizeTitleProduct'  => 'font-size: 14px;', // Добавяме променливата за стилизиране на заглавията
            // views
            'views'              => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        // $this -> cachePage(30);
        return view("{$this -> theme}/home/VIEW__home-index", $data);
    }

    // зареждане на css файлове в footer на html страноцата
    // ====================================================
    public function addCSS() {
        $page   = 'css/pages/';
        $vendor = 'vendor';

        return [
            "$vendor/slideshowSlider/imgs/style",
            "$page/global",
            "$page/home",
        ];
    }

    // зареждане на js файлове в footer на html страноцата
    // ====================================================
    public function addJS($isList = null) {
        $theme  = 'js/theme/electro';
        $vendor = 'vendor';

        // JS Implementing Plugins 
        $plugins = [
            "$theme/loadPlugins",
            "$vendor/slideshowSlider/switchable"
        ];

        $global = ["$theme/global"];

        $default = [
            "$theme/pages/home/slideShowSlider",
            "$theme/pages/cart/cart"
        ];

        $modals = [
            "$theme/pages/home/home_popupForms",
            "$theme/quickViewProduct"
        ];

        return array_merge($plugins, $global, $default, $modals);
    }
}
