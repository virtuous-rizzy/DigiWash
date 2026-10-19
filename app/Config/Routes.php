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

// ==========================================
// Fitur Kritik dan Saran
// ==========================================
$routes->get('/kritik-saran', 'KritikSaran::index');
$routes->post('/kritik-saran/simpan', 'KritikSaran::simpan');
$routes->get('/kelola-kritik', 'KritikSaran::kelola');


// ==========================================
// Pelanggan
// ==========================================
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
    // Alamat
    $routes->get('alamat', 'Pelanggan\ProfilPlg::alamat');
    $routes->get('alamat/ubah', 'Pelanggan\ProfilPlg::ubahAlamat');
    $routes->post('alamat/simpan', 'Pelanggan\ProfilPlg::simpanAlamat');
    $routes->post('alamat/hapus', 'Pelanggan\ProfilPlg::hapusAlamat');
});

// Layanan (Pelanggan)
$routes->group('layanan', ['filter' => 'role:pelanggan'], static function ($routes) {
    $routes->get('',               'Layanan::index');          // Pilih Layanan
    $routes->get('buat-pesanan',   'Layanan::buatPesanan');    // Form Pesanan
    $routes->post('ringkasan',     'Layanan::ringkasan');      // Ringkasan (POST dari form)
});

// ==========================================
// Admin
// ==========================================
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
    $routes->get('status-laundry', 'Admin\PesananAdm::statusLaundry');
    $routes->post('pesanan/update-status/(:num)', 'Admin\PesananAdm::updateStatus/$1');

    $routes->get('kelola-kritik', 'KritikSaran::kelola');
    $routes->post('kelola-kritik/update-status/(:num)', 'KritikSaran::updateStatus/$1');
});

// ==========================================
// Kurir
// ==========================================
$routes->group('kurir', ['filter' => 'role:kurir'], static function ($routes) {
    $routes->get('dashboard', 'Kurir\DashboardKrr::index');
    $routes->get('tugas', 'Kurir\TugasKrr::index');
    $routes->get('tugas/detail/(:num)', 'Kurir\TugasKrr::detail/$1');
    $routes->post('tugas/update-aksi/(:num)', 'Kurir\TugasKrr::updateAksi/$1');
    $routes->get('riwayat', 'Kurir\RiwayatKrr::index');

    $routes->get('akun', 'Kurir\AkunKrr::index');
    $routes->get('akun/data', 'Kurir\AkunKrr::data');
    $routes->get('akun/data/ubah', 'Kurir\AkunKrr::ubah');
    $routes->post('akun/data/simpan', 'Kurir\AkunKrr::simpan');
    $routes->post('akun/foto', 'Kurir\AkunKrr::foto');
    $routes->post('akun/foto/hapus', 'Kurir\AkunKrr::hapusFoto');
    $routes->get('password', 'Kurir\AkunKrr::password');
    $routes->post('password/simpan', 'Kurir\AkunKrr::gantiPassword');

    $routes->get('kritik-saran', 'KritikSaran::index');
    $routes->post('kritik-saran/simpan', 'KritikSaran::simpan');
    $routes->get('kelola-kritik', 'KritikSaran::kelola');
});

// ==========================================
// Buat Pesanan
// ==========================================
$routes->group('pesanan', ['filter' => 'role:pelanggan', 'namespace' => 'App\Controllers\Pelanggan'], static function ($routes) {
    $routes->get('pilih-layanan', 'Pesanan::pilihLayanan');
    $routes->match(['get', 'post'], 'buat-pesanan', 'Pesanan::buatPesanan');
    $routes->post('ringkasan', 'Pesanan::ringkasan');
    $routes->post('konfirmasi-pesanan', 'Pesanan::konfirmasiPesanan');
    $routes->get('pembayaran', 'Pesanan::pembayaran');
    $routes->get('pembayaran/(:num)', 'Pesanan::pembayaran/$1');
    $routes->post('proses-pembayaran', 'Pesanan::prosesPembayaran');
    $routes->post('proses-pembayaran/(:num)', 'Pesanan::prosesPembayaran/$1');
    $routes->get('status-pembayaran', 'Pesanan::statusPembayaran');
    $routes->get('status-pembayaran/(:num)', 'Pesanan::statusPembayaran/$1');
    $routes->get('tracking', 'Pesanan::tracking');
    $routes->get('tracking/(:num)', 'Pesanan::tracking/$1');
    $routes->get('tracking/(:num)/(:segment)', 'Pesanan::tracking/$1/$2');
    $routes->get('upload-bukti', 'Pesanan::uploadBukti');
    $routes->get('upload-bukti/(:num)', 'Pesanan::uploadBukti/$1');
    $routes->post('simpan-bukti', 'Pesanan::simpanBukti');
    $routes->post('simpan-bukti/(:num)', 'Pesanan::simpanBukti/$1');
    $routes->get('riwayat', 'Pesanan::riwayatPesanan');
    $routes->post('batalkan', 'Pesanan::batalkanPesanan');
    $routes->post('batalkan/(:num)', 'Pesanan::batalkanPesanan/$1');
    $routes->get('notifikasi', 'Pesanan::notifikasi');
});