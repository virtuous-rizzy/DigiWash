<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
<<<<<<< HEAD
=======
use App\Models\PesananModel;
>>>>>>> origin/digiwash-semoga-final

class DashboardAdm extends BaseController
{
    public function index()
    {
<<<<<<< HEAD
        $db = \Config\Database::connect();

        return view('admin/dashboard_adm', [
            'nama'         => (string) session()->get('nama'),
            'jmlAdmin'     => $db->table('admin')->countAllResults(),
            'jmlKurir'     => $db->table('kurir')->countAllResults(),
            'jmlPelanggan' => $db->table('pelanggan')->countAllResults(),
            'aktif'        => 'beranda',
=======
        $db           = \Config\Database::connect();
        $pesananModel = new PesananModel();

        return view('admin/dashboard_adm', [
            'nama'           => (string) session()->get('nama'),
            'jmlAdmin'       => $db->table('admin')->countAllResults(),
            'jmlKurir'       => $db->table('kurir')->countAllResults(),
            'jmlPelanggan'   => $db->table('pelanggan')->countAllResults(),
            'pesananTerbaru' => $pesananModel->pesananTerbaruAdmin(3),
            'aktif'          => 'beranda',
>>>>>>> origin/digiwash-semoga-final
        ]);
    }
}