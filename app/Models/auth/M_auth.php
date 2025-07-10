<?php

namespace App\Models\auth;

use CodeIgniter\Model;

class M_auth extends Model
{

    protected $table = 'mst_user';
    protected $primaryKey = 'user_id';
    protected $protectFields = false;

    function login_action($data)
    {

        $user = $this->where(['username' => $data['user'], 'deleted_at' => null])->first();
        if (@$user) {
            if (password_verify($data['pass'], $user['password'])) {
                $sess['login'] = [
                    'login_st' => 1,
                    'login_at' => date('Y-m-d H:i:s'),
                    'user_id' => $user['user_id'],
                    'username' => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'foto' => $user['foto'],
                ];
                session()->set($sess);
                return ['status' => 'success', 'message' => 'Login Berhasil!'];
            }
            return ['status' => 'error', 'message' => 'Username dan password anda tidak cocok!'];
        }
        return ['status' => 'error', 'message' => 'Username tidak ditemukan atau sudah tidak aktif!'];
    }

    function register_action($data)
    {
        $user = [
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $data['nama_lengkap'],
            'email' => $data['email'],
            'username' => $data['username'],
            'nama_lengkap' => $data['nama_lengkap'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ];
        return $this->insert($user);
    }
}
