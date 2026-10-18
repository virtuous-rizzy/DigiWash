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
        'selesai',
        'dibatalkan',
    ];

    /**
     * Halaman Memproses Pesanan (Sesuai Mockup)
     */
    public function index()
    {
        $q      = trim((string) ($this->request->getGet('q') ?? ''));
        $status = trim((string) ($this->request->getGet('status') ?? 'semua'));
        $bayar  = trim((string) ($this->request->getGet('bayar') ?? 'semua'));

        $model      = new PesananModel();
        $kurirModel = new \App\Models\KurirModel();
        $pesanan    = [];
        $kurirList  = [];

        try {
            $builder = $model->db->table('pesanan p')
                ->select([
                    'p.id_pesanan',
                    'p.id_pelanggan',
                    'p.id_layanan',
                    'p.tanggal',
                    'p.berat',
                    'p.alamat_pengambilan',
                    'p.alamat_pengantaran',
                    'p.status_pesanan',
                    'p.catatan',
                    'p.detail_satuan',
                    'p.created_at',
                    'pl.nama AS nama_pelanggan',
                    'pl.no_hp AS no_hp_pelanggan',
                    'pl.foto AS foto_pelanggan',
                    'l.nama_layanan',
                    'l.jenis_layanan',
                    'l.harga_per_kg',
                    'l.harga_flat',
                    'l.estimasi_hari',
                    'pb.status_bayar',
                    'pb.metode',
                    'pb.total_bayar',
                    'd.id_kurir',
                    'k.nama AS nama_kurir',
                    'k.no_hp AS no_hp_kurir',
                ])
                ->join('pelanggan pl',  'pl.id_pelanggan = p.id_pelanggan', 'left')
                ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
                ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
                ->join('distribusi d',  'd.id_pesanan    = p.id_pesanan', 'left')
                ->join('kurir k',       'k.id_kurir      = d.id_kurir', 'left');

            // Filter status pesanan
            if ($status !== '' && $status !== 'semua') {
                if ($status === 'diproses') {
                    $builder->whereIn('p.status_pesanan', ['menunggu', 'dikonfirmasi', 'dijemput', 'dicuci', 'disetrika']);
                } else {
                    $builder->where('p.status_pesanan', $status);
                }
            }

            // Filter status bayar
            if ($bayar !== '' && $bayar !== 'semua') {
                if ($bayar === 'lunas') {
                    $builder->where('pb.status_bayar', 'lunas');
                } elseif ($bayar === 'belum_lunas') {
                    $builder->where('pb.status_bayar !=', 'lunas');
                }
            }

            // Search query (cari no. transaksi / ID / pelanggan / layanan)
            if ($q !== '') {
                $builder->groupStart()
                    ->like('pl.nama', $q)
                    ->orLike('p.id_pesanan', $q)
                    ->orLike('l.nama_layanan', $q)
                    ->orLike('pl.no_hp', $q)
                    ->groupEnd();
            }

            $pesanan   = $builder->orderBy('p.id_pesanan', 'DESC')->get()->getResultArray();
            $kurirList = $kurirModel->daftarKurirAktif();
        } catch (\Throwable $th) {
            log_message('error', 'Gagal memuat memproses pesanan admin: ' . $th->getMessage());
        }

        return view('admin/pesanan_adm', [
            'pesanan'      => $pesanan,
            'q'            => $q,
            'statusFilter' => $status,
            'bayarFilter'  => $bayar,
            'kurirList'    => $kurirList,
            'aktif'        => 'pesanan',
        ]);
    }

    /**
     * Halaman Mengupdate Status Laundry (Stepper)
     */
    public function statusLaundry()
    {
        $tab = (string) ($this->request->getGet('tab') ?? 'semua');
        if (! in_array($tab, ['semua', 'diproses', 'diantar', 'selesai'], true)) {
            $tab = 'semua';
        }

        $q = trim((string) ($this->request->getGet('q') ?? ''));

        $model      = new PesananModel();
        $kurirModel = new \App\Models\KurirModel();
        $pesanan    = [];
        $counts     = [
            'semua'    => 0,
            'diproses' => 0,
            'diantar'  => 0,
            'selesai'  => 0,
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

            // Proteksi 1: Pesanan yang sudah dibatalkan tidak boleh diubah
            if ($currentStatus === 'dibatalkan') {
                return redirect()->to(base_url('admin/pesanan'))
                    ->with('errors', ['Pesanan PSN-' . str_pad((string)$idPesanan, 4, '0', STR_PAD_LEFT) . ' sudah dibatalkan/ditolak dan tidak dapat diubah statusnya lagi.']);
            }

            // Proteksi 2: Pesanan yang sudah selesai tidak boleh diubah lagi
            if ($currentStatus === 'selesai') {
                return redirect()->to(base_url('admin/pesanan'))
                    ->with('errors', ['Pesanan PSN-' . str_pad((string)$idPesanan, 4, '0', STR_PAD_LEFT) . ' sudah berstatus Selesai dan tidak dapat diubah statusnya lagi.']);
            }

            $db = \Config\Database::connect();

            // Proteksi 3: Pengecekan status pembayaran
            $pb = $db->table('pembayaran')->where('id_pesanan', $idPesanan)->get()->getRowArray();
            $statusBayar = (string) ($pb['status_bayar'] ?? 'belum_dibayar');

            if ($statusBayar === 'gagal') {
                return redirect()->to(base_url('admin/pesanan'))
                    ->with('errors', ['Pembayaran pesanan ini telah ditolak. Pesanan tidak dapat diproses.']);
            }

            if ($statusBayar !== 'lunas') {
                return redirect()->to(base_url('admin/pesanan'))
                    ->with('errors', ['Pembayaran pesanan ini belum berstatus Lunas. Silakan verifikasi pembayaran di menu Transaksi terlebih dahulu.']);
            }

            $model->update($idPesanan, [
                'status_pesanan' => $status,
                'id_admin'       => $idAdmin,
            ]);

            // Jika admin membatalkan pesanan, sinkronkan juga ke tabel pembayaran
            if ($status === 'dibatalkan') {
                $db->table('pembayaran')->where('id_pesanan', $idPesanan)->update([
                    'status_bayar' => 'gagal',
                    'id_admin'     => $idAdmin,
                ]);
            }

            // Sinkronisasi status pengantaran dan penugasan kurir di tabel distribusi
            $dist = $db->table('distribusi')->where('id_pesanan', $idPesanan)->get()->getRowArray();
            $statusAntar = match ($status) {
                'diantar' => 'proses_antar',
                'selesai' => 'terkirim',
                default   => 'menunggu_kurir',
            };

            if ($dist) {
                $updateDist = ['status_antar' => $statusAntar];
                if ($idKurir !== null) {
                    $updateDist['id_kurir'] = $idKurir;
                }
                $db->table('distribusi')->where('id_pesanan', $idPesanan)->update($updateDist);
            } else {
                $db->table('distribusi')->insert([
                    'id_pesanan'   => $idPesanan,
                    'id_kurir'     => $idKurir,
                    'status_antar' => $statusAntar,
                    'biaya'        => 0,
                ]);
            }

            // Update status ketersediaan kurir jika ada kurir yang ditugaskan
            $targetKurirId = $idKurir ?? ($dist['id_kurir'] ?? null);
            if ($targetKurirId) {
                if ($status === 'diantar' || $status === 'dijemput') {
                    $db->table('kurir')->where('id_kurir', $targetKurirId)->update(['status' => 'bertugas']);
                } elseif ($status === 'selesai' || $status === 'dibatalkan') {
                    $db->table('kurir')->where('id_kurir', $targetKurirId)->update(['status' => 'tersedia']);
                }
            }

            return redirect()->to(base_url('admin/pesanan'))
                ->with('success', 'Status pesanan PSN-' . str_pad((string)$idPesanan, 4, '0', STR_PAD_LEFT) . ' berhasil diperbarui.');
        } catch (\Throwable $th) {
            log_message('error', 'Gagal update status pesanan: ' . $th->getMessage());
            return redirect()->back()->with('errors', ['Terjadi kesalahan saat memperbarui status.']);
        }
    }
}
