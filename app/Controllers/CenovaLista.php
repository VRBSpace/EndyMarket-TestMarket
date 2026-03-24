<?php

namespace App\Controllers;

use \App\Controllers\BaseController;
use App\Models\MODEL__cenovaLista;

class CenovaLista extends BaseController {

    public function getCenovaListaById($cenovaListaId) {

        // ако потребителя няма профил не може да вижда ценова листа
        $sessionAccountId = $this -> getSessionAccount();
        if (empty($sessionAccountId)) {
            return redirect() -> to(route_to('Account-index'));
        }

        $cenovaListaModel = new MODEL__cenovaLista();

        $cenovaLista       = $cenovaListaModel -> getCenovaListaById($cenovaListaId);
        $productPriceLevel = $this -> getResolvedProductPriceLevel();

        if (!empty($cenovaLista -> productsID)) {
            $productIds = explode(',', $cenovaLista -> productsID);
            $product    = $cenovaListaModel -> get__products_fromCenova(['productIds' => $productIds, 'productPriceLevel' => $productPriceLevel]);
        }

        $this -> breadcrumb -> add('<i class="fa fa-home"></i>');
        $this -> breadcrumb -> add('Ценови листи');

        $klientIds_to_cenaIndiv = explode(',', array_column($product, 'klientIds_to_cenaIndiv')[0]);
        $cenaIndivValues = array_filter(array_column($product, 'cenaIndiv')); // ako има индивидуална цена
        $maxPrice = (!empty($cenaIndivValues) && in_array($sessionAccountId, $klientIds_to_cenaIndiv)) ? $cenaIndivValues : array_column($product, $productPriceLevel);

        $data = [
            'title'             => 'Valpers',
            'addGlobalJS'       => $this -> addGlobalJS(),
            'addJS'             => $this -> addJS(),
            'addCSS'            => $this -> addCSS(),
            'breadcrumbs'       => $this -> breadcrumb -> render(),
            'productPriceLevel' => $productPriceLevel,
            'res'               => $product,
            'maxPrice'          => !empty($maxPrice) ? max($maxPrice) : 0,
            'cenovaListaName'   => $cenovaLista -> offersName,
            'cenovaListaId'     => $cenovaLista -> id,
            // views
            'views'             => $this -> get_views(),
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return view("{$this -> theme}/cenovaLista/VIEW__cenovaLista", $data);
    }

    // ====================================================
    public function filter($id = null) {

        $productIds = explode(',', $this -> request -> getVar('productIds'));
        $nalichnost = $this -> request -> getVar('nalichnost');
        $searchName = $this -> request -> getVar('searchName');
        $sort       = $this -> request -> getVar('sort') ?? null;
        $from       = $this -> request -> getVar('from') ?? 0;
        $to         = $this -> request -> getVar('to');

        $nalichnost        = empty($nalichnost) ? null : $nalichnost;
        $searchName        = empty($searchName) ? null : $searchName;
        $sort              = empty($sort) ? null : $sort;
        $productPriceLevel = $this -> getResolvedProductPriceLevel();

        $url_params = [
            'productPriceLevel' => $productPriceLevel,
            'productIds'        => $productIds,
            'nalichnost'        => $nalichnost,
            'sort'              => $sort,
            'searchName'        => $searchName,
            'from'              => $from,
            'to'                => $to,
        ];

        $cenovaListaModel = new MODEL__cenovaLista();
        $cenovaLista      = $cenovaListaModel -> getCenovaListaById($id);

        $response = $cenovaListaModel -> get__products_fromCenova($url_params);
        $maxPrice = empty($response) ? $to : max(array_column($response, $productPriceLevel));

        $data = [
            'title'             => '',
            'addGlobalJS'       => $this -> addGlobalJS(),
            'addJS'             => $this -> addJS(),
            'addCSS'            => $this -> addCSS(),
            'breadcrumbs'       => $this -> breadcrumb -> render(),
            'productPriceLevel' => $productPriceLevel,
            'res'               => $response,
            'maxPrice'          => $maxPrice,
            'cenovaListaName'   => $cenovaLista -> offersName,
            'cenovaListaId'     => $cenovaLista -> id
        ];

        foreach ($this -> global() as $key => $val) {
            $data[$key] = $val;
        }

        return json_encode(
                [
                    'from' => $from,
                    'to'   => $to,
                    'view' => view($this -> get_views()['cenovaLista-products'], $data)]);
    }

    // зареждане на css файлове в footer на html страноцата
    // ====================================================
    public function addCSS() {
        $page = 'css/pages/';

        return [
            "$page/global"
        ];
    }

    // зареждане на js файлове в footer на html страноцата
    // ====================================================
    public function addJS($isList = null) {
        $theme = 'js/theme/electro';

        $plugins = ["$theme/loadPlugins"];

        $global = ["$theme/global"];

        $default = [
            "$theme/pages/cart/cart",
            "$theme/pages/shop/xls_exportProducts",
            "$theme/pages/cenovaLista/cenovaLista"
        ];

        $modals = [];

        return array_merge($plugins, $global, $default, $modals);
    }
}
