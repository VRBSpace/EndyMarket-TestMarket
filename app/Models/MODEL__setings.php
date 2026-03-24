<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class MODEL__setings extends BaseModel {

    // извл на настройките на портала
    public function get__nastroiki($arr = []) {
        return $this -> db -> table(self::TBL_SETINGS_PORTAL)
                        -> where('key', $arr['key'])
                        -> get() -> getRow();
    }

    public function get__nastroikiAll() {
        return $this -> db -> table(self::TBL_SETINGS_PORTAL)
                        -> get() -> getResultArray();
    }

    public function get__logo() {
        return $this -> db -> table(self::TBL_SETINGS_PORTAL)
                        -> where('key', 'logo')
                        -> get() -> getRow();
    }

    public function get__slideshow_images() {
        return $this -> db -> table(self::TBL_SETINGS_PORTAL)
                        -> where('key', 'slideshow')
                        -> orWhere('key', 'slideshowDilar')
                        -> get() -> getResultArray();
    }

    public function get__banerBlock1_images() {
        return $this -> db -> table(self::TBL_SETINGS_PORTAL)
                        -> where('key', 'home_banerBlock1')
                        -> orWhere('key', 'home_banerBlock1Dilar')
                        -> get() -> getResultArray();
    }
}
