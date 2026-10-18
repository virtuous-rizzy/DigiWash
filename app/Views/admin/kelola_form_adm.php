<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e     = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$peran = (string) old('peran', 'kurir');
?>

<div class="judul-halaman">
    <a href="<?= base_url('admin/kelola') ?>" class="kembali">&larr;</a>
    <h3>Tambah Akun</h3>
</div>

<div class="panel">
    <form action="<?= base_url('admin/kelola/simpan') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label>Peran</label>
        <select name="peran">
            <option value="kurir" <?= $peran === 'kurir' ? 'selected' : '' ?>>Kurir</option>
            <option value="admin" <?= $peran === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <label>Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Nama lengkap" value="<?= $e((string) old('nama')) ?>">

        <label>Nomor Telepon</label>
        <input type="text" name="no_hp" placeholder="08XXXXXXXXXX" value="<?= $e((string) old('no_hp')) ?>">

        <label>Email</label>
        <input type="email" name="email" placeholder="email@digiwash.test" value="<?= $e((string) old('email')) ?>">

        <label>Kata Sandi Awal</label>
        <div class="pw">
            <input type="password" name="password" id="pw" placeholder="Minimal 6 karakter" autocomplete="new-password">
            <button type="button" onclick="var x=document.getElementById('pw');x.type=x.type==='password'?'text':'password'">&#128065;</button>
        </div>

        <button type="submit" class="btn btn-dark btn-small">Simpan</button>
    </form>
</div>
<?= $this->endSection() ?>