<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e     = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$admin = (array) ($admin ?? []);
?>

<div class="judul-halaman">
    <a href="<?= base_url('admin/akun') ?>" class="kembali">&larr;</a>
    <h3>Ganti Kata Sandi</h3>
</div>

<div class="panel">
    <form action="<?= base_url('admin/password/simpan') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label>Email</label>
        <input type="email" value="<?= $e((string) ($admin['email'] ?? '')) ?>" readonly class="readonly">

        <label>Kata Sandi Lama</label>
        <div class="pw">
            <input type="password" name="password_lama" id="pw1" placeholder="Masukkan kata sandi lama" autocomplete="current-password">
            <button type="button" onclick="tp('pw1')">&#128065;</button>
        </div>

        <label>Kata Sandi Baru</label>
        <div class="pw">
            <input type="password" name="password_baru" id="pw2" placeholder="Minimal 6 karakter" autocomplete="new-password">
            <button type="button" onclick="tp('pw2')">&#128065;</button>
        </div>

        <label>Konfirmasi Kata Sandi Baru</label>
        <div class="pw">
            <input type="password" name="password_ulang" id="pw3" placeholder="Ulangi kata sandi baru" autocomplete="new-password">
            <button type="button" onclick="tp('pw3')">&#128065;</button>
        </div>

        <button type="submit" class="btn btn-dark btn-small">Simpan</button>
    </form>
</div>

<script>
function tp(id){var x=document.getElementById(id);x.type=x.type==='password'?'text':'password';}
</script>
<?= $this->endSection() ?>