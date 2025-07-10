<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class M_profil extends Model
{

    protected $table = 'mst_user';
    protected $primaryKey = 'user_id';
    protected $protectFields = false;

    function change_password()
    {
        $data = _input()->getPost();

        $user = $this->find(session()->get('login')['user_id']);
        if (!password_verify($data['old_password'], $user['password'])) {
            return ['status' => 'error', 'message' => 'Password lama tidak cocok.'];
        }

        $newPasswordHash = password_hash($data['new_password'], PASSWORD_DEFAULT);
        $this->update(session()->get('login')['user_id'], ['password' => $newPasswordHash]);
        return ['status' => 'success', 'message' => 'Password berhasil diubah.'];
    }

    function update_profil()
    {
        $data = _input()->getPost();
        $this->update(session()->get('login')['user_id'], $data);
        session()->set('login', array_merge(session()->get('login'), $data));
        if ($this->affectedRows() > 0) {
            return ['status' => 'success', 'message' => 'Profil berhasil diperbarui.'];
        } else {
            return ['status' => 'error', 'message' => 'Tidak ada perubahan yang dilakukan pada profil.'];
        }
    }
}
