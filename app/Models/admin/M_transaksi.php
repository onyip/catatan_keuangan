<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class M_transaksi extends Model
{

    protected $table = 'dat_transaksi';
    protected $primaryKey = 'transaksi_id';
    protected $protectFields = false;

    function list_datatables()
    {
        $data = _input()->getPost();

        $builder = $this->db->table('dat_transaksi dt');
        $builder->select("dt.*, DATE_FORMAT(dt.transaksi_tgl, '%d-%m-%Y %H:%i:%s') as transaksi_tgl_formatted, mk.kategori_nm");
        $builder->join('mst_kategori mk', 'dt.kategori_id = mk.kategori_id', 'left');

        $builder->where('dt.user_id', session()->get('login')['user_id']);
        if (@$data['transaksi_type'] != 2) {
            $builder->where('dt.transaksi_type', $data['transaksi_type']);
        }


        if (!empty($data['search']['value'])) {
            $builder->groupStart();
            $builder->orLike('dt.keterangan', $data['search']['value']);
            $builder->orLike('mk.kategori_nm', $data['search']['value']);
            $builder->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $builder->orderBy('transaksi_tgl', 'DESC');
        $builder->limit($data['length'], $data['start']);
        $result = $builder->get()->getResult();

        $recordsTotal = $this->db->table('dat_transaksi dt')->where(['user_id' => session()->get('login')['user_id']])->countAllResults();

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
        $transaksi_id = _input()->getPost('transaksi_id');
        $data = $this->find($transaksi_id);
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

    function save_transaksi()
    {
        $data = _input()->getPost();
        $data['user_id'] = session()->get('login')['user_id'];
        if (!@$data['transaksi_id']) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = session()->get('login')['nama_lengkap'];
            // echo json_encode($data); die;
            $db = $this->insert($data);
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = session()->get('login')['nama_lengkap'];
            $db = $this->update($data['transaksi_id'], $data);
        }

        if (@$db) {
            return [
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan.',
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi. Silakan coba lagi.',
            ];
        }
    }

    function delete_transaksi()
    {
        $transaksi_id = _input()->getPost('transaksi_id');
        $this->delete($transaksi_id);
        if ($this->db->affectedRows() > 0) {
            return [
                'status' => 'success',
                'message' => 'Transaksi berhasil dihapus.',
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal menghapus transaksi. Silakan coba lagi.',
            ];
        }
    }
}
