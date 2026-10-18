<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardAdm extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        return view('admin/dashboard_adm', [
            'nama'         => (string) session()->get('nama'),
            'jmlAdmin'     => $db->table('admin')->countAllResults(),
            'jmlKurir'     => $db->table('kurir')->countAllResults(),
            'jmlPelanggan' => $db->table('pelanggan')->countAllResults(),
            'aktif'        => 'beranda',
        ]);
    }
}