<?php

namespace App\Controllers;

class Profil extends BaseController
{

    protected $m_profil;
    function __construct()
    {
        if (!@session()->get('login') || session()->get('login')['login_st'] != 1) {
            header('Location: /');
            exit();
        }
        $this->m_profil = new \App\Models\admin\M_profil();
    }

    function index()
    {
        $data = [
            'title' => 'Profil',
            'subtitle' => 'Profil Saya',
            'active_menu' => 'profil',
        ];
        $data['user'] = $this->m_profil->find(session()->get('login')['user_id']);
        return $this->renderAdmin('admin/profil/index', $data);
    }

    function upload_foto()
    {
        $rules = [
            'profil_foto' => [
                'rules' => 'uploaded[profil_foto]|mime_in[profil_foto,image/jpg,image/jpeg,image/png]|max_size[profil_foto,2048]',
                'errors' => [
                    'uploaded' => 'Anda harus memilih sebuah gambar.',
                    'mime_in' => 'File yang diunggah bukan gambar yang valid (jpg, jpeg, png).',
                    'max_size' => 'Ukuran gambar tidak boleh lebih dari 2MB.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return json_encode([
                'status' => 'error',
                'message' => $this->validator->getError('profil_foto')
            ]);
        }

        $fileGambar = $this->request->getFile('profil_foto');
        $namaGambar = $fileGambar->getRandomName();
        $fileGambar->move(WRITEPATH . 'uploads/profil/', $namaGambar);

        // Hapus foto lama jika ada
        $fotoLama = $this->m_profil->find(session()->get('login')['user_id'])['foto'];
        if ($fotoLama && file_exists(WRITEPATH . 'uploads/profil/' . $fotoLama)) {
            unlink(WRITEPATH . 'uploads/profil/' . $fotoLama);
        }
        // Update nama foto di database
        $this->m_profil->update(session()->get('login')['user_id'], [
            'foto' => $namaGambar
        ]);
        session()->set('login', array_merge(session()->get('login'), ['foto' => $namaGambar]));

        return json_encode([
            'status' => 'success',
            'message' => 'Foto profil berhasil diunggah.'
        ]);
    }

    function profil_foto()
    {
        $foto = $this->m_profil->find(session()->get('login')['user_id'])['foto'];
        $path = WRITEPATH . 'uploads/profil/' . $foto;

        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $file = new \CodeIgniter\Files\File($path);
        $this->response->setHeader('Content-Type', $file->getMimeType());
        $this->response->setHeader('Content-Length', $file->getSize());
        readfile($path);
        exit();
    }


    function change_password()
    {
        $rules = [
            'old_password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password lama harus diisi.'
                ]
            ],
            'new_password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password baru harus diisi.',
                ]
            ],
            'confirm_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi.',
                    'matches' => 'Konfirmasi password tidak cocok dengan password baru.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return json_encode([
                'status' => 'error',
                'message' => $this->validator->getError('old_password') ?: $this->validator->getError('new_password') ?: $this->validator->getError('confirm_password')
            ]);
        }
        $res = $this->m_profil->change_password();
        return json_encode($res);
    }

    function update_profil()
    {
        $rules = [
            'username' => [
                'rules' => 'required|is_unique[mst_user.username, user_id,' . session()->get('login')['user_id'] . ']',
                'errors' => [
                    'required' => 'Username harus diisi.',
                    'is_unique' => 'Username ini sudah terdaftar. Silakan gunakan username lain.'
                ]
            ],
            'nama_lengkap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[mst_user.email, user_id,' . session()->get('login')['user_id'] . ']',
                'errors' => [
                    'required' => 'Email harus diisi.',
                    'valid_email' => 'Email tidak valid.',
                    'is_unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return json_encode([
                'status' => 'error',
                'message' => $this->validator->getError('username') ?: $this->validator->getError('nama_lengkap') ?: $this->validator->getError('email')
            ]);
        }

        $res = $this->m_profil->update_profil();
        return json_encode($res);
    }
}
