<?php

namespace App\Models;

use CodeIgniter\Model;

class KlientModel extends Model {

    protected $table      = '_klient'; // Име на таблицата в базата данни
    protected $primaryKey = 'klient_id'; // Основен ключ на таблицата

    public function getcenovaNivoByUserId($user_id) {
        return $this -> select('zenaNivo')
                        -> where('klient_id', $user_id)
                        -> get() -> getRow('zenaNivo');
    }

    public function getKlientIdbyUserId($user_id) {
        if (!$user_id) {
            return;
        }
        return $this -> where('klient_id', $user_id) -> first();
    }

    // public function user()
    // {
    //     return $this->belongsTo(UserModel::class, 'user_id', 'klient_id');
    // }
}
