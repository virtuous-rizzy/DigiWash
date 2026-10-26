<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
helper('foto');

$e       = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$kurir   = (array) ($kurir ?? []);
$nama    = (string) ($kurir['nama'] ?? '');
$noHp    = (string) ($kurir['no_hp'] ?? '');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : 'K';
$foto    = foto_profil();
?>

<div class="judul-halaman">
    <a href="<?= base_url('kurir/dashboard') ?>" class="kembali"><i class="bi bi-arrow-left"></i></a>
    <h3>Profil Saya</h3>
</div>

<div class="profil-hero">
    <form action="<?= base_url('kurir/akun/foto') ?>" method="post" enctype="multipart/form-data" id="formFoto">
        <?= csrf_field() ?>
        <label class="foto-wrap" for="inputFoto" title="Ganti foto profil">
            <?php if ($foto): ?>
                <img src="<?= $e($foto) ?>" alt="Foto profil" class="hero-foto">
            <?php else: ?>
                <div class="hero-avatar"><?= $e($inisial) ?></div>
            <?php endif; ?>
            <span class="foto-edit"><i class="bi bi-camera-fill"></i></span>
        </label>
        <input type="file" name="foto" id="inputFoto" accept="image/png,image/jpeg,image/webp" hidden>
    </form>

    <h3><?= $e($nama) ?></h3>
    <p><?= $e($noHp) ?></p>

    <?php if ($foto): ?>
        <form action="<?= base_url('kurir/akun/foto/hapus') ?>" method="post" data-konfirmasi="Hapus foto profil?">
            <?= csrf_field() ?>
            <button type="submit" class="hapus-foto">Hapus foto</button>
        </form>
    <?php endif; ?>
</div>

<div class="daftar-baris">
    <a class="baris" href="<?= base_url('kurir/akun/data') ?>"><span class="ikon"><i class="bi bi-person-fill" style="color: #033B59;"></i></span><span class="teks">Data Pribadi</span><span class="panah">&rsaquo;</span></a>
    <a class="baris" href="<?= base_url('kurir/password') ?>"><span class="ikon"><i class="bi bi-lock-fill" style="color: #033B59;"></i></span><span class="teks">Ganti Kata Sandi</span><span class="panah">&rsaquo;</span></a>
</div>

<a href="<?= base_url('keluar') ?>" class="btn-logout" data-konfirmasi="Yakin ingin keluar?">Keluar</a>

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