<?php

namespace App\Controllers;

namespace App\Controllers;

use App\Models\MODEL__category;
use App\Models\MODEL__product;
use App\Models\MODEL__brand;
use App\Models\MODEL__cenovaLista;

class Gdpr extends BaseController {

    public function index() {
        return redirect() -> back();

        $categories_instance = new MODEL__category();
        $categories          = $categories_instance -> getCategories();
        $category_tree       = $this -> buildCategoryTree(null, $categories);
        $brandModel          = new MODEL__brand();
        $brands              = $brandModel -> getBrands();

        $res               = [];
        $cenovaListaModel  = new MODEL__cenovaLista();
        $allCenovaLista    = $cenovaListaModel -> getAllCenovaLista();
        $productPriceLevel = $this -> getProductPriceLevel();
        $productModel      = new MODEL__product();

        foreach ($allCenovaLista as $cenovaLista) {
            // тази ценова листа няма продукти
            if ($cenovaLista['productsID'] == '') {
                break;
            }
            $productIds = explode(',', $cenovaLista['productsID']);
            foreach ($productIds as $productId) {
                $product                           = $productModel -> getProduct($productId);
                $product -> price                  = $this -> getProductRightPrice($productId, $productPriceLevel);
                $res[$cenovaLista['offersName']][] = $product;
            }
        }

        $productModel      = new MODEL__product();
        $featured_products = $productModel -> where('is_featured', 1) -> findAll();
        $sales_products    = $productModel -> where('is_onsale', 1) -> findAll();
        $toprated_products = $productModel -> where('is_toprated', 1) -> findAll();
        $cartSession       = $this -> getSessionProperty();

        $data = [
            'title'                   => 'Valpers',
            'categories'              => $category_tree,
            'allCenovaLista'          => $allCenovaLista,
            'res'                     => $res,
            'featured_products'       => $featured_products,
            'sales_products'          => $sales_products,
            'toprated_products'       => $toprated_products,
            'brands'                  => $brands,
            'cartSession'             => $cartSession,
            'cartSessionProductsList' => $cartSession['products'],
        ];

        return view("{$this -> theme}/policy/gdpr1", $data);

        $cenovaListaModel = new MODEL__cenovaLista();
        $allCenovaLista   = $cenovaListaModel -> getAllCenovaLista();
    }

    public function policy(): string {

        $categories_instance = new MODEL__category();
        $categories          = $categories_instance -> getCategories();
        $category_tree       = $this -> buildCategoryTree(null, $categories);
        $brandModel          = new MODEL__brand();
        $brands              = $brandModel -> getBrands();

        $res               = [];
        $cenovaListaModel  = new MODEL__cenovaLista();
        $allCenovaLista    = $cenovaListaModel -> getAllCenovaLista();
        $productPriceLevel = $this -> getProductPriceLevel();
        $productModel      = new MODEL__product();

        foreach ($allCenovaLista as $cenovaLista) {
            // тази ценова листа няма продукти
            if ($cenovaLista['productsID'] == '') {
                break;
            }
            $productIds = explode(',', $cenovaLista['productsID']);
            foreach ($productIds as $productId) {
                $product                           = $productModel -> getProduct($productId);
                $product -> price                  = $this -> getProductRightPrice($productId, $productPriceLevel);
                $res[$cenovaLista['offersName']][] = $product;
            }
        }

        $cenovaListaModel = new MODEL__cenovaLista();
        $allCenovaLista   = $cenovaListaModel -> getAllCenovaLista();

        $productModel      = new MODEL__product();
        $featured_products = $productModel -> where('is_featured', 1) -> findAll();
        $sales_products    = $productModel -> where('is_onsale', 1) -> findAll();
        $toprated_products = $productModel -> where('is_toprated', 1) -> findAll();
        $cartSession       = $this -> getSessionProperty();

        $data = [
            'title'                   => 'Valpers',
            'categories'              => $category_tree,
            'allCenovaLista'          => $allCenovaLista,
            'res'                     => $res,
            'featured_products'       => $featured_products,
            'sales_products'          => $sales_products,
            'toprated_products'       => $toprated_products,
            'brands'                  => $brands,
            'cartSession'             => $cartSession,
            'cartSessionProductsList' => $cartSession['products'],
        ];

        return view("{$this -> theme}/policy/privacy_policy", $data);
    }
}
