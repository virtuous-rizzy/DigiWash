<?php
helper('foto');

$e       = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$nama    = (string) session()->get('nama');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : 'K';
$foto    = foto_profil();
$aktif   = (string) ($aktif ?? '');
$sukses  = (string) (session()->getFlashdata('success') ?? '');
$errors  = (array) (session()->getFlashdata('errors') ?? []);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digiwash Kurir</title>
    <link rel="stylesheet" href="<?= base_url('css/digiwash.css') ?>">
</head>
<body>
<div class="dash-wrap">
    <div class="dash">

        <div class="dash-head">
            <div class="logo">🧺 DIGIWASH</div>

            <nav class="bottom-nav">
                <a href="<?= base_url('kurir/dashboard') ?>" class="<?= $aktif === 'beranda' ? 'active' : '' ?>"><span>🏠</span>Beranda</a>
                <a href="<?= base_url('kurir/tugas') ?>" class="<?= $aktif === 'tugas' ? 'active' : '' ?>"><span>📋</span>Tugas</a>
                <a href="#" data-soon><span>🕘</span>Riwayat</a>
                <a href="<?= base_url('kurir/akun') ?>" class="<?= $aktif === 'akun' ? 'active' : '' ?>"><span>👤</span>Akun</a>
            </nav>

            <div class="avatar">
                <?php if ($foto): ?>
                    <img src="<?= $e($foto) ?>" alt="">
                <?php else: ?>
                    <?= $e($inisial) ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($sukses !== ''): ?>
            <div class="alert ok"><?= $e($sukses) ?></div>
        <?php endif; ?>
        <?php if ($errors): ?>
            <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
        <?php endif; ?>

        <?= $this->renderSection('isi') ?>

    </div>
</div>

<div id="toast" class="toast" role="status" aria-live="polite"></div>

<script>
(function () {
    var toast = document.getElementById('toast'), timer;
    function tampilkan(p) {
        toast.textContent = p; toast.classList.add('show');
        clearTimeout(timer);
        timer = setTimeout(function () { toast.classList.remove('show'); }, 2000);
    }
    document.querySelectorAll('[data-soon]').forEach(function (el) {
        el.addEventListener('click', function (ev) { ev.preventDefault(); tampilkan('Fitur segera hadir'); });
    });
    document.querySelectorAll('a[data-konfirmasi]').forEach(function (el) {
        el.addEventListener('click', function (ev) {
            if (!confirm(el.getAttribute('data-konfirmasi'))) ev.preventDefault();
        });
    });
    document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
        f.addEventListener('submit', function (ev) {
            if (!confirm(f.getAttribute('data-konfirmasi'))) ev.preventDefault();
        });
    });
})();
</script>
</body>
</html>