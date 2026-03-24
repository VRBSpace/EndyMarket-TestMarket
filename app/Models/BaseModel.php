<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model {

    public function __construct() {
        parent::__construct();
        $this -> customConfig = new \Config\ValentiConfig\CustomConfig();
    }

    protected const TABLES                     = [
        'zenova'    => '_ofer_zenovaLista',
        'promo'     => '_ofer_promo',
        'baner'     => '_ofer_baner',
        'bizKlient' => '_ofer_biznesKlient',
        'special'   => '_ofer_special',
        'order'     => '_ofer_order',
        'dostavka'  => '_dostavka',
    ];
    /////////////////////////////////////////////////////////////////////////
    protected const TBL_PRODUCT                = '_product';
    protected const TBL_PRODUCT_PRICE_LEVEL    = '_product_priceLevel';
    protected const TBL_PRODUCT_IN_ZENOVALISTA = '_product_in_zenovaLista';
    protected const TBL_PRODUCT_CHARACTERISTIC = '_product_characteristic';
    protected const TBL_PRODUCT_SITES          = '_product_sites';
    public const TBL_MODEL                     = '_model';
    public const TBL_PRODUCT_MODEL_ADDIT_LINK  = '_product_modelAddit_link';
    /////////////////////////////////////////////////////////////////////////////
    protected const TBL_CATEGORY               = '_category';
    protected const TBL_CATEGORY_ATTRIBUTE     = '_category_characteristic';
    //////////////////////////////////////////////////////////////////////////////////////
    protected const TBL_BRAND                  = '_brand';
    // protected const TBL_PRODUCT_MODEL          = '_product_model';
    protected const TBL_PRODUCT_MODELVAR       = '_product_model_var';
    protected const TBL_PRODUCT_CHAR_VALUE     = '_product_characteristic_value';
    ////////////////////////////////////////////////////////////////////////////////
    protected const TBL_RAZNOS                 = '_raznos';
    protected const TBL_DOSTAVKA               = '_dostavka';
    protected const TBL_ZENOVA_LISTA           = '_ofer_zenovaLista';
    protected const TBL_ORDER                  = '_ofer_order';
    protected const TBL_SPECIAL                = '_ofer_special';
    protected const TBL_PROMO                  = '_ofer_promo';
    protected const TBL_BIZNESKLIENT           = '_ofer_biznesKlient';
    ///////////////////////////////////////////////////////////////////////////////
    protected const TBL_SITE_ORDERCART         = '_order_cart';
    protected const TBL_SITE_ORDER             = '_order';
    //////////////////////////////////////////////////////////////////////////
    protected const TBL_SP_SITE                = '_sp_site';
    protected const TBL_SP_MQRKA               = '_sp_mqrka';
    protected const TBL_SP_STATUS              = '_sp_status';
    protected const TBL_SP_COUNTRY             = '_sp_country';
    protected const TBL_SP_KONTRAGENT          = '_sp_kontragent';
    protected const TBL_SP_RAZMER              = '_sp_razmer';
    protected const TBL_SP_TEGLO               = '_sp_teglo';
    protected const TBL_SP_LANGUAGE            = '_sp_language';
    protected const TBL_STATUS                 = '_sp_status';
    ////////////////////////////////////////////////////////////////////////////////
    protected const TBL_VALUTA                 = '_valuta';
    protected const TBL_SHABLON                = '_shablon';
    protected const TBL_KLIENT                 = '_klient';
    public const TBL_USERSITE                  = 'users_site';
    protected const TBL_AUTO_COMPLETE          = '_autoComplete';
    protected const TBL_PROVIDER               = '_provider';
    protected const TBL_PROVIDER_GROUP         = '_provider_group';
    protected const TBL_PROVIDER_INGROUP       = '_provider_inGroup';
    ////////////////////////////////////////////////////////////////
    protected const TBL_SETINGS_PORTAL         = 'settings_portal';
    protected const TBL_NASHIFIRMI             = '_nashiFirmi';
    protected const TBL_PRODUCT_TO_MULTISITES  = '_product_to_multiSites';
    /////////////////////////////////////
    protected const ATTRIBUTES                 = [
        'mqrka'  => '_sp_mqrka',
        'razmer' => '_sp_razmer',
        'teglo'  => '_sp_teglo'
    ];
}
