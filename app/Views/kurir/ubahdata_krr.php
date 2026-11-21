<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
$e     = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$kurir = (array) ($kurir ?? []);
?>

<div class="judul-halaman">
<<<<<<< HEAD
    <a href="<?= base_url('kurir/akun/data') ?>" class="kembali">&larr;</a>
=======
    <a href="<?= base_url('kurir/akun/data') ?>" class="kembali"><i class="bi bi-arrow-left"></i></a>
>>>>>>> origin/digiwash-semoga-final
    <h3>Ubah Data Pribadi</h3>
</div>

<div class="panel">
    <form action="<?= base_url('kurir/akun/data/simpan') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= $e((string) old('nama', (string) ($kurir['nama'] ?? ''))) ?>">

        <label>Nomor Telepon</label>
        <input type="text" name="no_hp" value="<?= $e((string) old('no_hp', (string) ($kurir['no_hp'] ?? ''))) ?>">

        <label>Email</label>
        <input type="email" value="<?= $e((string) ($kurir['email'] ?? '')) ?>" readonly class="readonly">

        <button type="submit" class="btn btn-dark btn-small">Simpan</button>
    </form>
</div>
<?= $this->endSection() ?>