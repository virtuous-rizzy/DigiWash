<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e     = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$admin = (array) ($admin ?? []);
?>

<div class="judul-halaman">
    <a href="<?= base_url('admin/akun') ?>" class="kembali">&larr;</a>
    <h3>Data Pribadi</h3>
</div>

<p class="subjudul">Informasi akun</p>
<div class="daftar-baris">
    <a class="baris" href="<?= base_url('admin/akun/data/ubah') ?>">
        <span class="ikon">👤</span>
        <span class="teks"><small>Nama Lengkap</small><strong><?= $e((string) (($admin['nama'] ?? '') ?: '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <a class="baris" href="<?= base_url('admin/akun/data/ubah') ?>">
        <span class="ikon">📞</span>
        <span class="teks"><small>Nomor Telepon</small><strong><?= $e((string) (($admin['noHP'] ?? '') ?: '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <div class="baris">
        <span class="ikon">✉️</span>
        <span class="teks"><small>Email</small><strong><?= $e((string) (($admin['email'] ?? '') ?: '-')) ?></strong></span>
    </div>
</div>

<p class="subjudul">Keamanan akun</p>
<div class="daftar-baris">
    <div class="baris">
        <span class="ikon">🛡️</span>
        <span class="teks">Peran</span>
        <span class="badge-ok">ADMIN</span>
    </div>
</div>
<?= $this->endSection() ?>