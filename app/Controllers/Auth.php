<?php

namespace App\Controllers;

class Auth extends BaseController
{

    protected $m_auth;
    function __construct()
    {
        $this->m_auth = new \App\Models\auth\M_auth();
    }

    //login
    public function login()
    {
        return $this->renderAuth('auth/login');
    }

    function login_action()
    {
        $data = _input()->getPost();
        if (@$data['user'] && @$data['pass']) {
            $res = $this->m_auth->login_action($data);
        } else {
            $res = ['status' => 'error', 'message' => 'Username dan password wajib diisi!'];
        }
        return json_encode($res);
    }
    // end login

    //register
    public function register()
    {
        return $this->renderAuth('auth/register');
    }

    function register_action()
    {
        $data = _input()->getPost();
        $rules = [
            'username' => [
                'rules'  => 'required|is_unique[mst_user.username]',
                'errors' => [
                    'required'   => 'Username harus diisi.',
                    'is_unique'  => 'Username ini sudah terdaftar. Silakan gunakan username lain.'
                ]
            ],
            'nama_lengkap' => [
                'rules'  => 'required',
                'errors' => [
                    'required'    => 'Nama lengkap harus diisi.',
                ]
            ],
            'password' => [
                'rules'  => 'required',
                'errors' => [
                    'required'   => 'Password harus diisi.',
                ]
            ],
            're-password' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Re-password password harus diisi.',
                    'matches'  => 'Re-password password tidak cocok dengan password.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal
            return json_encode(['status' => 'error', 'message' => 'Registrasi gagal!', 'data' => $this->validator->getErrors()]);
        }

        $res = $this->m_auth->register_action($data);
        if ($res) {
            return json_encode(['status' => 'success', 'message' => 'Registrasi berhasil!']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Registrasi gagal!']);
        }
    }
}
