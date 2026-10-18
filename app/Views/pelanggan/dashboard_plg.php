<?php
helper('foto');

$nama = (string) ($nama ?? '');
$pesananAktif = (int) ($pesananAktif ?? 0);
$totalPesanan = (int) ($totalPesanan ?? 0);
$statusCuci = (string) ($statusCuci ?? '');
$statusBayar = (string) ($statusBayar ?? '');
$adaPesanan = ($pesananAktif > 0 || $totalPesanan > 0);

$e = static fn(string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$inisial = $nama !== '' ? mb_strtoupper(mb_substr($nama, 0, 1, 'UTF-8'), 'UTF-8') : '?';
$foto = foto_profil();

// Format label status: ganti underscore jadi spasi, ucwords
$fmtStatus = static fn(string $s): string =>
    $s !== '' ? ucwords(str_replace('_', ' ', $s)) : 'Tidak ada';

// Badge config: [keyword_lowercase => [warna-bg, warna-teks, icon-bi]]
$badgeCuci = ['#e8f5e9', '#1b5e20', 'bi-truck'];
$badgeBayar = ['#e3f2fd', '#0b3d5c', 'bi-credit-card-2-front-fill'];

$statusCuciLabel = $fmtStatus($statusCuci);
$statusBayarLabel = $fmtStatus($statusBayar);

// Warna dinamis status bayar
$bayarLow = strtolower($statusBayar);
if (str_contains($bayarLow, 'lunas') || str_contains($bayarLow, 'sukses') || str_contains($bayarLow, 'berhasil')) {
    $badgeBayar = ['#e8f5e9', '#1b5e20', 'bi-check-circle-fill'];
} elseif (str_contains($bayarLow, 'gagal') || str_contains($bayarLow, 'ditolak')) {
    $badgeBayar = ['#fdecea', '#b71c1c', 'bi-x-circle-fill'];
} elseif (str_contains($bayarLow, 'menunggu') || str_contains($bayarLow, 'pending') || str_contains($bayarLow, 'verifikasi')) {
    $badgeBayar = ['#fff8e1', '#e65100', 'bi-hourglass-split'];
}

// Warna dinamis status cuci
$cuciLow = strtolower($statusCuci);
if (str_contains($cuciLow, 'selesai') || str_contains($cuciLow, 'diterima') || str_contains($cuciLow, 'sampai')) {
    $badgeCuci = ['#e8f5e9', '#1b5e20', 'bi-check-circle-fill'];
} elseif (str_contains($cuciLow, 'proses') || str_contains($cuciLow, 'jalan') || str_contains($cuciLow, 'dikirim')) {
    $badgeCuci = ['#e3f2fd', '#0b3d5c', 'bi-truck'];
} elseif (str_contains($cuciLow, 'menunggu') || str_contains($cuciLow, 'pending')) {
    $badgeCuci = ['#fff8e1', '#e65100', 'bi-hourglass-split'];
}
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

        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .bottom-nav a span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Badge dot notifikasi */
        .notif-badge-dot {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            background-color: #ef4444 !important;
            border-radius: 50% !important;
            border: 1.5px solid #fff;
            display: inline-block;
            padding: 0 !important;
        }


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
                grid-template-columns: repeat(3, 1fr);
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
                    <a href="<?= base_url('pesanan/riwayat') ?>"><span><i class="bi bi-receipt"></i></span>Pesanan</a>
                    <?php if ($adaPesanan): ?>
                    <a href="<?= base_url('pesanan/notifikasi') ?>">
                        <span style="position:relative">
                            <i class="bi bi-bell-fill"></i>
                            <?php if ($pesananAktif > 0): ?>
                                <span class="notif-badge-dot"></span>
                            <?php endif; ?>
                        </span>
                        Notifikasi
                    </a>
                    <?php endif; ?>
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
                    <strong style="display:inline-flex;align-items:center;gap:8px">
                        <i class="bi <?= $badgeCuci[2] ?>"></i><?= $e($statusCuciLabel) ?>
                    </strong>
                </div>
                <div class="card c-green">
                    <small>Sistem Pembayaran</small>
                    <strong style="display:inline-flex;align-items:center;gap:8px">
                        <i class="bi <?= $badgeBayar[2] ?>"></i><?= $e($statusBayarLabel) ?>
                    </strong>
                </div>
            </div>

            <div class="menu">
                <h4>Menu Utama</h4>
                <div class="menu-grid">
                    <a class="menu-item" href="<?= base_url('pesanan/pilih-layanan') ?>"><span><i class="bi bi-clipboard-plus-fill"></i></span>Buat Pesanan</a>
                    <a class="menu-item" href="<?= base_url('pesanan/riwayat') ?>"><span><i class="bi bi-receipt"></i></span>Pesanan</a>
                    <a class="menu-item" href="<?= base_url('kritik-saran') ?>"><span><i class="bi bi-chat-dots-fill"></i></span>Kritik &amp; Saran</a>
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