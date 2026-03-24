<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckoutModel extends Model
{
    protected $table = '_order';
    protected $primaryKey = 'order_id'; // Ако имате primary key различно от 'id', го посочете тук
    protected $allowedFields = ['product_id', 'quantity', 'klient_id', 'payment_method']; // Заменете с имената на вашите полета

    public function insertData($data)
    {
        return $this->insert($data);
    }

}


?>