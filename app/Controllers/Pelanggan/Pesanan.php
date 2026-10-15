<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\AlamatModel;
use App\Models\LayananModel;
use App\Models\PelangganModel;
use App\Models\PembayaranModel;
use App\Models\PesananModel;

class Pesanan extends BaseController
{
    /**
     * Daftar metode pembayaran yang sah + tujuan transfernya.
     * Dipakai untuk validasi (whitelist) dan ditampilkan di halaman upload bukti.
     */
    private const REKENING = [
        'Bank BCA'     => '1234-5678-9012',
        'Bank BRI'     => '0987-6543-2101',
        'Bank BNI'     => '1122-3344-5566',
        'Bank Mandiri' => '9988-7766-5544',
        'DANA'         => '0812-xxxx-xxxx',
        'GoPay'        => '0821-xxxx-xxxx',
        'ShopeePay'    => '0856-xxxx-xxxx',
    ];

    private const DIR_BUKTI = 'uploads/bukti';

    /**
     * Helper untuk mengambil ID Pelanggan yang aktif secara aman.
     */
    private function getIdPelanggan(): int
    {
        $id = (int) (session()->get('id_pelanggan') ?? session()->get('user_id') ?? 0);
        if ($id > 0) {
            return $id;
        }

        // Jika belum ada di session, gunakan pelanggan pertama di database sebagai fallback
        try {
            $db  = \Config\Database::connect();
            $plg = $db->table('pelanggan')->select('id_pelanggan')->orderBy('id_pelanggan', 'ASC')->get()->getRowArray();
            if ($plg && ! empty($plg['id_pelanggan'])) {
                session()->set('id_pelanggan', (int) $plg['id_pelanggan']);
                session()->set('user_id', (int) $plg['id_pelanggan']);
                return (int) $plg['id_pelanggan'];
            }
        } catch (\Throwable $th) {
        }

        return 1;
    }

    /**
     * Halaman Pilih Layanan
     * Mengambil daftar layanan aktif dari LayananModel secara dinamis.
     */
    public function pilihLayanan()
    {
        $layananModel = new LayananModel();
        try {
            $daftarLayanan = $layananModel->aktif();
        } catch (\Throwable $th) {
            $daftarLayanan = [];
        }

        // Fallback jika database belum ada data
        if (empty($daftarLayanan)) {
            $daftarLayanan = [
                ['id_layanan' => 1, 'nama_layanan' => 'Laundry Reguler',   'jenis_layanan' => 'kiloan', 'harga_per_kg' => 7000,  'estimasi_hari' => 3, 'deskripsi' => 'Estimasi 3 hari', 'ikon' => 'bi-basket2-fill'],
                ['id_layanan' => 2, 'nama_layanan' => 'Laundry Express',   'jenis_layanan' => 'kiloan', 'harga_per_kg' => 12000, 'estimasi_hari' => 1, 'deskripsi' => 'Estimasi 1 hari', 'ikon' => 'bi-lightning-charge-fill'],
                ['id_layanan' => 3, 'nama_layanan' => 'Laundry Satuan',    'jenis_layanan' => 'satuan', 'harga_per_kg' => 5000,  'estimasi_hari' => 2, 'deskripsi' => 'Jasa cuci satuan seperti jas, selimut, boneka', 'ikon' => 'bi-tag-fill'],
                ['id_layanan' => 4, 'nama_layanan' => 'Laundry Bed Cover', 'jenis_layanan' => 'kiloan', 'harga_per_kg' => 7000,  'estimasi_hari' => 3, 'deskripsi' => 'Cuci bed cover, sprei, selimut besar', 'ikon' => 'bi-layers-fill'],
            ];
        }

        return view('Pesanan/pilih_layanan', [
            'daftarLayanan' => $daftarLayanan,
        ]);
    }

