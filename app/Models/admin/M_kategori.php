<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class M_kategori extends Model
{

    protected $table = 'mst_kategori';
    protected $primaryKey = 'kategori_id';
    protected $protectFields = false;
    protected $useSoftDeletes = true;

    function list_datatables()
    {
        $data = _input()->getPost();

        $builder = $this->db->table('mst_kategori mk');
        $builder->select("mk.*");
        $builder->where('mk.deleted_at IS NULL');
        $builder->where('mk.user_id ', session()->get('login')['user_id']);
        if (@$data['transaksi_type'] != 2) {
            $builder->where('mk.transaksi_type', $data['transaksi_type']);
        }


        if (!empty($data['search']['value'])) {
            $builder->groupStart();
            $builder->orLike('mk.kategori_nm', $data['search']['value']);
            $builder->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $builder->orderBy('kategori_nm', 'ASC');
        $builder->limit($data['length'], $data['start']);
        $result = $builder->get()->getResult();

        $recordsTotal = $this->db->table('mst_kategori mk')->where(['user_id' => session()->get('login')['user_id']])->countAllResults();

        $output = [
            'draw' => intval($data['draw']),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $result,
        ];
        return $output;
    }


    function get_data()
    {
        $kategori_id = _input()->getPost('kategori_id');
        $data = $this->find($kategori_id);
        if ($data) {
            return json_encode([
                'status' => 'success',
                'message' => 'Data ditemukan',
                'data' => $data
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }


    function kategory_by_type($type)
    {
        $data = $this->where(['transaksi_type' => $type, 'user_id' => session()->get('login')['user_id'], 'deleted_at' => null])->findAll();
        if (@$data) {
            return $data;
        } else {
            return [];
        }
    }

    function save_kategori()
    {
        $data = _input()->getPost();
        $data['user_id'] = session()->get('login')['user_id'];
        if (!@$data['kategori_id']) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = session()->get('login')['nama_lengkap'];
            $db = $this->insert($data);
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = session()->get('login')['nama_lengkap'];
            $db = $this->update($data['kategori_id'], $data);
        }

        if (@$db) {
            return [
                'status' => 'success',
                'message' => 'Kategori berhasil disimpan.',
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal menyimpan kategori. Silakan coba lagi.',
            ];
        }
    }

    function delete_kategori()
    {
        $kategori_id = _input()->getPost('kategori_id');
        $this->delete($kategori_id);
        if ($this->db->affectedRows() > 0) {
            return [
                'status' => 'success',
                'message' => 'Kategori berhasil dihapus.',
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal menghapus kategori. Silakan coba lagi.',
            ];
        }
    }
}
