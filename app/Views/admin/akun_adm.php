<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
helper('foto');

$e       = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$admin   = (array) ($admin ?? []);
$nama    = (string) ($admin['nama'] ?? '');
$noHp    = (string) ($admin['noHP'] ?? '');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : 'A';
$foto    = foto_profil();
?>

<div class="judul-halaman">
    <a href="<?= base_url('admin/dashboard') ?>" class="kembali">&larr;</a>
    <h3>Profil Saya</h3>
</div>

<div class="profil-hero">
    <form action="<?= base_url('admin/akun/foto') ?>" method="post" enctype="multipart/form-data" id="formFoto">
        <?= csrf_field() ?>
        <label class="foto-wrap" for="inputFoto" title="Ganti foto profil">
            <?php if ($foto): ?>
                <img src="<?= $e($foto) ?>" alt="Foto profil" class="hero-foto">
            <?php else: ?>
                <div class="hero-avatar"><?= $e($inisial) ?></div>
            <?php endif; ?>
            <span class="foto-edit">📷</span>
        </label>
        <input type="file" name="foto" id="inputFoto" accept="image/png,image/jpeg,image/webp" hidden>
    </form>

    <h3><?= $e($nama) ?></h3>
    <p><?= $e($noHp) ?></p>

    <?php if ($foto): ?>
        <form action="<?= base_url('admin/akun/foto/hapus') ?>" method="post" data-konfirmasi="Hapus foto profil?">
            <?= csrf_field() ?>
            <button type="submit" class="hapus-foto">Hapus foto</button>
        </form>
    <?php endif; ?>
</div>

<div class="daftar-baris">
    <a class="baris" href="<?= base_url('admin/akun/data') ?>"><span class="ikon">👤</span><span class="teks">Data Pribadi</span><span class="panah">&rsaquo;</span></a>
    <a class="baris" href="<?= base_url('admin/password') ?>"><span class="ikon">🔒</span><span class="teks">Ganti Kata Sandi</span><span class="panah">&rsaquo;</span></a>
</div>

<a href="<?= base_url('keluar') ?>" class="btn-logout" data-konfirmasi="Yakin ingin keluar?">Logout</a>

<script>
(function () {
    var input = document.getElementById('inputFoto');
    input.addEventListener('change', function () {
        var f = input.files[0];
        if (!f) return;
        if (f.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2 MB.');
            input.value = '';
            return;
        }
        document.getElementById('formFoto').submit();
    });
})();
</script>
<?= $this->endSection() ?>