<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Beranda::index');

$routes->get('daftar', 'Auth::daftar');
$routes->post('daftar', 'Auth::prosesDaftar');

$routes->get('masuk', 'Auth::masuk');
$routes->post('masuk', 'Auth::prosesMasuk');
$routes->get('keluar', 'Auth::keluar');

// Pelanggan
$routes->get('dashboard', 'Pelanggan\DashboardPlg::index', ['filter' => 'role:pelanggan']);

$routes->group('profil', ['filter' => 'role:pelanggan'], static function ($routes) {
    $routes->get('', 'Pelanggan\ProfilPlg::index');
    $routes->get('data', 'Pelanggan\ProfilPlg::data');
    $routes->get('data/ubah', 'Pelanggan\ProfilPlg::ubah');
    $routes->post('data/simpan', 'Pelanggan\ProfilPlg::simpan');
    $routes->get('password', 'Pelanggan\ProfilPlg::password');
    $routes->post('password/simpan', 'Pelanggan\ProfilPlg::gantiPassword');
    $routes->get('bantuan', 'Pelanggan\ProfilPlg::bantuan');
    $routes->get('tentang', 'Pelanggan\ProfilPlg::tentang');
    $routes->post('foto', 'Pelanggan\ProfilPlg::foto');
    $routes->post('foto/hapus', 'Pelanggan\ProfilPlg::hapusFoto');
});

$routes->group('alamat', ['filter' => 'role:pelanggan'], static function ($routes) {
    $routes->get('', 'Pelanggan\AlamatPlg::index');
    $routes->get('tambah', 'Pelanggan\AlamatPlg::tambah');
    $routes->post('simpan', 'Pelanggan\AlamatPlg::simpan');
    $routes->get('ubah/(:num)', 'Pelanggan\AlamatPlg::ubah/$1');
    $routes->post('update/(:num)', 'Pelanggan\AlamatPlg::update/$1');
    $routes->post('utama/(:num)', 'Pelanggan\AlamatPlg::utama/$1');
    $routes->post('hapus/(:num)', 'Pelanggan\AlamatPlg::hapus/$1');
});

// Layanan (Pelanggan)
$routes->group('layanan', ['filter' => 'role:pelanggan'], static function ($routes) {
    $routes->get('',               'Layanan::index');          // Pilih Layanan
    $routes->get('buat-pesanan',   'Layanan::buatPesanan');    // Form Pesanan
    $routes->post('ringkasan',     'Layanan::ringkasan');      // Ringkasan (POST dari form)
});

// Admin
$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardAdm::index');

    $routes->get('akun', 'Admin\AkunAdm::index');
    $routes->get('akun/data', 'Admin\AkunAdm::data');
    $routes->get('akun/data/ubah', 'Admin\AkunAdm::ubah');
    $routes->post('akun/data/simpan', 'Admin\AkunAdm::simpan');
    $routes->post('akun/foto', 'Admin\AkunAdm::foto');
    $routes->post('akun/foto/hapus', 'Admin\AkunAdm::hapusFoto');
    $routes->get('password', 'Admin\AkunAdm::password');
    $routes->post('password/simpan', 'Admin\AkunAdm::gantiPassword');

    $routes->get('kelola', 'Admin\KelolaAdm::index');
    $routes->get('kelola/tambah', 'Admin\KelolaAdm::tambah');
    $routes->post('kelola/simpan', 'Admin\KelolaAdm::simpan');
    $routes->post('kelola/status/(:segment)/(:num)', 'Admin\KelolaAdm::status/$1/$2');

    $routes->get('transaksi', 'Admin\TransaksiAdm::index');
    $routes->post('transaksi/verifikasi/(:num)', 'Admin\TransaksiAdm::verifikasi/$1');
    $routes->post('transaksi/tolak/(:num)', 'Admin\TransaksiAdm::tolak/$1');

    $routes->get('pesanan', 'Admin\PesananAdm::index');
    $routes->post('pesanan/update-status/(:num)', 'Admin\PesananAdm::updateStatus/$1');
});

// Kurir
$routes->group('kurir', ['filter' => 'role:kurir'], static function ($routes) {
    $routes->get('dashboard', 'Kurir\DashboardKrr::index');
    $routes->get('tugas', 'Kurir\TugasKrr::index');

    $routes->get('akun', 'Kurir\AkunKrr::index');
    $routes->get('akun/data', 'Kurir\AkunKrr::data');
    $routes->get('akun/data/ubah', 'Kurir\AkunKrr::ubah');
    $routes->post('akun/data/simpan', 'Kurir\AkunKrr::simpan');
    $routes->post('akun/foto', 'Kurir\AkunKrr::foto');
    $routes->post('akun/foto/hapus', 'Kurir\AkunKrr::hapusFoto');
    $routes->get('password', 'Kurir\AkunKrr::password');
    $routes->post('password/simpan', 'Kurir\AkunKrr::gantiPassword');
});

// Buat Pesanan (Dilengkapi namespace eksplisit agar dipastikan memanggil App\Controllers\Pelanggan\Pesanan)
$routes->group('pesanan', ['filter' => 'role:pelanggan', 'namespace' => 'App\Controllers\Pelanggan'], static function ($routes) {
    $routes->get('pilih-layanan', 'Pesanan::pilihLayanan');
    $routes->match(['get', 'post'], 'buat-pesanan', 'Pesanan::buatPesanan');
    $routes->post('ringkasan', 'Pesanan::ringkasan');
    $routes->post('konfirmasi-pesanan', 'Pesanan::konfirmasiPesanan');
    $routes->get('pembayaran', 'Pesanan::pembayaran');
    $routes->post('proses-pembayaran', 'Pesanan::prosesPembayaran');
    $routes->get('status-pembayaran', 'Pesanan::statusPembayaran');
    $routes->get('tracking', 'Pesanan::tracking');
    $routes->get('tracking/(:num)', 'Pesanan::tracking/$1');
    $routes->get('upload-bukti', 'Pesanan::uploadBukti');
    $routes->post('simpan-bukti', 'Pesanan::simpanBukti');
    $routes->get('riwayat', 'Pesanan::riwayatPesanan');
});
