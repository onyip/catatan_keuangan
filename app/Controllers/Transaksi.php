<?php

namespace App\Controllers;

class Transaksi extends BaseController
{

    protected $m_transaksi;
    function __construct()
    {
        if (!@session()->get('login') || session()->get('login')['login_st'] != 1) {
            header('Location: /');
            exit();
        }
        $this->m_transaksi = new \App\Models\admin\M_transaksi();
    }

    function index()
    {
        $data = [
            'title' => 'Transaksi',
            'subtitle' => 'Daftar Transaksi',
            'active_menu' => 'transaksi',
        ];
        $kategori = new \App\Models\admin\M_kategori();
        $data['kategori_pengeluaran'] = $kategori->kategory_by_type(0);
        $data['kategori_pemasukan'] = $kategori->kategory_by_type(1);
        return $this->renderAdmin('admin/transaksi/index', $data);
    }

    function list_datatables()
    {
        $res = $this->m_transaksi->list_datatables();
        return json_encode($res);
    }

    function get_data()
    {
        $res = $this->m_transaksi->get_data();
        return $res;
    }

    function save()
    {
        $res = $this->m_transaksi->save_transaksi();
        return json_encode($res);
    }

    function delete()
    {
        $res = $this->m_transaksi->delete_transaksi(_input()->getPost('transaksi_id'));
        return json_encode($res);
    }
}
