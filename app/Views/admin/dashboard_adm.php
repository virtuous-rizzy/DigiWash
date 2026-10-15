<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e            = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$jmlAdmin     = (int) ($jmlAdmin ?? 0);
$jmlKurir     = (int) ($jmlKurir ?? 0);
$jmlPelanggan = (int) ($jmlPelanggan ?? 0);
?>

<p class="hello">
    Selamat datang, <?= $e((string) ($nama ?? 'Admin')) ?>
    <small>Mau kelola apa hari ini?</small>
</p>

<p class="subjudul">Pekerjaan harian</p>
<div class="admin-menu">
    <a class="admin-card" href="#" data-soon>
        <span class="ikon-besar"><i class="bi bi-box-seam-fill" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Memproses Pesanan</strong><small>Lihat dan kelola pesanan baru</small></span>
    </a>
    <a class="admin-card" href="<?= base_url('admin/transaksi') ?>">
        <span class="ikon-besar"><i class="bi bi-cash-stack" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Memproses Transaksi</strong><small>Konfirmasi pembayaran dan kelola transaksi</small></span>
    </a>
    <a class="admin-card" href="#" data-soon>
        <span class="ikon-besar"><i class="bi bi-arrow-repeat" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Mengupdate Status Laundry</strong><small>Ubah status: diterima, dicuci, selesai, diantar</small></span>
    </a>
    <a class="admin-card" href="#" data-soon>
        <span class="ikon-besar"><i class="bi bi-chat-dots-fill" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Kritik &amp; Saran</strong><small>Baca dan tanggapi masukan pelanggan</small></span>
    </a>
</div>

<p class="subjudul">Pengelolaan</p>
<div class="admin-menu">
    <a class="admin-card" href="<?= base_url('admin/kelola') ?>">
        <span class="ikon-besar"><i class="bi bi-people-fill" style="color: #033B59; font-size: 32px;"></i></span>
        <span>
            <strong>Kelola Akun</strong>
            <small><?= $jmlAdmin ?> admin, <?= $jmlKurir ?> kurir, <?= $jmlPelanggan ?> pelanggan</small>
        </span>
    </a>
</div>

<p class="subjudul">Pesanan terbaru</p>
<div class="panel kosong"><p>Belum ada pesanan.</p></div>
<?= $this->endSection() ?>