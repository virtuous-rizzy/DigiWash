<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\PesananModel;

class DashboardPlg extends BaseController
{
    public function index()
    {
        $idPelanggan = (int) (session()->get('id_pelanggan') ?? session()->get('user_id') ?? 1);
        $model       = new PesananModel();

        // Ambil pesanan aktif (bukan selesai/dibatalkan) dari DB
        $pesananAktif = $model->jumlahAktif($idPelanggan);

        // Ambil pesanan terbaru untuk info status
        $semua     = $model->milikPelanggan($idPelanggan);
        $terbaru   = $semua[0] ?? null;

        $statusCuci  = $terbaru ? ucfirst(str_replace('_', ' ', $terbaru['status_pesanan'])) : '-';
        $statusBayar = $terbaru ? ucfirst($terbaru['status_bayar'] ?? 'Belum Bayar') : '-';

        $totalPesanan = count($semua);

        $data = [
            'nama'         => session()->get('nama'),
            'pesananAktif' => $pesananAktif,
            'totalPesanan' => $totalPesanan,
            'statusCuci'   => $statusCuci,
            'statusBayar'  => $statusBayar,
        ];

        return view('pelanggan/dashboard_plg', $data);
    }
}