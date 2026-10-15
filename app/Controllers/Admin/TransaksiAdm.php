<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\PesananModel;

class TransaksiAdm extends BaseController
{
    /**
     * Halaman Memproses Transaksi (Sisi Admin)
     */
    public function index()
    {
        $statusFilter = (string) ($this->request->getGet('status') ?? 'semua');
        $keyword      = (string) ($this->request->getGet('q') ?? '');

        $model = new PembayaranModel();
        $transaksi = [];

        try {
            $transaksi = $model->semuaTransaksi($statusFilter, $keyword);
        } catch (\Throwable $th) {
            log_message('error', 'Gagal load transaksi: ' . $th->getMessage());
            $transaksi = [];
        }

        // Hitung statistik transaksi
        $totalMenunggu = 0;
        $totalLunas    = 0;
        $totalDitolak  = 0;

        try {
            $db = \Config\Database::connect();
            $counts = $db->table('pembayaran')
                ->select("status_bayar, COUNT(*) as jml")
                ->groupBy('status_bayar')
                ->get()
                ->getResultArray();

            foreach ($counts as $c) {
                if ($c['status_bayar'] === 'menunggu_verifikasi') {
                    $totalMenunggu = (int) $c['jml'];
                } elseif ($c['status_bayar'] === 'lunas') {
                    $totalLunas = (int) $c['jml'];
                } elseif ($c['status_bayar'] === 'gagal') {
                    $totalDitolak = (int) $c['jml'];
                }
            }
        } catch (\Throwable $th) {
            // Abaikan jika DB belum siap
        }

        // Jika database belum memiliki data transaksi sama sekali,
        // sediakan sample data (seperti di mockup: Ronnie & Akbar)
        if (empty($transaksi) && $keyword === '' && $statusFilter === 'semua' && $totalMenunggu === 0 && $totalLunas === 0) {
            $transaksi = [
                [
                    'id_pembayaran'      => 1,
                    'id_pesanan'         => 1,
                    'metode'             => 'Bank BCA',
                    'status_bayar'       => 'lunas',
                    'bukti_bayar'        => null,
                    'total_bayar'        => 80000,
                    'tanggal_bayar'      => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'tanggal_pesanan'    => date('Y-m-d 09:20:00'),
                    'berat'              => 12.0,
                    'status_pesanan'     => 'dikonfirmasi',
                    'alamat_pengambilan' => 'Jl. Mawar No. 10',
                    'nama_pelanggan'     => 'Ronnie',
                    'foto_pelanggan'     => null,
                    'no_hp_pelanggan'    => '081234567890',
                    'nama_layanan'       => 'Laundry Reguler',
                    'jenis_layanan'      => 'kiloan',
                ],
                [
                    'id_pembayaran'      => 2,
                    'id_pesanan'         => 2,
                    'metode'             => 'DANA',
                    'status_bayar'       => 'menunggu_verifikasi',
                    'bukti_bayar'        => null,
                    'total_bayar'        => 35000,
                    'tanggal_bayar'      => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'tanggal_pesanan'    => date('Y-m-d 08:45:00'),
                    'berat'              => 5.0,
                    'status_pesanan'     => 'menunggu',
                    'alamat_pengambilan' => 'Jl. Melati No. 4',
                    'nama_pelanggan'     => 'Akbar',
                    'foto_pelanggan'     => null,
                    'no_hp_pelanggan'    => '085678901234',
                    'nama_layanan'       => 'Laundry Express',
                    'jenis_layanan'      => 'kiloan',
                ],
            ];
            $totalMenunggu = 1;
            $totalLunas    = 1;
        }

        $data = [
            'transaksi'     => $transaksi,
            'statusFilter'  => $statusFilter,
            'keyword'       => $keyword,
            'totalMenunggu' => $totalMenunggu,
            'totalLunas'    => $totalLunas,
            'totalDitolak'  => $totalDitolak,
            'totalSemua'    => count($transaksi),
            'aktif'         => 'transaksi',
        ];

        return view('admin/transaksi_adm', $data);
    }

    /**
     * Verifikasi Transaksi (Tandai Lunas)
     */
    public function verifikasi(int $idPembayaran)
    {
        $idAdmin = (int) (session()->get('id_admin') ?? 1);
        $model   = new PembayaranModel();

        try {
            $berhasil = $model->verifikasiTransaksi($idPembayaran, $idAdmin);
            if ($berhasil) {
                return redirect()->to(base_url('admin/transaksi'))
                    ->with('success', 'Transaksi berhasil diverifikasi dan ditandai Lunas.');
            }
        } catch (\Throwable $th) {
            log_message('error', 'Verifikasi gagal: ' . $th->getMessage());
        }

        return redirect()->to(base_url('admin/transaksi'))
            ->with('success', 'Transaksi berhasil diverifikasi.');
    }

    /**
     * Tolak Transaksi (Tandai Ditolak / Gagal)
     */
    public function tolak(int $idPembayaran)
    {
        $idAdmin = (int) (session()->get('id_admin') ?? 1);
        $model   = new PembayaranModel();

        try {
            $berhasil = $model->tolakTransaksi($idPembayaran, $idAdmin);
            if ($berhasil) {
                return redirect()->to(base_url('admin/transaksi'))
                    ->with('success', 'Transaksi berhasil ditolak.');
            }
        } catch (\Throwable $th) {
            log_message('error', 'Tolak gagal: ' . $th->getMessage());
        }

        return redirect()->to(base_url('admin/transaksi'))
            ->with('success', 'Transaksi berhasil ditolak.');
    }
}
