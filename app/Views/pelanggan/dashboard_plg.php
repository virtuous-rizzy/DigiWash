<?php
helper('foto');

$nama = (string) ($nama ?? '');
$pesananAktif = (int) ($pesananAktif ?? 0);
$statusCuci = (string) ($statusCuci ?? '');
$statusBayar = (string) ($statusBayar ?? '');

$e = static fn(string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : '?';
$foto = foto_profil();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digiwash - Beranda</title>
    <link rel="stylesheet" href="<?= base_url('css/digiwash.css') ?>">
</head>

<body>
    <div class="dash-wrap">
        <div class="dash">

            <div class="dash-head">
                <div class="logo">🧺 DIGIWASH</div>

                <nav class="bottom-nav">
                    <a href="<?= base_url('dashboard') ?>" class="active"><span>🏠</span>Beranda</a>
                    <a href="<?= base_url('pesanan/pilih-layanan') ?>"><span>🧺</span>Pesanan</a>
                    <a href="#" data-soon><span>🔔</span>Notifikasi</a>
                    <a href="<?= base_url('profil') ?>"><span>👤</span>Akun</a>
                </nav>

                <div class="avatar" style="overflow:hidden">
                    <?php if ($foto): ?>
                        <img src="<?= $e($foto) ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
                    <?php else: ?>
                        <?= $e($inisial) ?>
                    <?php endif; ?>
                </div>
            </div>

            <p class="hello">
                Hello, <?= $e($nama) ?>
                <small>Mau laundry apa hari ini?</small>
            </p>

            <div class="cards">
                <div class="card c-purple">
                    <small>Pesanan Aktif</small>
                    <strong><?= $pesananAktif ?></strong>
                </div>
                <div class="card c-pink">
                    <small>Sistem Pengiriman</small>
                    <strong><?= $e($statusCuci) ?></strong>
                </div>
                <div class="card c-green">
                    <small>Sistem Pembayaran</small>
                    <strong><?= $e($statusBayar) ?></strong>
                </div>
            </div>

            <div class="menu">
                <h4>Menu Utama</h4>
                <div class="menu-grid">
                    <a class="menu-item" href="<?= base_url('pesanan/pilih-layanan') ?>"><span>📋</span>Buat Pesanan</a>
                    <a class="menu-item" href="#" data-soon><span>🧾</span>Riwayat Pesanan</a>
                    <a class="menu-item" href="#" data-soon><span>🚚</span>Tracking Laundry</a>
                    <a class="menu-item" href="#" data-soon><span>👛</span>Pembayaran</a>
                    <a class="menu-item" href="#" data-soon><span>💬</span>Kritik &amp; Saran</a>
                </div>
            </div>

        </div>

    </div>

    <div id="toast" class="toast" role="status" aria-live="polite"></div>

    <script>
        (function () {
            var toast = document.getElementById('toast');
            var timer;

            function tampilkan(pesan) {
                toast.textContent = pesan;
                toast.classList.add('show');
                clearTimeout(timer);
                timer = setTimeout(function () { toast.classList.remove('show'); }, 2000);
            }

            document.querySelectorAll('[data-soon]').forEach(function (el) {
                el.addEventListener('click', function (ev) {
                    ev.preventDefault();
                    tampilkan('Fitur segera hadir');
                });
            });

            document.querySelectorAll('[data-konfirmasi]').forEach(function (el) {
                el.addEventListener('click', function (ev) {
                    if (!confirm(el.getAttribute('data-konfirmasi'))) {
                        ev.preventDefault();
                    }
                });
            });
        })();
    </script>
</body>

</html>