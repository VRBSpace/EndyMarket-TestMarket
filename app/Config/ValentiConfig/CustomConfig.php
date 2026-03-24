<?php

namespace Config\ValentiConfig;

use CodeIgniter\Config\BaseConfig;

class CustomConfig extends BaseConfig {

    // $customConfig в файла \App\Common.php е зареден глобално

    public $comany                   = 'Endy Market';
    public $copyRight                = 'Copyright © 2026, <span class="font-weight-bold text-gray-90">Endy Market</span> - All Rights Reserved.';
    public $account                  = [
        'loginColumn' => 'email_login',
    ];
    ///////////////////////////////////////
    public $header                   = [
        'appendBlockModels' => true, // дали към хедъра да се прикрепи блок с моделите
    ];
    public $isEnable_loginWithTel    = true; // дали да се логева с телефон
    public $isEnable_uncompleteOrder = true; // дали да бъде активна опцията за незавършени поръчки
    public $btn                      = [
        'showCart' => true, // показва бутона количка за продуктите и mini cart
    ];
    public $sort                     = [
        'lowPrice' => false, //default дали да се сортира по най ниска цена
    ];
    public $isVisible                = [
        'show_logo_inExpOrder' => false, // дали да се вижда логото в поръчка експорт в pdf
        'speedyRadioBtn'       => true, // дали да се вижда радио бутона speedy офис в поръчка - клиент
        'fastOrder'            => true, // показва бърза поръчка в single продукт
        'link_marka'           => false, // link към марките в header
        'link_zadalzenia'      => false, // link към марките в header
        // left sidebar navigation
        'sidebarNavLeft'       => [
            'link_fixPrice' => true, // линк  фикс. цена
            'link_feature'  => true, // линк  очаквани продукти
            'link_new'      => true, // линк  нови продукти
            'link_brand'    => false, // линк  марки
        ]
    ];
}