    /**
     * Form Buat Pesanan
     * Menyesuaikan jenis layanan, harga aktual dari LayananModel, dan alamat pengguna dari AlamatModel.
     */
    public function buatPesanan()
    {
        $jenisLayanan = $this->request->getGet('layanan') ?? 'Reguler';

        if ($this->request->getMethod() === 'post') {
            $jenisLayanan = $this->request->getPost('layanan') ?? $jenisLayanan;
        }

        $layananModel = new LayananModel();
        try {
            $layananDb = $layananModel->cariByIdOrNama($jenisLayanan);
        } catch (\Throwable $th) {
            $layananDb = null;
        }

        $idLayanan = $layananDb ? (int) $layananDb['id_layanan'] : 1;
        $hargaPerKg = 7000;
        if ($layananDb) {
            $hargaPerKg = (int) ($layananDb['harga_per_kg'] > 0 ? $layananDb['harga_per_kg'] : $layananDb['harga_flat']);
        } else {
            if ($jenisLayanan === 'Express') {
                $hargaPerKg = 12000;
            } elseif ($jenisLayanan === 'BedCover') {
                $hargaPerKg = 7000;
            }
        }

        // Ambil alamat pengguna dari database
        $idPelanggan   = $this->getIdPelanggan();
        $alamatDefault = 'Jl. Melati No. 12, RT 02/RW 01, Kec. Sukmajaya Depok';

        try {
            $alamatModel = new AlamatModel();
            $alamatUtama = $alamatModel->utama($idPelanggan);
            if ($alamatUtama && ! empty($alamatUtama['alamat_lengkap'])) {
                $alamatDefault = $alamatUtama['alamat_lengkap'];
            } else {
                $pelangganModel = new PelangganModel();
                $plg = $pelangganModel->find($idPelanggan);
                if ($plg && ! empty($plg['alamat'])) {
                    $alamatDefault = $plg['alamat'];
                }
            }
        } catch (\Throwable $th) {
        }

        // Tanggal dinamis hari ini
        $bulanIndo = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tanggalHariIni = date('j') . ' ' . $bulanIndo[(int) date('n')] . ' ' . date('Y');

        $data = [
            'id_layanan'   => $idLayanan,
            'layanan'      => $jenisLayanan,
            'harga_per_kg' => $hargaPerKg,
            'alamat'       => $alamatDefault,
            'tanggal'      => $tanggalHariIni,
        ];

        return view('Pesanan/buat_pesanan', $data);
    }

    /**
     * Ringkasan Pesanan sebelum konfirmasi
     */
    public function ringkasan()
    {
        $idLayanan = $this->request->getPost('id_layanan');
        if (! $idLayanan) {
            $layananModel = new LayananModel();
            $ly = $layananModel->cariByIdOrNama($this->request->getPost('layanan'));
            $idLayanan = $ly ? $ly['id_layanan'] : 1;
        }

        $data = [
            'id_layanan'    => $idLayanan,
            'id_pesanan'    => 'PSN-' . date('Ymd') . '-001',
            'layanan'       => $this->request->getPost('layanan'),
            'berat'         => $this->request->getPost('berat'),
            'total'         => $this->request->getPost('total_harga'),
            'alamat'        => $this->request->getPost('alamat'),
            'tanggal'       => $this->request->getPost('tanggal_penjemputan'),
            'catatan'       => $this->request->getPost('catatan'),
            'detail_satuan' => $this->request->getPost('detail_satuan'),
        ];

        return view('Pesanan/ringkasan', $data);
    }

