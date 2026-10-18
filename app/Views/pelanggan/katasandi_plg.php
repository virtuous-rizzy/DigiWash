<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
$user   = (array) ($user ?? []);
$errors = (array) (session()->getFlashdata('errors') ?? []);
$e      = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
?>

<div class="judul-halaman">
    <a href="<?= base_url('profil') ?>" class="kembali">&larr;</a>
    <h3>Ganti Kata Sandi</h3>
</div>

<div class="panel">
    <?php if ($errors): ?>
        <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('profil/password/simpan') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label>Email</label>
        <input type="email" value="<?= $e((string) ($user['email'] ?? '')) ?>" readonly class="readonly">

        <label>Kata Sandi Lama</label>
        <div class="pw">
            <input type="password" name="password_lama" id="pw1" placeholder="Masukkan kata sandi lama" autocomplete="current-password">
            <button type="button" data-toggle-pw="pw1">&#128065;</button>
        </div>

        <label>Kata Sandi Baru</label>
        <div class="pw">
            <input type="password" name="password_baru" id="pw2" placeholder="Masukkan kata sandi baru" autocomplete="new-password">
            <button type="button" data-toggle-pw="pw2">&#128065;</button>
        </div>

        <label>Konfirmasi Kata Sandi Baru</label>
        <div class="pw">
            <input type="password" name="password_ulang" id="pw3" placeholder="Konfirmasi kata sandi baru" autocomplete="new-password">
            <button type="button" data-toggle-pw="pw3">&#128065;</button>
        </div>

        <button type="submit" class="btn btn-dark btn-small">Simpan</button>
    </form>
</div>
<?= $this->endSection() ?>