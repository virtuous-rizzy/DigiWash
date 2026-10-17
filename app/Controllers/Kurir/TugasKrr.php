<?php

namespace App\Controllers\Kurir;

use App\Controllers\BaseController;
use App\Models\DistribusiModel;

class TugasKrr extends BaseController
{
    /**
     * Halaman Tugas Kurir (Mockup Penjemputan & Pengantaran)
     */
    public function index()
    {
        $tab = (string) ($this->request->getGet('tab') ?? 'semua');
        if (! in_array($tab, ['semua', 'penjemputan', 'pengantaran'], true)) {
            $tab = 'semua';
        }

        $idKurir = (int) (session()->get('id_kurir') ?? session()->get('user_id') ?? 1);
        $model   = new DistribusiModel();

        $tugasDb   = [];
        $ringkasan = [
            'aktif'   => 2,
            'selesai' => 1,
            'jemput'  => 1,
            'antar'   => 1,
        ];

        try {
            $tugasDb   = $model->tugasKurir($idKurir, $tab);
            $dbSummary = $model->hitungRingkasanKurir($idKurir);
            if ($dbSummary['aktif'] > 0 || $dbSummary['selesai'] > 0) {
                $ringkasan = $dbSummary;
            }
        } catch (\Throwable $th) {
            log_message('error', 'Gagal load tugas kurir: ' . $th->getMessage());
        }

        // Jika data di DB belum di-assign atau kosong, sediakan sampel sesuai mockup (Alya Putri & Budi)
        $daftarTugas = [];
        if (! empty($tugasDb)) {
            foreach ($tugasDb as $td) {
                $stPsn = (string) ($td['status_pesanan'] ?? 'menunggu');
                $isJemput = ($td['tipe'] === 'penjemputan');

                $statusLabel = match ($stPsn) {
                    'menunggu', 'dikonfirmasi' => 'Menunggu dijemput',
                    'dijemput'                  => 'Sedang dijemput',
                    'disetrika'                 => 'Siap diantar',
                    'diantar'                   => 'Sedang diantar',
                    'selesai'                   => 'Selesai',
                    default                     => 'Menunggu dijemput',
                };

                $statusColor = in_array($stPsn, ['disetrika', 'selesai'], true) ? 'green' : 'peach';
                $alamat = $isJemput ? ($td['alamat_pengambilan'] ?? '-') : ($td['alamat_pengantaran'] ?? '-');

                $daftarTugas[] = [
                    'id_pesanan'   => (int) $td['id_pesanan'],
                    'kode'         => '#DW-' . (1000 + (int)$td['id_pesanan']),
                    'tipe'         => $td['tipe'],
                    'nama'         => (string) ($td['nama_pelanggan'] ?? 'Pelanggan'),
                    'alamat'       => (string) $alamat,
                    'jam'          => '09.00 - 10.00',
                    'status_label' => $statusLabel,
                    'status_color' => $statusColor,
                ];
            }
        } else {
            // Sample data presisi sesuai gambar mockup
            $sampleData = [
                [
                    'id_pesanan'   => 24,
                    'kode'         => '#DW-1024',
                    'tipe'         => 'penjemputan',
                    'nama'         => 'Alya Putri',
                    'alamat'       => 'JL. Melati No.12, Banjarmasin',
                    'jam'          => '09.00 - 10.00',
                    'status_label' => 'Menunggu dijemput',
                    'status_color' => 'peach',
                ],
                [
                    'id_pesanan'   => 21,
                    'kode'         => '#DW-1021',
                    'tipe'         => 'pengantaran',
                    'nama'         => 'Budi',
                    'alamat'       => 'JL. Dahlia No. 10, Banjarmasin',
                    'jam'          => '09.00 - 10.00',
                    'status_label' => 'Siap diantar',
                    'status_color' => 'green',
                ],
            ];

            if ($tab === 'penjemputan') {
                $daftarTugas = [$sampleData[0]];
            } elseif ($tab === 'pengantaran') {
                $daftarTugas = [$sampleData[1]];
            } else {
                $daftarTugas = $sampleData;
            }
        }

        return view('kurir/tugas_krr', [
            'daftarTugas' => $daftarTugas,
            'tab'         => $tab,
            'ringkasan'   => $ringkasan,
            'aktif'       => 'tugas',
        ]);
    }
}
