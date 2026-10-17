<?php

namespace App\Controllers\Kurir;

use App\Controllers\BaseController;
use App\Models\DistribusiModel;
use App\Models\PesananModel;

class TugasKrr extends BaseController
{
    /**
     * Halaman Daftar Tugas Kurir (Penjemputan & Pengantaran)
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
            'aktif'   => 0,
            'selesai' => 0,
            'jemput'  => 0,
            'antar'   => 0,
        ];

        try {
            $tugasDb   = $model->tugasKurir($idKurir, $tab);
            $ringkasan = $model->hitungRingkasanKurir($idKurir);
        } catch (\Throwable $th) {
            log_message('error', 'Gagal load tugas kurir: ' . $th->getMessage());
        }

        $daftarTugas = [];
        if (! empty($tugasDb)) {
            foreach ($tugasDb as $td) {
                $stPsn    = (string) ($td['status_pesanan'] ?? 'menunggu');
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
        }

        return view('kurir/tugas_krr', [
            'daftarTugas' => $daftarTugas,
            'tab'         => $tab,
            'ringkasan'   => $ringkasan,
            'aktif'       => 'tugas',
        ]);
    }

    /**
     * Halaman Detail Tugas Kurir (Penjemputan / Pengantaran)
     */
    public function detail(int $idPesanan)
    {
        $idKurir = (int) (session()->get('id_kurir') ?? session()->get('user_id') ?? 1);
        $model   = new DistribusiModel();
        $tugas   = null;

        try {
            $tugas = $model->detailTugas($idPesanan, $idKurir);
            if (! $tugas) {
                $tugas = $model->detailTugas($idPesanan);
            }
        } catch (\Throwable $th) {
            log_message('error', 'Gagal memuat detail tugas: ' . $th->getMessage());
        }

        if (! $tugas) {
            return redirect()->to(base_url('kurir/tugas'))
                ->with('errors', ['Tugas pesanan #' . $idPesanan . ' tidak ditemukan.']);
        }

        $stPesanan   = (string) ($tugas['status_pesanan'] ?? 'menunggu');
        $statusBayar = (string) ($tugas['status_bayar'] ?? 'belum_dibayar');

        // Validasi apakah tugas ini legal untuk dijalankan kurir
        $isLunas      = ($statusBayar === 'lunas');
        $isDibatalkan = ($stPesanan === 'dibatalkan' || $statusBayar === 'gagal');
        $isMenunggu   = ($stPesanan === 'menunggu' || in_array($statusBayar, ['menunggu_verifikasi', 'belum_dibayar'], true));
        $bisaDiproses = ($isLunas && ! $isDibatalkan && ! $isMenunggu);

        $tipe = in_array($stPesanan, ['menunggu', 'dikonfirmasi', 'dijemput'], true) ? 'penjemputan' : 'pengantaran';
        $alamat = ($tipe === 'penjemputan') ? ($tugas['alamat_pengambilan'] ?? '-') : ($tugas['alamat_pengantaran'] ?? '-');

        return view('kurir/tugas_detail_krr', [
            'tugas'        => $tugas,
            'idPesanan'    => $idPesanan,
            'kode'         => '#DW-' . (1000 + $idPesanan),
            'tipe'         => $tipe,
            'stPesanan'    => $stPesanan,
            'statusBayar'  => $statusBayar,
            'isLunas'      => $isLunas,
            'isDibatalkan' => $isDibatalkan,
            'isMenunggu'   => $isMenunggu,
            'bisaDiproses' => $bisaDiproses,
            'alamat'       => $alamat,
            'aktif'        => 'tugas',
        ]);
    }

