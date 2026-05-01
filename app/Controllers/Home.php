<?php

namespace App\Controllers;

use App\Models\MODEL__category;
use App\Models\MODEL__product;
use App\Models\MODEL__brand;
use App\Models\MODEL__filter;
use App\Models\MODEL__cenovaLista;
use App\Models\MODEL__setings;

class Home extends BaseController {

    private const HOME_CACHE_TTL = 600;

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
        // На начална страница всички виждат публичната (гост) версия
        $productPriceLevel = 'cenaKKC';

        // Допълнителни категории
        $categorySections = [
            ['key' => 'cosmetics', 'title' => 'Козметика', 'categoryRootId' => 771, 'categoryId' => 771, 'offerId' => 11],
            ['key' => 'garden_flowers', 'title' => 'Градина и цветя', 'categoryRootId' => 766, 'categoryId' => 766, 'offerId' => 12],
            ['key' => 'hair_care', 'title' => 'Грижа за коса', 'categoryRootId' => 521, 'categoryId' => 521, 'offerId' => 13],
            ['key' => 'home_needs', 'title' => 'Домашни потреби', 'categoryRootId' => 767, 'categoryId' => 767, 'offerId' => 14],
            ['key' => 'for_kids', 'title' => 'За детето', 'categoryRootId' => 768, 'categoryId' => 768, 'offerId' => 15],
            ['key' => 'pc_periphery', 'title' => 'Компютърна периферия', 'categoryRootId' => 772, 'categoryId' => 772, 'offerId' => 16],
            ['key' => 'furniture', 'title' => 'Мебели', 'categoryRootId' => 773, 'categoryId' => 773, 'offerId' => 17],
            ['key' => 'flooring', 'title' => 'Подови настилки', 'categoryRootId' => 775, 'categoryId' => 775, 'offerId' => 18],
            ['key' => 'souvenirs', 'title' => 'Сувенири и декорация', 'categoryRootId' => 778, 'categoryId' => 778, 'offerId' => 19],
            ['key' => 'tableware', 'title' => 'Съдове за хранене', 'categoryRootId' => 186, 'categoryId' => 186, 'offerId' => null],
        ];

        $cacheKey = 'home_index_' . $productPriceLevel;
        $homeData = service('cache') -> get($cacheKey);

        if ($homeData === null) {
            $homeData = $this -> buildHomePageData($productPriceLevel, $categorySections);
            service('cache') -> save($cacheKey, $homeData, self::HOME_CACHE_TTL);
        }

        $data               = [
            'title'              => '',
            'addGlobalJS'        => $this -> addGlobalJS(),
            'addJS'              => $this -> addJS(),
            'addCSS'             => $this -> addCSS(),
            'categorySections'   => $categorySections,
            '_sizeTitleProduct'  => 'font-size: 14px;', // Добавяме променливата за стилизиране на заглавията
            // views
            'views'              => $this -> get_views(),
        ];

        foreach ($homeData as $key => $val) {
            $data[$key] = $val;
        }

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

