<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class M_laporan extends Model
{

    protected $table = 'dat_transaksi';
    protected $primaryKey = 'transaksi_id';

    function data_per_kategori()
    {
        $data = _input()->getPost();
        $query = $this->db->table('mst_kategori mk');
        $query->select('mk.kategori_nm, SUM(dt.nominal) as jumlah');
        $query->join('dat_transaksi dt', 'mk.kategori_id = dt.kategori_id', 'left');
        $query->where('dt.user_id', session()->get('login')['user_id']);
        $query->where('dt.transaksi_type', 1);
        $query->where("date(dt.transaksi_tgl) BETWEEN '" . $data['startDate'] . "' AND '" . $data['endDate'] . "'");
        $query->groupBy('mk.kategori_id');
        $query->orderBy('mk.kategori_nm', 'ASC');
        $pemasukan = $query->get()->getResultArray();

        $query = $this->db->table('mst_kategori mk');
        $query->select('mk.kategori_nm, SUM(dt.nominal) as jumlah');
        $query->join('dat_transaksi dt', 'mk.kategori_id = dt.kategori_id', 'left');
        $query->where('dt.user_id', session()->get('login')['user_id']);
        $query->where("date(dt.transaksi_tgl) BETWEEN '" . $data['startDate'] . "' AND '" . $data['endDate'] . "'");
        $query->where('dt.transaksi_type', 0);
        $query->groupBy('mk.kategori_id');
        $query->orderBy('mk.kategori_nm', 'ASC');
        $pengeluaran = $query->get()->getResultArray();

        $result = [
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran
            ]
        ];

        return $result;
    }
}
