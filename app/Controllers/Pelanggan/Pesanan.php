<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
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
     * Menyesuaikan jenis layanan, harga aktual dari LayananModel, dan alamat pengguna dari tabel pelanggan.
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

        // Ambil alamat pengguna langsung dari database (tabel pelanggan)
        $idPelanggan        = $this->getIdPelanggan();
        $alamatDefault      = '';
        $alamatAlternatif   = '';

        try {
            $pelangganModel = new PelangganModel();
            $plg = $pelangganModel->find($idPelanggan);
            if ($plg) {
                $alamatDefault    = (string) ($plg['alamat']              ?? '');
                $alamatAlternatif = (string) ($plg['alamat_alternatif']   ?? '');
            }
        } catch (\Throwable $th) {
        }

        // Tanggal dinamis hari ini
        $bulanIndo = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tanggalHariIni = date('j') . ' ' . $bulanIndo[(int) date('n')] . ' ' . date('Y');

        $data = [
            'id_layanan'          => $idLayanan,
            'layanan'             => $jenisLayanan,
            'harga_per_kg'        => $hargaPerKg,
            'alamat'              => $alamatDefault,
            'alamat_alternatif'   => $alamatAlternatif,
            'tanggal'             => $tanggalHariIni,
            'tanggalYmd'          => date('Y-m-d'),
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

        $tglPost = (string) ($this->request->getPost('tanggal_penjemputan') ?? $this->request->getPost('tanggal') ?? '');
        $tanggalTampil = $tglPost;
        if ($tglPost !== '' && strtotime($tglPost)) {
            $bulanIndo = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $tglObj = strtotime($tglPost);
            $tanggalTampil = date('j', $tglObj) . ' ' . $bulanIndo[(int) date('n', $tglObj)] . ' ' . date('Y', $tglObj);
        }

        $data = [
            'id_layanan'    => $idLayanan,
            'id_pesanan'    => 'PSN-' . date('Ymd') . '-001',
            'layanan'       => $this->request->getPost('layanan'),
            'berat'         => $this->request->getPost('berat'),
            'total'         => $this->request->getPost('total_harga'),
            'alamat'        => $this->request->getPost('alamat'),
            'tanggal'       => $tanggalTampil,
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

        $tglPost = (string) ($this->request->getPost('tanggal') ?? '');
        $tglDb   = date('Y-m-d H:i:s');
        if ($tglPost !== '') {
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglPost)) {
                $tglDb = $tglPost . ' ' . date('H:i:s');
            } else {
                $bulanMap = [
                    'Januari' => '01', 'Februari' => '02', 'Maret' => '03', 'April' => '04',
                    'Mei' => '05', 'Juni' => '06', 'Juli' => '07', 'Agustus' => '08',
                    'September' => '09', 'Oktober' => '10', 'November' => '11', 'Desember' => '12',
                ];
                foreach ($bulanMap as $namaBln => $blnAngka) {
                    if (stripos($tglPost, $namaBln) !== false) {
                        $parts = explode(' ', trim($tglPost));
                        if (count($parts) >= 3) {
                            $tglDb = $parts[2] . '-' . $blnAngka . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT) . ' ' . date('H:i:s');
                        }
                        break;
                    }
                }
            }
        }

        try {
            $pesananModel = new PesananModel();
            $idPesananDb  = $pesananModel->insert([
                'id_pelanggan'       => $idPelanggan,
                'id_layanan'         => $idLayanan,
                'tanggal'            => $tglDb,
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
     * Helper untuk memuat data pesanan milik pelanggan dari database ke session
     */
    private function muatDataPesanan(int $idPesanan): ?array
    {
        $idPelanggan = $this->getIdPelanggan();
        if ($idPesanan <= 0 || $idPelanggan <= 0) {
            return null;
        }

        $pesananModel = new PesananModel();
        $detail = $pesananModel->detailPesanan($idPesanan, $idPelanggan);
        if (! $detail) {
            return null;
        }

        $formattedIdPesanan = 'PSN-' . date('Ymd', strtotime((string)($detail['tanggal'] ?? 'now'))) . '-' . str_pad((string) $detail['id_pesanan'], 4, '0', STR_PAD_LEFT);
        $formattedIdBayar   = 'PAY-' . date('dmY') . '-' . str_pad((string) ($detail['id_pembayaran'] ?? 1), 3, '0', STR_PAD_LEFT);

        session()->set([
            'pesanan_id_db'         => (int) $detail['id_pesanan'],
            'pembayaran_id_db'      => (int) ($detail['id_pembayaran'] ?? 0),
            'pesanan_id_pesanan'    => $formattedIdPesanan,
            'pesanan_id_bayar'      => $formattedIdBayar,
            'pesanan_id_layanan'    => (int) ($detail['id_layanan'] ?? 0),
            'pesanan_layanan'       => $detail['nama_layanan'] ?? '-',
            'pesanan_berat'         => (float) ($detail['berat'] ?? 1.0),
            'pesanan_total'         => (float) ($detail['total_harga'] ?? 0),
            'pesanan_alamat'        => $detail['alamat_pengambilan'] ?? '-',
            'pesanan_tanggal'       => $detail['tanggal'] ?? '',
            'pesanan_catatan'       => $detail['catatan'] ?? '',
            'pesanan_metode_bayar'  => $detail['metode'] ?? '',
        ]);

        if (! empty($detail['bukti_bayar'])) {
            session()->set('pesanan_bukti', $detail['bukti_bayar']);
        }

        return $detail;
    }

    /**
     * Halaman pilih metode pembayaran
     */
    public function pembayaran($id = null)
    {
        $session   = session();
        $idPesanan = (int) ($id ?? $this->request->getGet('id_pesanan') ?? $session->get('pesanan_id_db') ?? 0);

        $metodeTerpilih = '';
        if ($idPesanan > 0) {
            $detail = $this->muatDataPesanan($idPesanan);
            if ($detail) {
                // Jika pesanan sudah dibatalkan, jangan boleh bayar
                if (($detail['status_pesanan'] ?? '') === 'dibatalkan') {
                    return redirect()->to(base_url('pesanan/riwayat'))
                        ->with('errors', ['Pesanan ini telah dibatalkan dan tidak dapat dilakukan pembayaran.']);
                }

                // Jika pesanan sudah lunas atau menunggu verifikasi, arahkan langsung ke tracking
                $stBayar = strtolower((string) ($detail['status_bayar'] ?? ''));
                if (in_array($stBayar, ['lunas', 'menunggu_verifikasi'], true)) {
                    return redirect()->to(base_url('pesanan/tracking/' . $idPesanan));
                }

                $metodeTerpilih = (string) ($detail['metode'] ?? '');
            }
        }

        if ($metodeTerpilih === '' || $metodeTerpilih === 'Belum Dipilih') {
            $metodeTerpilih = (string) ($session->get('pesanan_metode_bayar') ?? '');
        }

        $idDb = (int) ($session->get('pesanan_id_db') ?? $idPesanan);
        $data = [
            'id_pesanan_db'   => $idDb,
            'id_pesanan'      => $session->get('pesanan_id_pesanan') ?? ('PSN-' . date('Ymd') . '-001'),
            'total'           => $session->get('pesanan_total') ?? 0,
            'layanan'         => $session->get('pesanan_layanan') ?? '-',
            'metode_terpilih' => $metodeTerpilih,
        ];
        return view('Pesanan/pembayaran', $data);
    }

    /**
     * Proses metode pembayaran → update metode di database dan lanjut ke upload bukti
     */
    public function prosesPembayaran($id = null)
    {
        $session   = session();
        $idPesanan = (int) ($id ?? $this->request->getPost('id_pesanan') ?? $session->get('pesanan_id_db') ?? 0);
        if ($idPesanan > 0) {
            $detail = $this->muatDataPesanan($idPesanan);
            if ($detail && ($detail['status_pesanan'] ?? '') === 'dibatalkan') {
                return redirect()->to(base_url('pesanan/riwayat'))
                    ->with('errors', ['Pesanan ini telah dibatalkan dan tidak dapat diproses lagi.']);
            }
        }

        $metode = (string) $this->request->getPost('metode_bayar');

        if (! array_key_exists($metode, self::REKENING)) {
            $redirectUrl = base_url('pesanan/pembayaran' . ($idPesanan > 0 ? '/' . $idPesanan : ''));
            return redirect()->to($redirectUrl)
                ->with('errors', ['Pilih metode pembayaran yang tersedia.']);
        }

        $metodeLama    = (string) $session->get('pesanan_metode_bayar');
        $metodeBerubah = ($metode !== $metodeLama);

        if ($metodeBerubah) {
            $this->hapusBuktiTersimpan();
        }

        $session->set('pesanan_metode_bayar', $metode);

        // Update record pembayaran di database jika ada
        $idPembayaranDb  = (int) $session->get('pembayaran_id_db');
        $idPesananDb     = (int) ($session->get('pesanan_id_db') ?? $idPesanan);
        $pembayaranModel = new PembayaranModel();

        if ($idPembayaranDb <= 0 && $idPesananDb > 0) {
            try {
                $existingBayar = $pembayaranModel->where('id_pesanan', $idPesananDb)->first();
                if ($existingBayar) {
                    $idPembayaranDb = (int) $existingBayar['id_pembayaran'];
                    $session->set('pembayaran_id_db', $idPembayaranDb);
                }
            } catch (\Throwable $th) {
            }
        }

        if ($idPembayaranDb > 0) {
            try {
                $updateData = ['metode' => $metode];
                if ($metodeBerubah) {
                    $updateData['bukti_bayar'] = null;
                }
                $pembayaranModel->update($idPembayaranDb, $updateData);
            } catch (\Throwable $th) {
            }
        }

        return redirect()->to(base_url('pesanan/upload-bukti' . ($idPesananDb > 0 ? '/' . $idPesananDb : '')));
    }

    /**
     * Halaman upload bukti pembayaran
     */
    public function uploadBukti($id = null)
    {
        $session   = session();
        $idPesanan = (int) ($id ?? $this->request->getGet('id_pesanan') ?? $session->get('pesanan_id_db') ?? 0);

        if ($idPesanan > 0) {
            $detail = $this->muatDataPesanan($idPesanan);
            if ($detail) {
                if (($detail['status_pesanan'] ?? '') === 'dibatalkan') {
                    return redirect()->to(base_url('pesanan/riwayat'))
                        ->with('errors', ['Pesanan ini telah dibatalkan dan tidak dapat mengunggah bukti pembayaran.']);
                }
                $stBayar = strtolower((string) ($detail['status_bayar'] ?? ''));
                if (in_array($stBayar, ['lunas', 'menunggu_verifikasi'], true)) {
                    return redirect()->to(base_url('pesanan/tracking/' . $idPesanan));
                }
            }
        }

        if ($session->get('pesanan_total') === null && $idPesanan <= 0) {
            return redirect()->to(base_url('pesanan/pilih-layanan'));
        }

        $metode = (string) $session->get('pesanan_metode_bayar');
        if (! array_key_exists($metode, self::REKENING)) {
            return redirect()->to(base_url('pesanan/pembayaran' . ($idPesanan > 0 ? '/' . $idPesanan : '')));
        }

        $idDb = (int) ($session->get('pesanan_id_db') ?? $idPesanan);
        $data = [
            'id_pesanan_db' => $idDb,
            'id_pesanan'    => $session->get('pesanan_id_pesanan') ?? ('PSN-' . date('Ymd') . '-001'),
            'layanan'       => $session->get('pesanan_layanan') ?? '-',
            'metode'        => $metode,
            'rekening'      => self::REKENING[$metode],
            'total'         => (int) $session->get('pesanan_total'),
            'bukti_url'     => $this->urlBukti(),
        ];

        return view('Pesanan/upload_bukti', $data);
    }

    /**
     * Simpan file bukti transfer dan update status bayar di database menjadi menunggu_verifikasi
     */
    public function simpanBukti($id = null)
    {
        $session   = session();
        $idPesanan = (int) ($id ?? $this->request->getPost('id_pesanan') ?? $session->get('pesanan_id_db') ?? 0);
        if ($idPesanan > 0) {
            $detail = $this->muatDataPesanan($idPesanan);
            if ($detail && ($detail['status_pesanan'] ?? '') === 'dibatalkan') {
                return redirect()->to(base_url('pesanan/riwayat'))
                    ->with('errors', ['Pesanan ini telah dibatalkan dan tidak dapat mengunggah bukti pembayaran.']);
            }
        }

        if ($session->get('pesanan_total') === null && $idPesanan <= 0) {
            return redirect()->to(base_url('pesanan/pilih-layanan'));
        }
        if (! array_key_exists((string) $session->get('pesanan_metode_bayar'), self::REKENING)) {
            return redirect()->to(base_url('pesanan/pembayaran' . ($idPesanan > 0 ? '/' . $idPesanan : '')));
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
            return redirect()->to(base_url('pesanan/upload-bukti' . ($idPesanan > 0 ? '/' . $idPesanan : '')))
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
        $idPembayaranDb  = (int) $session->get('pembayaran_id_db');
        $idPesananDb     = (int) ($session->get('pesanan_id_db') ?? $idPesanan);
        $pembayaranModel = new PembayaranModel();

        if ($idPembayaranDb <= 0 && $idPesananDb > 0) {
            try {
                $existingBayar = $pembayaranModel->where('id_pesanan', $idPesananDb)->first();
                if ($existingBayar) {
                    $idPembayaranDb = (int) $existingBayar['id_pembayaran'];
                    $session->set('pembayaran_id_db', $idPembayaranDb);
                }
            } catch (\Throwable $th) {
            }
        }

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
                $newPesananDb = $pesananModel->insert([
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

                if ($newPesananDb) {
                    $idPesananDb    = (int) $newPesananDb;
                    $idPembayaranDb = (int) $pembayaranModel->insert([
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

        return redirect()->to(base_url('pesanan/status-pembayaran' . ($idPesananDb > 0 ? '/' . $idPesananDb : '')));
    }

    /**
     * Halaman status pembayaran
     */
    public function statusPembayaran($id = null)
    {
        $session   = session();
        $idPesanan = (int) ($id ?? $this->request->getGet('id_pesanan') ?? $session->get('pesanan_id_db') ?? 0);

        if ($idPesanan > 0) {
            $detail = $this->muatDataPesanan($idPesanan);
            if ($detail && ($detail['status_pesanan'] ?? '') === 'dibatalkan') {
                return redirect()->to(base_url('pesanan/riwayat'))
                    ->with('errors', ['Pesanan ini telah dibatalkan.']);
            }
        }

        if ($session->get('pesanan_total') === null && $idPesanan <= 0) {
            return redirect()->to(base_url('pesanan/pilih-layanan'));
        }
        if (! array_key_exists((string) $session->get('pesanan_metode_bayar'), self::REKENING)) {
            return redirect()->to(base_url('pesanan/pembayaran' . ($idPesanan > 0 ? '/' . $idPesanan : '')));
        }

        $buktiUrl = $this->urlBukti();
        if ($buktiUrl === null) {
            return redirect()->to(base_url('pesanan/upload-bukti' . ($idPesanan > 0 ? '/' . $idPesanan : '')))
                ->with('errors', ['Unggah bukti pembayaran dulu sebelum melihat status.']);
        }

        $idDb = (int) ($session->get('pesanan_id_db') ?? $idPesanan);
        $data = [
            'id_pesanan_db' => $idDb,
            'id_pembayaran' => $session->get('pesanan_id_bayar') ?? ('PAY-' . date('dmY') . '-001'),
            'id_pesanan'    => $session->get('pesanan_id_pesanan') ?? ('PSN-' . date('Ymd') . '-001'),
            'metode'        => $session->get('pesanan_metode_bayar'),
            'total'         => $session->get('pesanan_total') ?? 0,
            'bukti_url'     => $buktiUrl,
        ];
        return view('Pesanan/status_pembayaran', $data);
    }

    /**
     * Halaman tracking pesanan (Dinamis sinkron dengan database & status yang dikelola admin)
     */
    public function tracking($idPesanan = null, $source = null)
    {
        $idPelanggan = $this->getIdPelanggan();
        $model       = new PesananModel();
        $pesanan     = null;
        $from        = ! empty($source) ? $source : (string) ($this->request->getGet('from') ?? '');

        if ($idPesanan !== null) {
            $pesanan = $model->detailTracking((int)$idPesanan, $idPelanggan);
            if (! $pesanan) {
                $pesanan = $model->detailTracking((int)$idPesanan);
            }
        }

        // Jika idPesanan tidak dispesifikasi atau tidak ditemukan, coba dari session
        if (! $pesanan) {
            $idPesananSession = (int) session()->get('pesanan_id_db');
            if ($idPesananSession > 0) {
                $pesanan = $model->detailTracking($idPesananSession);
            }
        }

        // Jika masih belum ada, ambil pesanan aktif terbaru milik pelanggan
        if (! $pesanan) {
            $semua = $model->milikPelanggan($idPelanggan);
            if (! empty($semua)) {
                // Prioritaskan pesanan yang belum selesai
                $aktif = array_filter($semua, static fn($p) => ! in_array($p['status_pesanan'] ?? '', ['selesai', 'dibatalkan'], true));
                $target = ! empty($aktif) ? reset($aktif) : reset($semua);
                $pesanan = $model->detailTracking((int) $target['id_pesanan']);
            }
        }

        // Jika pelanggan aktif belum punya pesanan, gunakan pesanan terbaru dari tabel pesanan di DB
        if (! $pesanan) {
            try {
                $db = \Config\Database::connect();
                $terakhir = $db->table('pesanan')->orderBy('id_pesanan', 'DESC')->get()->getRowArray();
                if ($terakhir) {
                    $pesanan = $model->detailTracking((int) $terakhir['id_pesanan']);
                }
            } catch (\Throwable $th) {
            }
        }

        // Jika benar-benar belum ada data di database sama sekali
        if (! $pesanan) {
            $pesanan = [
                'id_pesanan'         => 1,
                'id_pelanggan'       => $idPelanggan,
                'tanggal'            => date('Y-m-d H:i:s'),
                'berat'              => 3.0,
                'status_pesanan'     => 'dikonfirmasi',
                'alamat_pengambilan' => 'Jl. Lambung Mangkurat No. 12',
                'alamat_pengantaran' => 'Jl. Lambung Mangkurat No. 12',
                'nama_layanan'       => 'Laundry Reguler',
                'estimasi_hari'      => 2,
                'nama_kurir'         => 'Andi Kurniawan',
                'no_hp_kurir'        => '081200000020',
                'jenis_kendaraan'    => 'Motor · Honda Beat',
            ];
        }

        $formattedId = 'PSN-' . date('dmY', strtotime($pesanan['tanggal'] ?? $pesanan['created_at'] ?? 'now')) . '-' . str_pad((string)($pesanan['id_pesanan'] ?? 0), 3, '0', STR_PAD_LEFT);

        return view('Pesanan/tracking', [
            'pesanan'      => $pesanan,
            'id_pesanan'   => $formattedId,
            'status'       => $pesanan['status_pesanan'] ?? 'menunggu',
            'layanan'      => $pesanan['nama_layanan'] ?? 'Reguler',
            'estimasiHari' => (int)($pesanan['estimasi_hari'] ?? 2),
            'from'         => $from,
        ]);
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

    /**
     * Batalkan pesanan oleh pelanggan
     * Hanya pesanan dengan status 'menunggu' atau 'dikonfirmasi' (belum diproses pengerjaan) yang dapat dibatalkan.
     */
    public function batalkanPesanan($id = null)
    {
        $idPelanggan = $this->getIdPelanggan();
        $idPesanan   = (int) ($id ?? $this->request->getPost('id_pesanan') ?? 0);

        if ($idPesanan <= 0) {
            return redirect()->to(base_url('pesanan/riwayat'))
                ->with('errors', ['ID pesanan tidak valid.']);
        }

        $pesananModel = new PesananModel();
        $pesanan = $pesananModel->where('id_pesanan', $idPesanan)
                                ->where('id_pelanggan', $idPelanggan)
                                ->first();

        if (! $pesanan) {
            return redirect()->to(base_url('pesanan/riwayat'))
                ->with('errors', ['Pesanan tidak ditemukan atau bukan milik akun Anda.']);
        }

        $stPesanan = (string) ($pesanan['status_pesanan'] ?? '');
        if (! in_array($stPesanan, ['menunggu', 'dikonfirmasi'], true)) {
            return redirect()->to(base_url('pesanan/riwayat'))
                ->with('errors', ['Pesanan ini tidak dapat dibatalkan karena sudah dalam penanganan kurir / proses pengerjaan laundry.']);
        }

        // Proteksi: Hanya bisa dibatalkan jika BELUM upload bukti bayar
        $pembayaranModel = new PembayaranModel();
        $pb = $pembayaranModel->where('id_pesanan', $idPesanan)->first();
        if ($pb) {
            $stBayar    = strtolower((string) ($pb['status_bayar'] ?? ''));
            $buktiBayar = (string) ($pb['bukti_bayar'] ?? '');
            if (! empty($buktiBayar) || in_array($stBayar, ['menunggu_verifikasi', 'lunas'], true)) {
                return redirect()->to(base_url('pesanan/riwayat'))
                    ->with('errors', ['Pesanan tidak dapat dibatalkan karena Anda sudah mengunggah bukti pembayaran.']);
            }
        }

        $alasan = trim((string) ($this->request->getPost('alasan') ?? ''));
        $catatanBatal = $alasan !== '' ? ' [Dibatalkan pelanggan: ' . $alasan . ']' : ' [Dibatalkan oleh pelanggan]';
        $catatanAkhir = trim(($pesanan['catatan'] ?? '') . $catatanBatal);

        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // 1. Update status_pesanan menjadi 'dibatalkan'
            $pesananModel->update($idPesanan, [
                'status_pesanan' => 'dibatalkan',
                'catatan'        => $catatanAkhir,
            ]);

            // 2. Update status_bayar di tabel pembayaran menjadi 'gagal'
            $db->table('pembayaran')->where('id_pesanan', $idPesanan)->update([
                'status_bayar' => 'gagal',
            ]);

            // 3. Jika sudah ada record di tabel distribusi, reset kurir
            $dist = $db->table('distribusi')->where('id_pesanan', $idPesanan)->get()->getRowArray();
            if ($dist) {
                $db->table('distribusi')->where('id_pesanan', $idPesanan)->update([
                    'status_antar' => 'menunggu_kurir',
                ]);
                if (! empty($dist['id_kurir'])) {
                    $db->table('kurir')->where('id_kurir', (int) $dist['id_kurir'])->update([
                        'status' => 'tersedia',
                    ]);
                }
            }

            // 4. Hapus file bukti transfer jika ada
            $pb = $db->table('pembayaran')->where('id_pesanan', $idPesanan)->get()->getRowArray();
            if ($pb && ! empty($pb['bukti_bayar'])) {
                $path = FCPATH . self::DIR_BUKTI . '/' . basename($pb['bukti_bayar']);
                if (is_file($path)) {
                    @unlink($path);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to(base_url('pesanan/riwayat'))
                    ->with('errors', ['Gagal membatalkan pesanan. Terjadi kesalahan pada database.']);
            }

            // Bersihkan session jika pesanan aktif adalah yang dibatalkan
            if ((int) session()->get('pesanan_id_db') === $idPesanan) {
                session()->remove([
                    'pesanan_id_db',
                    'pembayaran_id_db',
                    'pesanan_id_pesanan',
                    'pesanan_id_bayar',
                    'pesanan_id_layanan',
                    'pesanan_layanan',
                    'pesanan_berat',
                    'pesanan_total',
                    'pesanan_alamat',
                    'pesanan_tanggal',
                    'pesanan_catatan',
                    'pesanan_detail_satuan',
                    'pesanan_metode_bayar',
                    'pesanan_bukti',
                ]);
            }

            $kodePesanan = 'PSN-' . date('dmY', strtotime((string)($pesanan['tanggal'] ?? 'now'))) . '-' . str_pad((string)$idPesanan, 3, '0', STR_PAD_LEFT);
            return redirect()->to(base_url('pesanan/riwayat'))
                ->with('success', 'Pesanan ' . $kodePesanan . ' berhasil dibatalkan.');
        } catch (\Throwable $th) {
            log_message('error', 'Gagal membatalkan pesanan: ' . $th->getMessage());
            return redirect()->to(base_url('pesanan/riwayat'))
                ->with('errors', ['Terjadi kesalahan saat membatalkan pesanan: ' . $th->getMessage()]);
        }
    }

    /**
     * Halaman Notifikasi Pelanggan
     * Mengambil pesanan pelanggan dan mengonversi progres status menjadi notifikasi informatif
     * sesuai dengan mockup (Cucian Selesai, Sedang Dicuci, Kurir Menuju Lokasi, Pesanan Diterima).
     */
    public function notifikasi()
    {
        $idPelanggan = $this->getIdPelanggan();
        $model = new PesananModel();

        $semua = [];
        try {
            $semua = $model->milikPelanggan($idPelanggan);
        } catch (\Throwable $th) {
            log_message('error', 'Gagal ambil pesanan untuk notifikasi: ' . $th->getMessage());
            $semua = [];
        }

        $daftarNotifikasi = [];

        foreach ($semua as $p) {
            $idPesanan    = (int) ($p['id_pesanan'] ?? 0);
            $tglStr       = ! empty($p['tanggal']) ? $p['tanggal'] : (! empty($p['created_at']) ? $p['created_at'] : date('Y-m-d H:i:s'));
            $orderTime    = strtotime($tglStr);
            $formattedId  = 'PSN-' . date('dmY', $orderTime) . '-' . str_pad((string) $idPesanan, 3, '0', STR_PAD_LEFT);
            $layanan      = ! empty($p['nama_layanan']) ? $p['nama_layanan'] : 'laundry reguler';
            $alamatJemput = ! empty($p['alamat_pengambilan']) ? $p['alamat_pengambilan'] : 'alamat penjemputan Anda';
            $alamatAntar  = ! empty($p['alamat_pengantaran']) ? $p['alamat_pengantaran'] : 'alamat pengantaran Anda';
            $statusBayar  = strtolower($p['status_bayar'] ?? '');
            $sudahBayar   = in_array($statusBayar, ['lunas', 'menunggu_verifikasi'], true);
            $targetUrl    = $sudahBayar ? base_url('pesanan/tracking/' . $idPesanan . '/notifikasi') : base_url('pesanan/pembayaran?id_pesanan=' . $idPesanan . '&from=notifikasi');

            $status = strtolower($p['status_pesanan'] ?? 'menunggu');

            if ($status === 'dibatalkan') {
                $daftarNotifikasi[] = [
                    'id_pesanan'    => $idPesanan,
                    'kode_pesanan'  => $formattedId,
                    'judul'         => 'Pesanan Dibatalkan',
                    'pesan'         => "Pesanan laundry Anda dengan kode <strong>#{$formattedId}</strong> telah dibatalkan.",
                    'waktu'         => $orderTime,
                    'waktu_label'   => $this->formatWaktuNotif($orderTime),
                    'badge'         => 'BATAL',
                    'badge_bg'      => '#fee2e2',
                    'badge_color'   => '#b91c1c',
                    'icon'          => 'bi-x-circle-fill',
                    'icon_bg'       => '#fee2e2',
                    'icon_color'    => '#dc2626',
                    'target_url'    => base_url('pesanan/tracking/' . $idPesanan . '/notifikasi'),
                ];
                continue;
            }

            // Milestone mapping
            $stages = [
                'menunggu'     => 1,
                'dikonfirmasi' => 1,
                'dijemput'     => 2,
                'dicuci'       => 3,
                'disetrika'    => 4,
                'diantar'      => 5,
                'selesai'      => 6,
            ];
            $currentLevel = $stages[$status] ?? 1;

            // Milestone 1: Pesanan Diterima
            if ($currentLevel >= 1) {
                $time1 = $orderTime;
                $daftarNotifikasi[] = [
                    'id_pesanan'    => $idPesanan,
                    'kode_pesanan'  => $formattedId,
                    'judul'         => 'Pesanan Diterima',
                    'pesan'         => "Pesanan laundry {$layanan} Anda telah kami terima dan akan segera diproses.",
                    'waktu'         => $time1,
                    'waktu_label'   => $this->formatWaktuNotif($time1),
                    'badge'         => '',
                    'badge_bg'      => '',
                    'badge_color'   => '',
                    'icon'          => 'bi-receipt',
                    'icon_bg'       => '#ccfbf1',
                    'icon_color'    => '#0d9488',
                    'target_url'    => $targetUrl,
                ];
            }

            // Milestone 2: Kurir Menuju Lokasi
            if ($currentLevel >= 2) {
                $time2 = $orderTime + 1800; // 30 menit
                $daftarNotifikasi[] = [
                    'id_pesanan'    => $idPesanan,
                    'kode_pesanan'  => $formattedId,
                    'judul'         => 'Kurir Menuju Lokasi',
                    'pesan'         => "Kurir kami sedang menuju ke alamat penjemputan Anda di {$alamatJemput}.",
                    'waktu'         => $time2,
                    'waktu_label'   => $this->formatWaktuNotif($time2),
                    'badge'         => '',
                    'badge_bg'      => '',
                    'badge_color'   => '',
                    'icon'          => 'bi-truck',
                    'icon_bg'       => '#ffedd5',
                    'icon_color'    => '#ea580c',
                    'target_url'    => $targetUrl,
                ];
            }

            // Milestone 3: Sedang Dicuci
            if ($currentLevel >= 3) {
                $time3 = $orderTime + 7200; // 2 jam
                $daftarNotifikasi[] = [
                    'id_pesanan'    => $idPesanan,
                    'kode_pesanan'  => $formattedId,
                    'judul'         => 'Sedang Dicuci',
                    'pesan'         => 'Cucian Anda sedang dalam proses pencucian menggunakan deterjen ramah lingkungan kami.',
                    'waktu'         => $time3,
                    'waktu_label'   => $this->formatWaktuNotif($time3),
                    'badge'         => 'PROSES',
                    'badge_bg'      => '#ede9fe',
                    'badge_color'   => '#6d28d9',
                    'icon'          => 'bi-basket2-fill',
                    'icon_bg'       => '#ede9fe',
                    'icon_color'    => '#7c3aed',
                    'target_url'    => $targetUrl,
                ];
            }

            // Milestone 4: Sedang Disetrika
            if ($currentLevel >= 4) {
                $time4 = $orderTime + 14400; // 4 jam
                $daftarNotifikasi[] = [
                    'id_pesanan'    => $idPesanan,
                    'kode_pesanan'  => $formattedId,
                    'judul'         => 'Sedang Disetrika',
                    'pesan'         => 'Cucian Anda telah dicuci bersih dan saat ini sedang disetrika rapi serta wangi.',
                    'waktu'         => $time4,
                    'waktu_label'   => $this->formatWaktuNotif($time4),
                    'badge'         => 'PROSES',
                    'badge_bg'      => '#fef3c7',
                    'badge_color'   => '#b45309',
                    'icon'          => 'bi-stars',
                    'icon_bg'       => '#fef3c7',
                    'icon_color'    => '#d97706',
                    'target_url'    => $targetUrl,
                ];
            }

            // Milestone 5: Sedang Diantar
            if ($currentLevel >= 5) {
                $time5 = $orderTime + 21600; // 6 jam
                $daftarNotifikasi[] = [
                    'id_pesanan'    => $idPesanan,
                    'kode_pesanan'  => $formattedId,
                    'judul'         => 'Kurir Mengantar Cucian',
                    'pesan'         => "Kurir kami sedang menuju ke alamat pengantaran Anda di {$alamatAntar}.",
                    'waktu'         => $time5,
                    'waktu_label'   => $this->formatWaktuNotif($time5),
                    'badge'         => 'DIANTAR',
                    'badge_bg'      => '#e0f2fe',
                    'badge_color'   => '#0369a1',
                    'icon'          => 'bi-truck',
                    'icon_bg'       => '#e0f2fe',
                    'icon_color'    => '#0284c7',
                    'target_url'    => $targetUrl,
                ];
            }

            // Milestone 6: Cucian Selesai
            if ($currentLevel >= 6) {
                $time6 = $orderTime + 28800; // 8 jam
                $daftarNotifikasi[] = [
                    'id_pesanan'    => $idPesanan,
                    'kode_pesanan'  => $formattedId,
                    'judul'         => 'Cucian Selesai',
                    'pesan'         => "Yeay! Cucian Anda dengan kode <strong>#{$formattedId}</strong> telah selesai disetrika dan siap untuk dikirim.",
                    'waktu'         => $time6,
                    'waktu_label'   => $this->formatWaktuNotif($time6),
                    'badge'         => 'SELESAI',
                    'badge_bg'      => '#dcfce7',
                    'badge_color'   => '#15803d',
                    'icon'          => 'bi-check-circle-fill',
                    'icon_bg'       => '#dcfce7',
                    'icon_color'    => '#16a34a',
                    'target_url'    => $targetUrl,
                ];
            }
        }

        // Urutkan notifikasi dari yang terbaru ke terlama
        usort($daftarNotifikasi, static fn($a, $b) => $b['waktu'] <=> $a['waktu']);

        return view('Pesanan/notifikasi', [
            'daftarNotifikasi' => $daftarNotifikasi,
            'totalPesanan'     => count($semua),
        ]);
    }

    /**
     * Helper format waktu ramah pengguna: "Baru saja", "10:30 AM", atau "04 Jun"
     */
    private function formatWaktuNotif(int $timestamp): string
    {
        $diff = time() - $timestamp;
        if ($diff < 1800 && $diff >= 0) {
            return 'Baru saja';
        }
        if (date('Y-m-d', $timestamp) === date('Y-m-d')) {
            return date('h:i A', $timestamp);
        }
        return date('d M', $timestamp);
    }

    /* ── Helper bukti pembayaran ─────────────────────────────── */

    /** URL bukti yang sudah diunggah, atau null kalau belum ada / filenya hilang. */
    private function urlBukti(): ?string
    {
        $nama = (string) session()->get('pesanan_bukti');

        if ($nama === '' || ! is_file(FCPATH . self::DIR_BUKTI . '/' . $nama)) {
            $idPesananDb = (int) session()->get('pesanan_id_db');
            if ($idPesananDb > 0) {
                try {
                    $pembayaranModel = new PembayaranModel();
                    $pb = $pembayaranModel->where('id_pesanan', $idPesananDb)->first();
                    if ($pb && ! empty($pb['bukti_bayar']) && is_file(FCPATH . self::DIR_BUKTI . '/' . $pb['bukti_bayar'])) {
                        session()->set('pesanan_bukti', $pb['bukti_bayar']);
                        return base_url(self::DIR_BUKTI . '/' . $pb['bukti_bayar']);
                    }
                } catch (\Throwable $th) {
                }
            }
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