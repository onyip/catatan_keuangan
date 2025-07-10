<?php

namespace App\Controllers;

class Laporan extends BaseController
{

    protected $m_laporan;
    function __construct()
    {
        if (!@session()->get('login') || session()->get('login')['login_st'] != 1) {
            header('Location: /');
            exit();
        }
        $this->m_laporan = new \App\Models\admin\M_laporan();
    }

    function index()
    {
        $data = [
            'title' => 'Laporan',
            'subtitle' => 'Laporan Transaksi',
            'active_menu' => 'laporan',
        ];
        $data['user'] = $this->m_laporan->find(session()->get('login')['user_id']);
        return $this->renderAdmin('admin/laporan/index', $data);
    }

    function data_per_kategori()
    {
        $res = $this->m_laporan->data_per_kategori();
        return $this->response->setJSON($res);
    }
}
