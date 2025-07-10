<?php

namespace App\Controllers;

class Kategori extends BaseController
{

    protected $m_kategori;
    function __construct()
    {
        if (!@session()->get('login') || session()->get('login')['login_st'] != 1) {
            header('Location: /');
            exit();
        }
        $this->m_kategori = new \App\Models\admin\M_kategori();
    }

    function index()
    {
        $data = [
            'title' => 'Kategori',
            'subtitle' => 'Daftar Kategori',
            'active_menu' => 'kategori',
        ];
        return $this->renderAdmin('admin/kategori/index', $data);
    }

    function list_datatables()
    {
        $res = $this->m_kategori->list_datatables();
        return json_encode($res);
    }

    function get_data()
    {
        $res = $this->m_kategori->get_data();
        return json_encode($res);
    }

    function save()
    {
        $res = $this->m_kategori->save_kategori();
        return json_encode($res);
    }

    function delete()
    {
        $res = $this->m_kategori->delete_kategori(_input()->getPost('kategori_id'));
        return json_encode($res);
    }
}
