<?php

namespace App\Controllers\Kurir;

use App\Controllers\BaseController;
use App\Models\DistribusiModel;

class DashboardKrr extends BaseController
{
    public function index()
    {
        $idKurir = (int) (session()->get('id_kurir') ?? session()->get('user_id') ?? 1);
        $kurir = \Config\Database::connect()->table('kurir')
            ->select('nama, status')
            ->where('id_kurir', $idKurir)
            ->get()->getRowArray() ?? [];

        $model = new DistribusiModel();
        $ringkasan = [
            'aktif'   => 2,
            'selesai' => 1,
            'jemput'  => 1,
            'antar'   => 1,
        ];
        try {
            $dbSummary = $model->hitungRingkasanKurir($idKurir);
            if ($dbSummary['aktif'] > 0 || $dbSummary['selesai'] > 0) {
                $ringkasan = $dbSummary;
            }
        } catch (\Throwable $th) {
            // Gunakan default
        }

        return view('kurir/dashboard_krr', [
            'nama'      => (string) ($kurir['nama'] ?? session()->get('nama') ?? 'Kurir'),
            'status'    => (string) ($kurir['status'] ?? 'tersedia'),
            'ringkasan' => $ringkasan,
            'aktif'     => 'beranda',
        ]);
    }
}