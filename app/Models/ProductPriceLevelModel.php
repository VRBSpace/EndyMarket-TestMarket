<?php

namespace App\Models;

use CodeIgniter\Model;


class ProductPriceLevelModel extends Model
{
    protected $table = '_product_priceLevel'; // Име на таблицата в базата данни
    protected $primaryKey = 'product_priceLevel_id'; // Основен ключ на таблицата

    public function getProductPrice($product_id)
    {
        return $this->where('product_id', $product_id)->first();
    }

}




