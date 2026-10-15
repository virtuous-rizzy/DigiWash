<?php
helper('foto');

$nama       = (string) session()->get('nama');
$judul      = (string) ($judul ?? 'Digiwash');
$e          = static fn(string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$inisial    = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : '?';
$fotoHeader = foto_profil();

// Menu aktif ditentukan dari segmen pertama URL
// contoh: /dashboard -> 'dashboard', /pesanan/pilih-layanan -> 'pesanan', /profil/data -> 'profil'
$segmen = (string) service('uri')->getSegment(1);

$menuAktif = static function (array $daftarSegmen) use ($segmen): string {
    return in_array($segmen, $daftarSegmen, true) ? ' class="active" aria-current="page"' : '';
};
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digiwash - <?= $e($judul) ?></title>
    <link rel="stylesheet" href="<?= base_url('css/digiwash.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
         .bi { line-height: 1; vertical-align: -0.125em; }
        .logo .bi { margin-right: 6px; }
        /* Menu yang sedang dibuka: warna abu */
        .bottom-nav a.active,
        .bottom-nav a.active span {
            color: #8A8A8A;
        }
    </style>
</head>

<body>
    <div class="dash-wrap">
        <div class="dash">

            <div class="dash-head">
                <div class="logo"><i class="bi bi-basket2-fill"></i>DigiWash</div>

                <nav class="bottom-nav">
                    <a href="<?= base_url('dashboard') ?>"<?= $menuAktif(['dashboard', '']) ?>><span><i class="bi bi-house-door-fill"></i></span>Beranda</a>
                    <a href="<?= base_url('pesanan/pilih-layanan') ?>"<?= $menuAktif(['pesanan']) ?>><span><i class="bi bi-basket2-fill"></i></span>Pesanan</a>
                    <a href="#" data-soon><span><i class="bi bi-bell-fill"></i></span>Notifikasi</a>
                    <a href="<?= base_url('profil') ?>"<?= $menuAktif(['profil']) ?>><span><i class="bi bi-person-fill"></i></span>Akun</a>
                </nav>
                <div class="avatar" style="overflow:hidden">
                    <?php if ($fotoHeader): ?>
                        <img src="<?= $e($fotoHeader) ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
                    <?php else: ?>
                        <?= $e($inisial) ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="kolom">
                <?= $this->renderSection('isi') ?>
            </div>

        </div>
    </div>

    <div id="toast" class="toast" role="status" aria-live="polite"></div>

    <script>
        (function() {
            var toast = document.getElementById('toast'),
                timer;

            function tampilkan(p) {
                toast.textContent = p;
                toast.classList.add('show');
                clearTimeout(timer);
                timer = setTimeout(function() {
                    toast.classList.remove('show');
                }, 2000);
            }
            document.querySelectorAll('[data-soon]').forEach(function(el) {
                el.addEventListener('click', function(ev) {
                    ev.preventDefault();
                    tampilkan('Fitur segera hadir');
                });
            });
            document.querySelectorAll('a[data-konfirmasi]').forEach(function(el) {
                el.addEventListener('click', function(ev) {
                    if (!confirm(el.getAttribute('data-konfirmasi'))) ev.preventDefault();
                });
            });
            document.querySelectorAll('[data-toggle-pw]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var input = document.getElementById(btn.getAttribute('data-toggle-pw'));
                    input.type = input.type === 'password' ? 'text' : 'password';
                });
            });
        })();
    </script>
</body>

</html>