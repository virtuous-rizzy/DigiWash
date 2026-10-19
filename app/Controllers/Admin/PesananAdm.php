<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PesananAdm extends BaseController
{
    private const STATUS_VALID = [
        'menunggu',
        'dikonfirmasi',
        'dijemput',
        'dicuci',
        'disetrika',
        'diantar',
        'dibatalkan',
    ];

    /**
     * Halaman Memproses Pesanan dialihkan ke status-laundry (dijadikan satu)
     */
    public function index()
    {
        $tab = $this->request->getGet('tab');
        $q   = $this->request->getGet('q');
        $params = [];
        if ($tab !== null && $tab !== '') {
            $params['tab'] = $tab;
        }
        if ($q !== null && $q !== '') {
            $params['q'] = $q;
        }
        $url = base_url('admin/status-laundry') . (!empty($params) ? '?' . http_build_query($params) : '');
        return redirect()->to($url);
    }

    /**
     * Halaman Mengupdate Status Laundry (Stepper)
     */
    public function statusLaundry()
    {
        $tab = (string) ($this->request->getGet('tab') ?? 'semua');
        if (! in_array($tab, ['semua', 'diproses', 'diantar', 'selesai', 'dibatalkan', 'ditolak'], true)) {
            $tab = 'semua';
        }

        $q = trim((string) ($this->request->getGet('q') ?? ''));

        $model      = new PesananModel();
        $kurirModel = new \App\Models\KurirModel();
        $pesanan    = [];
        $counts     = [
            'semua'      => 0,
            'diproses'   => 0,
            'diantar'    => 0,
            'selesai'    => 0,
            'dibatalkan' => 0,
            'ditolak'    => 0,
        ];
        $kurirList  = [];

        try {
            $pesanan   = $model->semuaPesananAdmin($tab, $q);
            $counts    = $model->hitungStatusAdmin();
            $kurirList = $kurirModel->daftarKurirAktif();
        } catch (\Throwable $th) {
            log_message('error', 'Gagal memuat pesanan admin: ' . $th->getMessage());
        }

        return view('admin/status_laundry_adm', [
            'pesanan'   => $pesanan,
            'tab'       => $tab,
            'q'         => $q,
            'counts'    => $counts,
            'kurirList' => $kurirList,
            'aktif'     => 'pesanan',
        ]);
    }

    /**
     * Update Status Laundry & Penugasan Kurir
     */
    public function updateStatus(int $idPesanan)
    {
        $status = (string) $this->request->getPost('status_pesanan');

        // Proteksi 1: Admin dilarang menyelesaikan pesanan (Hanya kurir yang menyelesaikan pengantaran)
        if ($status === 'selesai') {
            return redirect()->to(base_url('admin/status-laundry'))
                ->with('errors', ['Status "Selesai" tidak dapat diatur oleh Admin. Status ini hanya dapat diselesaikan oleh Kurir setelah pengantaran pakaian ke pelanggan berhasil.']);
        }

        if (! in_array($status, self::STATUS_VALID, true)) {
            return redirect()->back()->with('errors', ['Status laundry yang dipilih tidak valid.']);
        }

        $idKurirRaw = $this->request->getPost('id_kurir');
        $idKurir = ($idKurirRaw !== null && $idKurirRaw !== '') ? (int) $idKurirRaw : null;

        $idAdmin = (int) (session()->get('id_admin') ?? session()->get('user_id') ?? 1);
        $model   = new PesananModel();

        try {
            $pesanan = $model->find($idPesanan);
            if (! $pesanan) {
                throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
            }

            $currentStatus = (string) ($pesanan['status_pesanan'] ?? 'menunggu');

            // Proteksi 2: Pesanan yang sudah dibatalkan tidak boleh diubah
            if ($currentStatus === 'dibatalkan') {
                return redirect()->to(base_url('admin/status-laundry'))
                    ->with('errors', ['Pesanan PSN-' . str_pad((string)$idPesanan, 4, '0', STR_PAD_LEFT) . ' sudah dibatalkan/ditolak dan tidak dapat diubah statusnya lagi.']);
            }

            // Proteksi 3: Pesanan yang sudah selesai tidak boleh diubah lagi
            if ($currentStatus === 'selesai') {
                return redirect()->to(base_url('admin/status-laundry'))
                    ->with('errors', ['Pesanan PSN-' . str_pad((string)$idPesanan, 4, '0', STR_PAD_LEFT) . ' sudah berstatus Selesai dan tidak dapat diubah statusnya lagi.']);
            }

            $db = \Config\Database::connect();

            // Proteksi 4: Pengecekan status pembayaran
            $pb = $db->table('pembayaran')->where('id_pesanan', $idPesanan)->get()->getRowArray();
            $statusBayar = (string) ($pb['status_bayar'] ?? 'belum_dibayar');

            if ($statusBayar === 'gagal') {
                return redirect()->to(base_url('admin/status-laundry'))
                    ->with('errors', ['Pembayaran pesanan ini telah ditolak. Pesanan tidak dapat diproses.']);
            }

            if ($statusBayar !== 'lunas') {
                return redirect()->to(base_url('admin/status-laundry'))
                    ->with('errors', ['Pembayaran pesanan ini belum berstatus Lunas. Silakan verifikasi pembayaran di menu Transaksi terlebih dahulu.']);
            }

            // Proteksi 5: Batasi admin baru bisa mengubah status proses cucian ketika kurir sudah selesai mengantar cucian ke admin
            if (in_array($currentStatus, ['menunggu', 'dikonfirmasi'], true)) {
                if (! in_array($status, ['menunggu', 'dikonfirmasi', 'dibatalkan'], true)) {
                    return redirect()->to(base_url('admin/status-laundry'))
                        ->with('errors', ['Admin baru dapat mengubah status proses cucian setelah Kurir selesai menjemput dan mengantarkan cucian ke outlet laundry (status Cucian Dijemput).']);
                }
            }

            // Proteksi 6: Pesanan yang sedang diantar kurir tidak dapat diubah statusnya oleh admin
            if ($currentStatus === 'diantar') {
                if ($status !== 'diantar' && $status !== 'dibatalkan') {
                    return redirect()->to(base_url('admin/status-laundry'))
                        ->with('errors', ['Pakaian sedang dalam perjalanan diantar oleh Kurir ke alamat pelanggan. Status Selesai hanya dapat diselesaikan oleh Kurir setelah menyerahkan pakaian ke pelanggan.']);
                }
            }

            // Proteksi 7: Cucian yang sudah berada di outlet tidak dapat dimundurkan ke status menunggu penjemputan
            if (in_array($currentStatus, ['dijemput', 'dicuci', 'disetrika'], true)) {
                if (in_array($status, ['menunggu', 'dikonfirmasi'], true)) {
                    return redirect()->to(base_url('admin/status-laundry'))
                        ->with('errors', ['Cucian sudah berada di outlet laundry dan tidak dapat dikembalikan ke status menunggu penjemputan kurir.']);
                }
            }

            if ($status === 'dibatalkan') {
                $catatanLama = trim((string)($pesanan['catatan'] ?? ''));
                if (! str_contains(strtolower($catatanLama), 'dibatalkan') && ! str_contains(strtolower($catatanLama), 'ditolak')) {
                    $catatanLama = trim($catatanLama . ' [Dibatalkan oleh Admin]');
                }
                $model->update($idPesanan, [
                    'status_pesanan' => $status,
                    'id_admin'       => $idAdmin,
                    'catatan'        => $catatanLama,
                ]);
                $db->table('pembayaran')->where('id_pesanan', $idPesanan)->update([
                    'status_bayar' => 'gagal',
                    'id_admin'     => $idAdmin,
                ]);
            } else {
                $model->update($idPesanan, [
                    'status_pesanan' => $status,
                    'id_admin'       => $idAdmin,
                ]);
            }

            // Sinkronisasi status pengantaran dan penugasan kurir di tabel distribusi
            $dist = $db->table('distribusi')->where('id_pesanan', $idPesanan)->get()->getRowArray();
            $statusAntar = match ($status) {
                'diantar' => 'proses_antar',
                default   => 'menunggu_kurir',
            };

            if ($dist) {
                $updateDist = [];
                if ($status === 'diantar') {
                    $updateDist['status_antar'] = 'proses_antar';
                }
                if ($idKurir !== null) {
                    $updateDist['id_kurir'] = $idKurir;
                }
                if (! empty($updateDist)) {
                    $db->table('distribusi')->where('id_pesanan', $idPesanan)->update($updateDist);
                }
            } else {
                $db->table('distribusi')->insert([
                    'id_pesanan'   => $idPesanan,
                    'id_kurir'     => $idKurir,
                    'status_antar' => $statusAntar,
                ]);
            }

            // Update status ketersediaan kurir jika ada kurir yang ditugaskan
            $targetKurirId = $idKurir ?? ($dist['id_kurir'] ?? null);
            if ($targetKurirId) {
                if ($status === 'diantar') {
                    $db->table('kurir')->where('id_kurir', $targetKurirId)->update(['status' => 'bertugas']);
                } elseif (in_array($status, ['dibatalkan', 'dijemput', 'dicuci', 'disetrika'], true)) {
                    $db->table('kurir')->where('id_kurir', $targetKurirId)->update(['status' => 'tersedia']);
                }
            }

            return redirect()->to(base_url('admin/status-laundry'))
                ->with('success', 'Status pesanan PSN-' . str_pad((string)$idPesanan, 4, '0', STR_PAD_LEFT) . ' berhasil diperbarui.');
        } catch (\Throwable $th) {
            log_message('error', 'Gagal update status pesanan: ' . $th->getMessage());
            return redirect()->back()->with('errors', ['Terjadi kesalahan saat memperbarui status.']);
        }
    }
}

