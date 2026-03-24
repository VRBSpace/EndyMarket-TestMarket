<?php

namespace App\Models;

use \App\Models\BaseModel as BaseModel;

class UserModel extends BaseModel {

    protected $table         = 'users_site';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['username', 'email_login', 'password', 'is_password_changed'];

    public function getUserByEmailOrBustat($email_bustat) {

        $config = $this -> customConfig;

        $user = $this -> db -> table(self::TBL_USERSITE . ' u')
                        -> join(self::TBL_KLIENT . ' k', 'k.klient_id=u.id', 'inner')
                        -> groupStart()
                        -> where('u.email_login', $email_bustat)
                        -> orWhere('u.bulstat', $email_bustat)
                        -> groupEnd()
                        -> where('k.isActive', 'Y')
                        -> get() -> getRowArray();

        // ако не същ. user в user_site се копират данните за клиент от табл. _klient
        if (!$user) {
            $klient = $this -> db -> table(self::TBL_KLIENT)
                            -> groupStart()
                            -> where('bulstat', $email_bustat)
                            -> orWhere('klient_tel', $email_bustat)
                            -> groupEnd()
                            -> where('isActive', 'Y')
                            -> get() -> getRowArray();

            if ($klient) {
                // Изтриване на стар запис с еднакъв булстат (ако е различен по ID)
                $this -> db -> table(self::TBL_USERSITE)
                        -> where('bulstat', $klient['bulstat'])
                        -> where('id !=', $klient['klient_id'])
                        -> delete();

                // Разделяне на име и фамилия
                $mol   = trim((string) ($klient['klient_mol'] ?? ''));
                $names = array_pad(preg_split('/\s+/u', $mol, 2), 2, '---');
                [$firstName, $lastName] = $names;

                $klientData = [
                    'id'          => $klient['klient_id'],
                    'username'    => $klient['klient_name'],
                    'first_name'  => $firstName,
                    'last_name'   => $lastName,
                    'password'    => '$2y$10$5LgEvBI3y66/uPJgG8Zw8.Eul0Wpw2nQ4rZCzOk455SxHOvJmc8sW', // Default password hash (1234)
                    'email_login' => $config -> isEnable_loginWithTel ? $klient['klient_tel'] : $klient['email'],
                    'bulstat'     => $klient['bulstat'],
                ];

                $this -> upsert($klientData);
            }

            // Re-query the user after upsert
            $user = $this -> where('email_login', $email_bustat)
                    -> orWhere('bulstat', $email_bustat)
                    -> first();
        }

        return $user;
    }

    public function updateUserPassword($userId, $newPassword, $email = '') {
        // Обновете паролата в базата данни

        if (empty($userId)) {
            return ['err' => 'Грешка. Липсва id на потребител.'];
        }

        if (!empty($newPassword)) {
            $data['password']            = password_hash($newPassword, PASSWORD_DEFAULT);
            $data['is_password_changed'] = 1;
        }

        $data['email_login'] = !empty($email) ? $email : null;

        try {
            $this -> db -> transStart();

            $res = $this -> update($userId, $data);

            if (!$this -> db -> transStatus()) {
                $errorCode = $this -> db -> error()['code'];

                if ($errorCode === 1062) {
                    $errorMessage = "Този имейл $email вече съществува.";
                } else {
                    $errorMessage = $this -> db -> error()['message'];
                }
                $this -> db -> transRollback();
                return ['err' => $errorMessage];
            }

            $this -> db -> transComplete();
            return $res;
        } catch (\Exception $e) {
            $this -> db -> transRollback();
            return ['err' => $e -> getMessage()];
        }
    }

    public function client() {
        return $this -> hasOne(KlientModel::class, 'user_id', 'klient_id');
    }
}
