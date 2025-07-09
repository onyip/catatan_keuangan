<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class M_kategori extends Model
{

    protected $table = 'mst_kategori';
    protected $primaryKey = 'kategori_id';
    protected $protectFields = false;

    function kategory_by_type($type)
    {
        $data = $this->where(['transaksi_type' => $type, 'deleted_at' => null])->findAll();
        if (@$data) {
            return $data;
        } else {
            return [];
        }
    }
}
