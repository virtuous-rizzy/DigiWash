<?php

namespace App\Controllers;

class Layanan extends BaseController
{
    /**
     * Daftar layanan yang tersedia (bisa dipindah ke Model/DB nantinya)
     */
    private array $daftarLayanan = [
        'reguler' => [
            'id'          => 'reguler',
            'nama'        => 'Laundry Reguler',
            'deskripsi'   => 'Estimasi 3 hari',
            'harga'       => 7000,
            'ikon'        => '🧺',
            'harga_label' => 'Rp7.000 / Kg',
        ],
        'express' => [
            'id'          => 'express',
            'nama'        => 'Laundry Express',
            'deskripsi'   => 'Estimasi 1 hari',
            'harga'       => 12000,
            'ikon'        => '⚡',
            'harga_label' => 'Rp12.000 / Kg',
        ],
        'satuan' => [
            'id'          => 'satuan',
            'nama'        => 'Laundry Satuan',
            'deskripsi'   => 'Dihitung per helai pakaian',
            'harga'       => 5000,
            'ikon'        => '👔',
            'harga_label' => 'Mulai dari Rp5.000',
        ],
        'bedcover' => [
            'id'          => 'bedcover',
            'nama'        => 'Laundry Bed Cover',
            'deskripsi'   => 'Cuci dan, set, almina berat',
            'harga'       => 35000,
            'ikon'        => '🛏️',
            'harga_label' => 'Rp7.000 / Kg',
        ],
    ];

    /**
     * Halaman 1 – Pilih Layanan
     */
    public function index(): string
    {
        return view('layanan/pilih', [
            'judul'         => 'Pilih Layanan',
            'daftarLayanan' => $this->daftarLayanan,
        ]);
    }

    /**
     * Halaman 2 – Form Buat Pesanan
     * Menerima query string ?layanan=reguler
     */
    public function buatPesanan(): string
    {
        $idLayanan = $this->request->getGet('layanan') ?? 'reguler';

        // Pastikan layanan valid, fallback ke reguler
        if (! array_key_exists($idLayanan, $this->daftarLayanan)) {
            $idLayanan = 'reguler';
        }

        $layananTerpilih = $this->daftarLayanan[$idLayanan];

        // Ambil alamat utama dari sesi (jika ada), sementara pakai dummy
        $alamatUtama = session()->get('alamat_utama') ?? 'Jl. Masuk No. 12, RT 03/RW 01, Kel. Sukajaya, Depok.';

        return view('layanan/buat_pesanan', [
            'judul'          => 'Buat Pesanan',
            'layanan'        => $layananTerpilih,
            'alamatUtama'    => $alamatUtama,
        ]);
    }

    /**
     * Halaman 3 – Ringkasan Pesanan
     * Menerima data POST dari form buat_pesanan
     */
    public function ringkasan(): string
    {
        // Ambil data dari POST
        $idLayanan    = $this->request->getPost('id_layanan')    ?? 'reguler';
        $namaLayanan  = $this->request->getPost('nama_layanan')  ?? '';
        $hargaPerKg   = (int) ($this->request->getPost('harga_per_kg') ?? 0);
        $berat        = (int) ($this->request->getPost('berat')        ?? 1);
        $alamat       = $this->request->getPost('alamat')              ?? '';
        $tanggal      = $this->request->getPost('tanggal')             ?? '';
        $catatan      = $this->request->getPost('catatan')             ?? '';
        $hargaLabel   = $this->request->getPost('harga_label')         ?? '';

        $totalBiaya = $hargaPerKg * $berat;

        return view('layanan/ringkasan', [
            'judul'       => 'Ringkasan Pesanan',
            'idLayanan'   => $idLayanan,
            'namaLayanan' => $namaLayanan,
            'hargaLabel'  => $hargaLabel,
            'berat'       => $berat,
            'hargaPerKg'  => $hargaPerKg,
            'alamat'      => $alamat,
            'tanggal'     => $tanggal,
            'catatan'     => $catatan,
            'totalBiaya'  => $totalBiaya,
        ]);
    }
}
