<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e     = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$admin = (array) ($admin ?? []);
?>

<div class="judul-halaman">
    <a href="<?= base_url('admin/akun/data') ?>" class="kembali">&larr;</a>
    <h3>Ubah Data Pribadi</h3>
</div>

<div class="panel">
    <form action="<?= base_url('admin/akun/data/simpan') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= $e((string) old('nama', (string) ($admin['nama'] ?? ''))) ?>">

        <label>Nomor Telepon</label>
        <input type="text" name="no_hp" value="<?= $e((string) old('no_hp', (string) ($admin['noHP'] ?? ''))) ?>">

        <label>Email</label>
        <input type="email" value="<?= $e((string) ($admin['email'] ?? '')) ?>" readonly class="readonly">

        <button type="submit" class="btn btn-dark btn-small">Simpan</button>
    </form>
</div>
<?= $this->endSection() ?>