    private function buildHomePageData(string $productPriceLevel, array $categorySections): array {
        $brands         = $this -> MODEL__brand -> get__brands();
        $product_models = $this -> MODEL__filter -> get__brandsModels();

        $offerIds = [8, 10];
        foreach ($categorySections as $_section) {
            $offerId = (int) ($_section['offerId'] ?? 0);
            if ($offerId > 0) {
                $offerIds[] = $offerId;
            }
        }

        $offerProductsMap = $this -> getOfferProductsMap(array_values(array_unique($offerIds)), $productPriceLevel);

        $latest_products = $offerProductsMap[8] ?? [];
        $sale_products   = $offerProductsMap[10] ?? [];

        $allCategories = $this -> MODEL__category -> getCategories();
        $byParent      = [];
        foreach ($allCategories as $_cat) {
            $byParent[$_cat['parent_id']][] = $_cat;
        }

        $categoryProducts     = [];
        $categorySectionItems = [];
        foreach ($categorySections as $_section) {
            $sectionKey = $_section['key'];
            $offerId    = (int) ($_section['offerId'] ?? 0);

            if ($offerId > 0 && !empty($offerProductsMap[$offerId])) {
                $categoryProducts[$sectionKey] = $offerProductsMap[$offerId];
            } else {
                $branchIds = $this -> collectCategoryBranchIds($_section['categoryId'], $byParent);
                $categoryProducts[$sectionKey] = $this -> MODEL__product -> get__all_product([
                    'perPage'           => 15,
                    'subCategoryIds'    => $branchIds,
                    'categoryId'        => null,
                    'sort'              => 'p.product_id DESC',
                    'productPriceLevel' => $productPriceLevel,
                    'offset'            => 0
                ]);
            }

            $categorySectionItems[$sectionKey] = array_slice($categoryProducts[$sectionKey] ?? [], 0, 15);
        }

        $banerBlock1_images = $this -> MODEL__setings -> get__banerBlock1_images();

        return [
            'latest_products'     => $latest_products,
            'latestProducts'      => array_slice($latest_products, 0, 10),
            'sale_products'       => $sale_products,
            'discountProducts'    => array_slice($sale_products, 0, 15),
            'categoryProducts'    => $categoryProducts,
            'categorySectionItems'=> $categorySectionItems,
            'productModelsTree'   => array_column($this -> buildModelByBrand($brands, $product_models), null, 'brand_id'),
            'slideshow_images'    => $this -> MODEL__setings -> get__slideshow_images() ?? [],
            'banerBlock1_images'  => $banerBlock1_images ?? [],
            'categoryBannerItems' => $this -> buildHomeCategoryBannerItems($banerBlock1_images ?? []),
        ];
    }

    private function getOfferProductsMap(array $offerIds, string $productPriceLevel): array {
        if (empty($offerIds)) {
            return [];
        }

        $rows = db_connect()
            -> table('_ofer_special')
            -> select('id, productsID')
            -> whereIn('id', $offerIds)
            -> get()
            -> getResultArray();

        $productsMap = [];
        foreach ($rows as $row) {
            $ids = $this -> parseOfferProductIds($row['productsID'] ?? '');
            if (empty($ids)) {
                $productsMap[(int) $row['id']] = [];
                continue;
            }

            $productsMap[(int) $row['id']] = $this -> MODEL__product -> get__all_product([
                'productIds'        => $ids,
                'productPriceLevel' => $productPriceLevel,
                'sort'              => 'FIELD(p.product_id,' . implode(',', $ids) . ')',
                'perPage'           => count($ids),
                'offset'            => 0
            ]);
        }

        return $productsMap;
    }

    private function parseOfferProductIds(string $productsIdText): array {
        return array_values(array_filter(array_map('intval', preg_split('/[^0-9]+/', $productsIdText))));
    }

    private function buildHomeCategoryBannerItems(array $banerBlock1Images): array {
        $portalSettings = $this -> get_portalSettings();
        $funcSettings   = json_decode($portalSettings['func']['text'] ?? '[]', true);
        $sort           = !empty($funcSettings['sort']) ? '&sort=' . str_replace('price', 'cenaKKC', $funcSettings['sort']) : '';
        $banerItems = [];

        if (!empty($banerBlock1Images)) {
            $indexed = array_column($banerBlock1Images, null, 'key');

            foreach ($indexed as $key => $banner) {
                if ($key === 'home_banerBlock1Dilar' || empty($banner['text'])) {
                    continue;
                }

                $_json = json_decode($banner['text']);
                if (!is_array($_json)) {
                    continue;
                }

                foreach ($_json as $image) {
                    $banerItems[] = [
                        'src'  => ($_ENV['app.imagePortalDir'] ?? '') . ($image -> img ?? ''),
                        'link' => !empty($image -> url) ? $image -> url . $sort : '#',
                    ];
                }
            }
        }

        return array_slice($banerItems, 3, 8);
    }
}