    /**
     * Konfirmasi Pesanan:
     * SEGERA INSERT data ke tabel pesanan dan pembayaran di database
     * agar pesanan langsung tercatat dan muncul di Riwayat Pesanan pelanggan!
     */
    public function konfirmasiPesanan()
    {
        $session = session();

        // Pesanan baru → bukti & metode dari pesanan sebelumnya tidak boleh terbawa
        $this->hapusBuktiTersimpan();
        $session->remove('pesanan_metode_bayar');

        $idPelanggan = $this->getIdPelanggan();

        $layananModel = new LayananModel();
        $idLayanan = (int) $this->request->getPost('id_layanan');
        if ($idLayanan <= 0) {
            $ly = $layananModel->cariByIdOrNama($this->request->getPost('layanan'));
            $idLayanan = $ly ? (int) $ly['id_layanan'] : 1;
        }

        $berat   = (float) ($this->request->getPost('berat') ?? 1.0);
        $total   = (float) ($this->request->getPost('total') ?? 0);
        $alamat  = (string) ($this->request->getPost('alamat') ?? '-');
        $catatan = (string) ($this->request->getPost('catatan') ?? '');
        $satuan  = (string) ($this->request->getPost('detail_satuan') ?? '');

        $idPesananDb    = null;
        $idPembayaranDb = null;

        try {
            $pesananModel = new PesananModel();
            $idPesananDb  = $pesananModel->insert([
                'id_pelanggan'       => $idPelanggan,
                'id_layanan'         => $idLayanan,
                'tanggal'            => date('Y-m-d H:i:s'),
                'berat'              => $berat,
                'alamat_pengambilan' => $alamat,
                'alamat_pengantaran' => $alamat,
                'status_pesanan'     => 'menunggu',
                'catatan'            => $catatan,
                'detail_satuan'      => $satuan,
            ]);

            if ($idPesananDb) {
                $pembayaranModel = new PembayaranModel();
                $idPembayaranDb  = $pembayaranModel->insert([
                    'id_pesanan'    => $idPesananDb,
                    'metode'        => 'Belum Dipilih',
                    'status_bayar'  => 'belum_dibayar',
                    'bukti_bayar'   => null,
                    'total_bayar'   => $total,
                    'tanggal_bayar' => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (\Throwable $th) {
            log_message('error', 'Gagal insert pesanan: ' . $th->getMessage());
        }

        $formattedIdPesanan = 'PSN-' . date('Ymd') . '-' . str_pad((string) ($idPesananDb ?? 1), 4, '0', STR_PAD_LEFT);
        $formattedIdBayar   = 'PAY-' . date('dmY') . '-' . str_pad((string) ($idPembayaranDb ?? 1), 3, '0', STR_PAD_LEFT);

        $session->set([
            'pesanan_id_db'         => $idPesananDb,
            'pembayaran_id_db'      => $idPembayaranDb,
            'pesanan_id_pesanan'    => $formattedIdPesanan,
            'pesanan_id_bayar'      => $formattedIdBayar,
            'pesanan_id_layanan'    => $idLayanan,
            'pesanan_layanan'       => $this->request->getPost('layanan'),
            'pesanan_berat'         => $berat,
            'pesanan_total'         => $total,
            'pesanan_alamat'        => $alamat,
            'pesanan_tanggal'       => $this->request->getPost('tanggal'),
            'pesanan_catatan'       => $catatan,
            'pesanan_detail_satuan' => $satuan,
        ]);

        return redirect()->to(base_url('pesanan/pembayaran'));
    }

    /**
     * Halaman pilih metode pembayaran
     */
    public function pembayaran()
    {
        $session = session();
        $data = [
            'id_pesanan' => $session->get('pesanan_id_pesanan') ?? ('PSN-' . date('Ymd') . '-001'),
            'total'      => $session->get('pesanan_total') ?? 0,
            'layanan'    => $session->get('pesanan_layanan') ?? '-',
        ];
        return view('Pesanan/pembayaran', $data);
    }

    /**
     * Proses metode pembayaran → update metode di database dan lanjut ke upload bukti
     */
    public function prosesPembayaran()
    {
        $session = session();
        $metode  = (string) $this->request->getPost('metode_bayar');

        if (! array_key_exists($metode, self::REKENING)) {
            return redirect()->to(base_url('pesanan/pembayaran'))
                ->with('errors', ['Pilih metode pembayaran yang tersedia.']);
        }

        if ($metode !== (string) $session->get('pesanan_metode_bayar')) {
            $this->hapusBuktiTersimpan();
        }

        $session->set('pesanan_metode_bayar', $metode);

        // Update record pembayaran di database jika ada
        $idPembayaranDb = (int) $session->get('pembayaran_id_db');
        if ($idPembayaranDb > 0) {
            try {
                $pembayaranModel = new PembayaranModel();
                $pembayaranModel->update($idPembayaranDb, ['metode' => $metode]);
            } catch (\Throwable $th) {
            }
        }

        return redirect()->to(base_url('pesanan/upload-bukti'));
    }

    /**
     * Halaman upload bukti pembayaran
     */
    public function uploadBukti()
    {
        $session = session();

        if ($session->get('pesanan_total') === null) {
            return redirect()->to(base_url('pesanan/pilih-layanan'));
        }

        $metode = (string) $session->get('pesanan_metode_bayar');
        if (! array_key_exists($metode, self::REKENING)) {
            return redirect()->to(base_url('pesanan/pembayaran'));
        }

        $data = [
            'id_pesanan' => $session->get('pesanan_id_pesanan') ?? ('PSN-' . date('Ymd') . '-001'),
            'layanan'    => $session->get('pesanan_layanan') ?? '-',
            'metode'     => $metode,
            'rekening'   => self::REKENING[$metode],
            'total'      => (int) $session->get('pesanan_total'),
            'bukti_url'  => $this->urlBukti(),
        ];

        return view('Pesanan/upload_bukti', $data);
    }

    /**
     * Simpan file bukti transfer dan update status bayar di database menjadi menunggu_verifikasi
     */
    public function simpanBukti()
    {
        $session = session();

        if ($session->get('pesanan_total') === null) {
            return redirect()->to(base_url('pesanan/pilih-layanan'));
        }
        if (! array_key_exists((string) $session->get('pesanan_metode_bayar'), self::REKENING)) {
            return redirect()->to(base_url('pesanan/pembayaran'));
        }

        $rules = [
            'bukti' => [
                'label'  => 'Bukti pembayaran',
                'rules'  => 'uploaded[bukti]|is_image[bukti]|mime_in[bukti,image/jpg,image/jpeg,image/png,image/webp]|max_size[bukti,2048]',
                'errors' => [
                    'uploaded' => 'Pilih foto bukti pembayaran terlebih dahulu.',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in'  => 'Format file harus JPG, PNG, atau WEBP.',
                    'max_size' => 'Ukuran file maksimal 2 MB.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(base_url('pesanan/upload-bukti'))
                ->with('errors', $this->validator->getErrors());
        }

        $dir = FCPATH . self::DIR_BUKTI;
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $file = $this->request->getFile('bukti');
        $nama = $file->getRandomName();
        $file->move($dir, $nama);

        // Upload ulang → hapus file lama supaya tidak numpuk
        $this->hapusBuktiTersimpan();
        $session->set('pesanan_bukti', $nama);

        // ── UPDATE RECORD DI DATABASE ────────────
        $idPembayaranDb = (int) $session->get('pembayaran_id_db');
        $pembayaranModel = new PembayaranModel();

        if ($idPembayaranDb > 0) {
            try {
                $pembayaranModel->update($idPembayaranDb, [
                    'metode'        => (string) $session->get('pesanan_metode_bayar'),
                    'status_bayar'  => 'menunggu_verifikasi',
                    'bukti_bayar'   => $nama,
                    'tanggal_bayar' => date('Y-m-d H:i:s'),
                ]);
            } catch (\Throwable $th) {
                log_message('error', 'Gagal update pembayaran: ' . $th->getMessage());
            }
        } else {
            // Jika record belum dibuat di konfirmasiPesanan, buat sekarang
            try {
                $idPelanggan  = $this->getIdPelanggan();
                $layananModel = new LayananModel();
                $idLayanan    = (int) $session->get('pesanan_id_layanan');
                if ($idLayanan <= 0) {
                    $ly = $layananModel->cariByIdOrNama($session->get('pesanan_layanan'));
                    $idLayanan = $ly ? (int) $ly['id_layanan'] : 1;
                }

                $pesananModel = new PesananModel();
                $idPesananDb  = $pesananModel->insert([
                    'id_pelanggan'       => $idPelanggan,
                    'id_layanan'         => $idLayanan,
                    'tanggal'            => date('Y-m-d H:i:s'),
                    'berat'              => (float) ($session->get('pesanan_berat') ?? 1.0),
                    'alamat_pengambilan' => (string) ($session->get('pesanan_alamat') ?? '-'),
                    'alamat_pengantaran' => (string) ($session->get('pesanan_alamat') ?? '-'),
                    'status_pesanan'     => 'menunggu',
                    'catatan'            => (string) ($session->get('pesanan_catatan') ?? ''),
                    'detail_satuan'      => (string) ($session->get('pesanan_detail_satuan') ?? ''),
                ]);

                if ($idPesananDb) {
                    $idPembayaranDb = $pembayaranModel->insert([
                        'id_pesanan'    => $idPesananDb,
                        'metode'        => (string) $session->get('pesanan_metode_bayar'),
                        'status_bayar'  => 'menunggu_verifikasi',
                        'bukti_bayar'   => $nama,
                        'total_bayar'   => (float) ($session->get('pesanan_total') ?? 0),
                        'tanggal_bayar' => date('Y-m-d H:i:s'),
                    ]);

                    $formattedIdPesanan = 'PSN-' . date('Ymd') . '-' . str_pad((string) $idPesananDb, 4, '0', STR_PAD_LEFT);
                    $formattedIdBayar   = 'PAY-' . date('dmY') . '-' . str_pad((string) $idPembayaranDb, 3, '0', STR_PAD_LEFT);
                    $session->set('pesanan_id_db', $idPesananDb);
                    $session->set('pembayaran_id_db', $idPembayaranDb);
                    $session->set('pesanan_id_pesanan', $formattedIdPesanan);
                    $session->set('pesanan_id_bayar', $formattedIdBayar);
                }
            } catch (\Throwable $th) {
                log_message('error', 'Gagal simpan pesanan: ' . $th->getMessage());
            }
        }

        return redirect()->to(base_url('pesanan/status-pembayaran'));
    }

    /**
     * Halaman status pembayaran
     */
    public function statusPembayaran()
    {
        $session = session();

        if ($session->get('pesanan_total') === null) {
            return redirect()->to(base_url('pesanan/pilih-layanan'));
        }
        if (! array_key_exists((string) $session->get('pesanan_metode_bayar'), self::REKENING)) {
            return redirect()->to(base_url('pesanan/pembayaran'));
        }

        $buktiUrl = $this->urlBukti();
        if ($buktiUrl === null) {
            return redirect()->to(base_url('pesanan/upload-bukti'))
                ->with('errors', ['Unggah bukti pembayaran dulu sebelum melihat status.']);
        }

        $data = [
            'id_pembayaran' => $session->get('pesanan_id_bayar') ?? ('PAY-' . date('dmY') . '-001'),
            'id_pesanan'    => $session->get('pesanan_id_pesanan') ?? ('PSN-' . date('Ymd') . '-001'),
            'metode'        => $session->get('pesanan_metode_bayar'),
            'total'         => $session->get('pesanan_total') ?? 0,
            'bukti_url'     => $buktiUrl,
        ];
        return view('Pesanan/status_pembayaran', $data);
    }

    /**
     * Halaman tracking pesanan
     */
    public function tracking()
    {
        $session = session();
        $data = [
            'id_pesanan' => $session->get('pesanan_id_pesanan') ?? ('PSN-' . date('Ymd') . '-001'),
            'layanan'    => $session->get('pesanan_layanan') ?? 'Reguler',
        ];
        return view('Pesanan/tracking', $data);
    }

    /**
     * Riwayat Pesanan
     * Mengambil pesanan langsung dari PesananModel berdasarkan session pelanggan aktif,
     * dan mengelompokkan ke tab Semua, Diproses, Selesai, dan Dibatalkan.
     */
    public function riwayatPesanan()
    {
        $idPelanggan = $this->getIdPelanggan();

        $semua = [];
        try {
            $model = new PesananModel();
            $semua = $model->milikPelanggan($idPelanggan);
        } catch (\Throwable $th) {
            log_message('error', 'Gagal ambil riwayat: ' . $th->getMessage());
            $semua = [];
        }

        // Pisahkan per tab berdasarkan status_pesanan
        $statusDiproses   = ['menunggu', 'dikonfirmasi', 'dijemput', 'dicuci', 'disetrika', 'diantar'];
        $statusSelesai    = ['selesai'];
        $statusDibatalkan = ['dibatalkan'];

        $diproses   = array_filter($semua, static fn($p) => in_array($p['status_pesanan'] ?? '', $statusDiproses, true));
        $selesai    = array_filter($semua, static fn($p) => in_array($p['status_pesanan'] ?? '', $statusSelesai, true));
        $dibatalkan = array_filter($semua, static fn($p) => in_array($p['status_pesanan'] ?? '', $statusDibatalkan, true));

        return view('Pesanan/riwayat_pesanan', [
            'semua'      => array_values($semua),
            'diproses'   => array_values($diproses),
            'selesai'    => array_values($selesai),
            'dibatalkan' => array_values($dibatalkan),
        ]);
    }

    /* ── Helper bukti pembayaran ─────────────────────────────── */

    /** URL bukti yang sudah diunggah, atau null kalau belum ada / filenya hilang. */
    private function urlBukti(): ?string
    {
        $nama = (string) session()->get('pesanan_bukti');

        if ($nama === '' || ! is_file(FCPATH . self::DIR_BUKTI . '/' . $nama)) {
            return null;
        }

        return base_url(self::DIR_BUKTI . '/' . $nama);
    }

    /** Hapus file bukti yang tercatat di session (kalau ada) lalu bersihkan session-nya. */
    private function hapusBuktiTersimpan(): void
    {
        $nama = (string) session()->get('pesanan_bukti');

        if ($nama !== '') {
            $path = FCPATH . self::DIR_BUKTI . '/' . basename($nama);
            if (is_file($path)) {
                @unlink($path);
            }
        }

        session()->remove('pesanan_bukti');
    }
}