    /**
     * Update Aksi Status Tugas oleh Kurir (Dengan Pengecekan Ketat Status Pembayaran)
     */
    public function updateAksi(int $idPesanan)
    {
        $aksi    = (string) $this->request->getPost('aksi');
        $idKurir = (int) (session()->get('id_kurir') ?? session()->get('user_id') ?? 1);

        $db = \Config\Database::connect();
        $pesananModel = new PesananModel();

        try {
            $pesanan = $pesananModel->find($idPesanan);
            if (! $pesanan) {
                return redirect()->to(base_url('kurir/tugas'))
                    ->with('errors', ['Pesanan tidak ditemukan.']);
            }

            // Validasi status pembayaran & pesanan
            $pembayaran = $db->table('pembayaran')->where('id_pesanan', $idPesanan)->get()->getRowArray();
            $statusBayar = (string) ($pembayaran['status_bayar'] ?? 'belum_dibayar');
            $statusPesanan = (string) ($pesanan['status_pesanan'] ?? 'menunggu');

            // 1. CEK: Jika pesanan dibatalkan atau pembayaran ditolak / gagal
            if ($statusPesanan === 'dibatalkan' || $statusBayar === 'gagal') {
                return redirect()->to(base_url('kurir/tugas/detail/' . $idPesanan))
                    ->with('errors', ['Pesanan ini telah DIBATALKAN / pembayaran ditolak oleh Admin. Kurir tidak dapat memproses pesanan ini!']);
            }

            // 2. CEK: Jika pembayaran belum lunas diverifikasi admin
            if ($statusBayar !== 'lunas') {
                return redirect()->to(base_url('kurir/tugas/detail/' . $idPesanan))
                    ->with('errors', ['Pembayaran pesanan ini BELUM diverifikasi lunas oleh Admin (Status: ' . ucwords(str_replace('_', ' ', $statusBayar)) . '). Kurir tidak boleh mengambil atau mengantar cucian sebelum pembayaran lunas!']);
            }

            // 3. CEK: Jika status pesanan masih 'menunggu'
            if ($statusPesanan === 'menunggu') {
                return redirect()->to(base_url('kurir/tugas/detail/' . $idPesanan))
                    ->with('errors', ['Pesanan masih menunggu konfirmasi admin. Kurir belum dapat menjalankan tugas.']);
            }

            if ($aksi === 'otw_jemput') {
                $db->table('kurir')->where('id_kurir', $idKurir)->update(['status' => 'bertugas']);
                $db->table('distribusi')->where('id_pesanan', $idPesanan)->update(['id_kurir' => $idKurir]);
                return redirect()->to(base_url('kurir/tugas/detail/' . $idPesanan))
                    ->with('success', 'Status diperbarui: Anda sedang dalam perjalanan menjemput cucian.');
            }

            if ($aksi === 'jemput_selesai') {
                $pesananModel->update($idPesanan, ['status_pesanan' => 'dijemput']);
                $db->table('distribusi')->where('id_pesanan', $idPesanan)->update([
                    'id_kurir'     => $idKurir,
                    'status_antar' => 'menunggu_kurir',
                ]);
                return redirect()->to(base_url('kurir/tugas/detail/' . $idPesanan))
                    ->with('success', 'Cucian berhasil diambil! Status pesanan kini berubah menjadi "Dijemput".');
            }

            if ($aksi === 'otw_antar') {
                $pesananModel->update($idPesanan, ['status_pesanan' => 'diantar']);
                $db->table('distribusi')
                    ->where('id_pesanan', $idPesanan)
                    ->update([
                        'id_kurir'     => $idKurir,
                        'status_antar' => 'proses_antar',
                    ]);
                $db->table('kurir')->where('id_kurir', $idKurir)->update(['status' => 'bertugas']);

                return redirect()->to(base_url('kurir/tugas/detail/' . $idPesanan))
                    ->with('success', 'Mulai mengantar pakaian. Status pesanan berubah menjadi "Sedang Diantar".');
            }

            if ($aksi === 'antar_selesai') {
                $pesananModel->update($idPesanan, ['status_pesanan' => 'selesai']);
                $db->table('distribusi')
                    ->where('id_pesanan', $idPesanan)
                    ->update([
                        'id_kurir'     => $idKurir,
                        'status_antar' => 'terkirim',
                    ]);
                $db->table('kurir')->where('id_kurir', $idKurir)->update(['status' => 'tersedia']);

                return redirect()->to(base_url('kurir/tugas/detail/' . $idPesanan))
                    ->with('success', 'Pengantaran selesai! Pesanan telah ditandai Selesai.');
            }

        } catch (\Throwable $th) {
            log_message('error', 'Gagal update aksi kurir: ' . $th->getMessage());
        }

        return redirect()->to(base_url('kurir/tugas/detail/' . $idPesanan))
            ->with('success', 'Aksi berhasil disimpan.');
    }
}
