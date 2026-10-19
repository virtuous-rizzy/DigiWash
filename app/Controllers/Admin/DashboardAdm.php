<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananModel;

class DashboardAdm extends BaseController
{
    public function index()
    {
        $db           = \Config\Database::connect();
        $pesananModel = new PesananModel();

        return view('admin/dashboard_adm', [
            'nama'           => (string) session()->get('nama'),
            'jmlAdmin'       => $db->table('admin')->countAllResults(),
            'jmlKurir'       => $db->table('kurir')->countAllResults(),
            'jmlPelanggan'   => $db->table('pelanggan')->countAllResults(),
            'pesananTerbaru' => $pesananModel->pesananTerbaruAdmin(3),
            'aktif'          => 'beranda',
        ]);
    }
}