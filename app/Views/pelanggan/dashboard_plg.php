<?php
helper('foto');

$nama = (string) ($nama ?? '');
$pesananAktif = (int) ($pesananAktif ?? 0);
$statusCuci = (string) ($statusCuci ?? '');
$statusBayar = (string) ($statusBayar ?? '');

$e = static fn(string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$inisial = $nama !== '' ? mb_strtoupper(mb_substr($nama, 0, 1, 'UTF-8'), 'UTF-8') : '?';
$foto = foto_profil();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digiwash - Beranda</title>
    <link rel="stylesheet" href="<?= base_url('css/digiwash.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Icon ikut ukuran & warna teks induknya (span/logo/link) */
        .bi { line-height: 1; vertical-align: -0.125em; }
        .logo .bi { margin-right: 6px; }


        @media (min-width: 900px) {
            .dash-wrap .dash {
                width: 100%;
                max-width: 1080px;
                height: auto;
                min-height: 100vh;
                margin: 0 auto;
                padding: 0 40px 48px;
                overflow: visible;
            }

            /* header: logo kiri, navbar tengah, avatar kanan */
            .dash-wrap .dash-head {
                display: flex;
                align-items: center;
                gap: 24px;
                padding: 28px 0 20px;
                position: static;
            }
            .dash-wrap .dash-head .logo { flex-shrink: 0; }

            /* menu bawah (mobile) -> navbar horizontal */
            .dash-wrap .bottom-nav {
                position: static !important;
                inset: auto !important;
                transform: none !important;
                width: auto !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
                border: 0 !important;
                border-radius: 0 !important;
                display: flex !important;
                flex-direction: row !important;
                align-items: center;
                gap: 6px;
                flex: 1;
            }
            .dash-wrap .bottom-nav a {
                display: inline-flex;
                flex-direction: row;
                align-items: center;
                gap: 8px;
                padding: 10px 16px;
                border-radius: 999px;
                font-size: 14px;
                text-decoration: none;
                transition: background 0.15s;
            }
            .dash-wrap .bottom-nav a span { font-size: 17px; }
            .dash-wrap .bottom-nav a:hover { background: rgba(3, 59, 89, 0.08); }
            .dash-wrap .bottom-nav a.active { background: rgba(3, 59, 89, 0.12); }

            .dash-wrap .avatar { flex-shrink: 0; }

            /* sapaan */
            .dash-wrap .hello { font-size: 28px; margin: 12px 0 28px; }
            .dash-wrap .hello small { display: block; font-size: 15px; margin-top: 4px; }

            /* kartu status: 3 kolom */
            .dash-wrap .cards {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-bottom: 36px;
            }
            .dash-wrap .cards .card {
                min-height: 130px;
                margin: 0;
                width: auto;
            }

            /* menu utama: 5 kolom */
            .dash-wrap .menu h4 { font-size: 18px; margin-bottom: 16px; }
            .dash-wrap .menu-grid {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 16px;
            }
            .dash-wrap .menu-item {
                min-height: 130px;
                width: auto;
                margin: 0;
            }
            .dash-wrap .menu-item span { font-size: 32px; }
        }
    </style>
</head>

<body>
    <div class="dash-wrap">
        <div class="dash">

            <div class="dash-head">
                <div class="logo"><i class="bi bi-basket2-fill"></i>DigiWash</div>

                <nav class="bottom-nav">
                    <a href="<?= base_url('dashboard') ?>" class="active"><span><i class="bi bi-house-door-fill"></i></span>Beranda</a>
                    <a href="<?= base_url('pesanan/pilih-layanan') ?>"><span><i class="bi bi-basket2-fill"></i></span>Pesanan</a>
                    <a href="#" data-soon><span><i class="bi bi-bell-fill"></i></span>Notifikasi</a>
                    <a href="<?= base_url('profil') ?>"><span><i class="bi bi-person-fill"></i></span>Akun</a>
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
                Halo, <?= $e($nama) ?>
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
                    <a class="menu-item" href="<?= base_url('pesanan/pilih-layanan') ?>"><span><i class="bi bi-clipboard-plus-fill"></i></span>Buat Pesanan</a>
                    <a class="menu-item" href="<?= base_url('pesanan/riwayat') ?>"><span><i class="bi bi-receipt"></i></span>Riwayat Pesanan</a>
                    <a class="menu-item" href="<?= base_url('pesanan/tracking') ?>"><span><i class="bi bi-truck"></i></span>Tracking Laundry</a>
                    <a class="menu-item" href="#" data-soon><span><i class="bi bi-wallet2"></i></span>Pembayaran</a>
                    <a class="menu-item" href="#" data-soon><span><i class="bi bi-chat-dots-fill"></i></span>Kritik &amp; Saran</a>
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