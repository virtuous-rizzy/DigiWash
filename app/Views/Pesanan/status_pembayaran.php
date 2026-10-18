<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran - DigiWash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #EFEEEE;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        .mobile-wrap {
            width: 100%;
            max-width: 402px;
            background: #EFEEEE;
            min-height: 100vh;
            padding-bottom: 40px;
        }

        /* Header */
        .page-header {
            display: flex; align-items: center;
            padding: 54px 30px 24px; position: relative;
        }
        .btn-back {
            display: flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; text-decoration: none;
            color: #000; font-size: 20px; font-weight: bold;
        }
        .page-title {
            position: absolute; left: 50%; transform: translateX(-50%);
            font-weight: 600; font-size: 20px; line-height: 22px;
            color: #000; white-space: nowrap;
        }

        /* Illustration */
        .illus-wrap { display: flex; justify-content: center; margin: 10px 0 16px; }
        .illus-circle {
            width: 151px; height: 151px; border-radius: 50%;
            background: #d4edda;
            display: flex; align-items: center; justify-content: center;
            font-size: 70px;
            box-shadow: 0 4px 20px rgba(1,157,19,0.2);
        }

        /* Status text */
        .status-title {
            font-weight: 600; font-size: 20px; line-height: 22px;
            color: #000; text-align: center; margin-bottom: 6px;
        }
        .status-subtitle {
            font-weight: 200; font-size: 16px; line-height: 22px;
            color: #555; text-align: center; margin-bottom: 20px;
            padding: 0 24px;
        }

        /* Waiting badge */
        .waiting-badge {
            display: flex; align-items: center; justify-content: center;
            gap: 6px; background: #FFF6DE; border: 1px solid #D2CBCB;
            border-radius: 10px; padding: 6px 20px;
            width: fit-content; margin: 0 auto 24px;
        }
        .waiting-badge-text {
            font-weight: 600; font-size: 16px; line-height: 22px; color: #A37C12;
        }

        /* Detail card */
        .detail-card {
            background: #fff; border: 1px solid #D2CBCB; border-radius: 10px;
            margin: 0 24px 20px; padding: 16px 20px;
        }
        .detail-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 9px 0; border-bottom: 1px solid #f5f5f5;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-key { font-weight: 200; font-size: 16px; line-height: 22px; color: #000; }
        .detail-val { font-weight: 600; font-size: 16px; line-height: 22px; color: #000; text-align: right; }
        .status-menunggu { color: #A37C12; }

        /* CTA buttons */
        .cta-wrap { padding: 0 17px; display: flex; flex-direction: column; gap: 12px; }
        .btn-tracking {
            display: flex; align-items: center; justify-content: center;
            width: 100%; height: 48px; background: #033B59;
            border: none; border-radius: 10px;
            font-weight: 700; font-size: 16px; color: #fff;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-tracking:hover { background: #022c44; color: #fff; }
        .btn-dashboard {
            display: flex; align-items: center; justify-content: center;
            width: 100%; height: 41px; background: #fff;
            border: 1px solid #D2CBCB; border-radius: 10px;
            font-weight: 700; font-size: 14px; color: #033B59;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-dashboard:hover { background: #f0f7ff; color: #033B59; }
    </style>
</head>
<body>
<div class="mobile-wrap">

    <!-- Header -->
    <div class="page-header">
        <a href="<?= base_url('pesanan/upload-bukti') ?>" class="btn-back">&#8592;</a>
        <span class="page-title">Status Pembayaran</span>
    </div>

    <!-- Illustration -->
    <div class="illus-wrap">
        <div class="illus-circle">⏳</div>
    </div>

    <!-- Status -->
    <div class="status-title">Menunggu Verifikasi</div>
    <div class="status-subtitle">Pembayaran kamu sedang kami verifikasi.<br>Ini hanya membutuhkan beberapa menit.</div>

    <!-- Waiting badge -->
    <div class="waiting-badge">
        <span>⏰</span>
        <span class="waiting-badge-text">Menunggu Verifikasi</span>
    </div>

    <!-- Detail Card -->
    <div class="detail-card">

        <div class="detail-row">
            <span class="detail-key">ID Pembayaran</span>
            <span class="detail-val"><?= esc((string) $id_pembayaran) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-key">ID Pesanan</span>
            <span class="detail-val"><?= esc((string) $id_pesanan) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-key">Metode</span>
            <span class="detail-val"><?= esc((string) $metode) ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-key">Jumlah</span>
            <span class="detail-val">Rp <?= number_format((int) $total, 0, ',', '.') ?></span>
        </div>
        <?php if (!empty($bukti_url)): ?>
        <div class="detail-row">
            <span class="detail-key">Bukti Bayar</span>
            <span class="detail-val"><a href="<?= esc($bukti_url, 'attr') ?>" target="_blank" rel="noopener" style="color:#033B59;text-decoration:none;">Lihat bukti</a></span>
        </div>
        <?php endif; ?>
        <div class="detail-row">
            <span class="detail-key">Status</span>
            <span class="detail-val status-menunggu">Menunggu</span>
        </div>

    </div><!-- /.detail-card -->

    <!-- CTA Buttons -->
    <div class="cta-wrap">
        <!-- Tombol utama → Tracking Pesanan -->
        <a href="<?= base_url('pesanan/tracking') ?>" class="btn-tracking">
            📍 Lacak Pesanan Saya →
        </a>
        <!-- Tombol sekunder → Dashboard -->
        <a href="<?= base_url('dashboard') ?>" class="btn-dashboard">
            Kembali ke Beranda
        </a>
    </div>

</div><!-- /.mobile-wrap -->
</body>
</html>