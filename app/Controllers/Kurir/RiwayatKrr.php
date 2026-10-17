<?php

namespace App\Controllers\Kurir;

use App\Controllers\BaseController;
use App\Models\DistribusiModel;

class RiwayatKrr extends BaseController
{
    /**
     * Halaman Riwayat Pengantaran & Tugas Kurir yang Telah Selesai
     */
    public function index()
    {
        $tab = (string) ($this->request->getGet('tab') ?? 'semua');
        if (! in_array($tab, ['semua', 'pengantaran', 'penjemputan'], true)) {
            $tab = 'semua';
        }

        $q = trim((string) ($this->request->getGet('q') ?? ''));

        $idKurir = (int) (session()->get('id_kurir') ?? session()->get('user_id') ?? 1);
        $model   = new DistribusiModel();

        $riwayatDb = [];
        $ringkasan = [
            'total'       => 0,
            'pengantaran' => 0,
            'penjemputan' => 0,
        ];

        try {
            $riwayatDb = $model->riwayatKurir($idKurir, $tab, $q);
            $ringkasan = $model->hitungRingkasanRiwayat($idKurir);
        } catch (\Throwable $th) {
            log_message('error', 'Gagal memuat riwayat kurir: ' . $th->getMessage());
        }

        $daftarRiwayat = [];
        if (! empty($riwayatDb)) {
            foreach ($riwayatDb as $rd) {
                $idPesanan = (int) $rd['id_pesanan'];
                $tipe      = (string) ($rd['tipe'] ?? 'pengantaran');
                $isJemput  = ($tipe === 'penjemputan');

                $alamat = $isJemput
                    ? ($rd['alamat_pengambilan'] ?? '-')
                    : ($rd['alamat_pengantaran'] ?? $rd['alamat_pengambilan'] ?? '-');

                $waktuRaw = ! empty($rd['waktu_selesai']) ? $rd['waktu_selesai'] : ($rd['tanggal'] ?? date('Y-m-d H:i:s'));
                $waktuFormatted = date('d M Y, H:i', strtotime((string)$waktuRaw)) . ' WIB';

                $total = (float) ($rd['total_bayar'] ?? 0);
                if ($total <= 0) {
                    $berat = (float) ($rd['berat'] ?? 1.0);
                    $total = $berat * 7000;
                }

                $statusLabel = $isJemput ? 'Cucian Berhasil Dijemput' : 'Pesanan Telah Diterima';

                $daftarRiwayat[] = [
                    'id_pesanan'     => $idPesanan,
                    'kode'           => '#DW-' . (1000 + $idPesanan),
                    'kode_psn'       => 'PSN-' . date('dmY', strtotime((string)$rd['tanggal'])) . '-' . str_pad((string)$idPesanan, 3, '0', STR_PAD_LEFT),
                    'tipe'           => $tipe,
                    'is_jemput'      => $isJemput,
                    'nama'           => (string) ($rd['nama_pelanggan'] ?? 'Pelanggan'),
                    'no_hp'          => (string) ($rd['no_hp_pelanggan'] ?? ''),
                    'alamat'         => (string) $alamat,
                    'waktu'          => $waktuFormatted,
                    'layanan'        => (string) ($rd['nama_layanan'] ?? 'Laundry Reguler'),
                    'berat'          => (float) ($rd['berat'] ?? 1.0),
                    'total'          => $total,
                    'metode'         => (string) ($rd['metode'] ?? 'Transfer Bank'),
                    'status_pesanan' => (string) ($rd['status_pesanan'] ?? 'selesai'),
                    'status_label'   => $statusLabel,
                ];
            }
        }

        return view('kurir/riwayat_krr', [
            'daftarRiwayat' => $daftarRiwayat,
            'tab'           => $tab,
            'q'             => $q,
            'ringkasan'     => $ringkasan,
            'aktif'         => 'riwayat',
        ]);
    }
}
