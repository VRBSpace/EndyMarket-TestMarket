<?php

namespace App\Models;

use CodeIgniter\Model;


class CampanyModel extends Model
{
    protected $table = '_order'; // Име на таблицата в базата данни
    protected $primaryKey = 'order_id'; // Основен ключ на таблицата
    protected $allowedFields = ['klient_id', 'status', 'payment_method', 'total_price', 'doc_files_path', 'order_number']; 


    public function insertData($data)
    {
        return $this->insert($data);
    }
}


