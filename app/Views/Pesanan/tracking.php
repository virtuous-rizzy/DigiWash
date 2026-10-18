<?php
$e = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');

$st = (string) ($status ?? $pesanan['status_pesanan'] ?? 'menunggu');
$idPesananFormatted = (string) ($id_pesanan ?? 'PSN-' . date('dmY') . '-001');
$estimasiHari = (int) ($estimasiHari ?? $pesanan['estimasi_hari'] ?? 2);
$layananNama = (string) ($layanan ?? $pesanan['nama_layanan'] ?? 'Laundry Reguler');

$fromParam = (string) ($from ?? request()->getGet('from') ?? '');
$referer   = (string) ($_SERVER['HTTP_REFERER'] ?? '');
if ($fromParam === 'notifikasi' || str_contains($referer, 'notifikasi')) {
    $backTrackingUrl = base_url('pesanan/notifikasi');
} elseif ($fromParam === 'dashboard' || str_contains($referer, 'dashboard')) {
    $backTrackingUrl = base_url('dashboard');
} else {
    $backTrackingUrl = base_url('pesanan/riwayat');
}

// Base timestamp untuk timeline
$baseTime = ! empty($pesanan['tanggal']) ? strtotime((string)$pesanan['tanggal']) : (! empty($pesanan['created_at']) ? strtotime((string)$pesanan['created_at']) : time());

// Judul header status utama di kartu tracking
$headerStatusMap = [
    'menunggu'     => 'Menunggu Penjemputan',
    'dikonfirmasi' => 'Menunggu Penjemputan',
    'dijemput'     => 'Cucian Dijemput',
    'dicuci'       => 'Sedang Dicuci',
    'disetrika'    => 'Sedang Disetrika',
    'diantar'      => 'Dalam Pengiriman',
    'selesai'      => 'Pesanan Selesai',
    'dibatalkan'   => 'Pesanan Dibatalkan',
];
$headerStatusTitle = $headerStatusMap[$st] ?? 'Sedang Diproses';

// Menentukan level aktif (1 sampai 8) sinkron dengan status admin:
// 1 = Menunggu Penjemputan
// 2 = Kurir Menuju Lokasi
// 3 = Cucian Dijemput
// 4 = Sedang Dicuci
// 5 = Sedang Disetrika
// 6 = Siap Dikirim
// 7 = Dalam Pengiriman
// 8 = Pesanan Selesai
$activeStage = match ($st) {
    'menunggu', 'dikonfirmasi' => 1,
    'dijemput'                 => 2,
    'dicuci'                   => 3,
    'disetrika'                => 4,
    'diantar'                  => 5,
    'selesai'                  => 6,
    default                    => 1,
};

// Data Kurir (jika ada)
$namaKurir = (string) ($pesanan['nama_kurir'] ?? '');
$noHpKurir = (string) ($pesanan['no_hp_kurir'] ?? '');
$kendaraanKurir = (string) ($pesanan['jenis_kendaraan'] ?? 'Motor · B 1234 XYZ');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Pesanan - DigiWash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #F0EFEF;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            min-height: 100vh;
            color: #1e293b;
        }
        .mobile-wrap {
            width: 100%;
            max-width: 402px;
            background: #F0EFEF;
            min-height: 100vh;
            padding-bottom: <?= ! empty($namaKurir) ? '120px' : '40px' ?>;
            position: relative;
        }

        /* ── Header ────────────────────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            padding: 44px 20px 16px;
            position: relative;
        }
        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            text-decoration: none;
            color: #000;
            font-size: 22px;
            font-weight: 700;
            border-radius: 50%;
            transition: background 0.2s;
        }
        .btn-back:hover {
            background: rgba(0,0,0,0.06);
            color: #000;
        }
        .page-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 700;
            font-size: 18px;
            color: #000;
            white-space: nowrap;
        }

        /* ── Kotak ID Pesanan (Top Box) ───────────────────── */
        .order-id-box {
            background: #ffffff;
            border-radius: 16px;
            margin: 0 20px 14px;
            padding: 16px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        .order-id-sub {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 2px;
        }
        .order-id-val {
            font-size: 18px;
            font-weight: 800;
            color: #000;
            letter-spacing: 0.3px;
        }

        /* ── Kotak Utama Tracking ─────────────────────────── */
        .main-tracking-card {
            background: #ffffff;
            border-radius: 20px;
            margin: 0 20px;
            padding: 22px 18px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.05);
        }

        /* Header Status di Kartu */
        .tracking-status-head {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .spin-icon {
            display: inline-block;
            animation: spinRotate 2.4s linear infinite;
            color: #334155;
            font-size: 18px;
        }
        @keyframes spinRotate {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* Ilustrasi Mesin Cuci */
        .illus-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 0 26px;
            position: relative;
        }
        .washing-machine-svg {
            width: 170px;
            height: 190px;
            filter: drop-shadow(0 10px 18px rgba(3, 59, 89, 0.12));
            transition: transform 0.3s ease;
        }
        .washing-machine-svg:hover {
            transform: scale(1.03);
        }
        /* Animasi gelembung */
        .bubble-anim-1 { animation: floatBubble 2.5s ease-in-out infinite alternate; }
        .bubble-anim-2 { animation: floatBubble 3.2s ease-in-out infinite alternate 0.5s; }
        .bubble-anim-3 { animation: floatBubble 2.8s ease-in-out infinite alternate 1s; }
        @keyframes floatBubble {
            from { transform: translateY(0) scale(0.95); }
            to   { transform: translateY(-7px) scale(1.05); }
        }

        /* ── Timeline Vertikal (8 Tahapan) ─────────────────── */
        .tracking-timeline {
            margin-top: 6px;
        }
        .timeline-step {
            display: flex;
            gap: 14px;
            position: relative;
        }
        .timeline-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 34px;
            flex-shrink: 0;
        }
        .step-icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #EDE9FE;
            color: #7C3AED;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
            z-index: 2;
            transition: all 0.25s ease;
        }
        .timeline-step.active .step-icon-circle {
            background: #7C3AED;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.22);
        }
        .step-dash-line {
            width: 2px;
            flex: 1;
            min-height: 38px;
            margin: 3px 0;
            border-left: 2px dashed #CBD5E1;
        }
        .timeline-step:last-child .step-dash-line {
            display: none;
        }

        .timeline-body {
            flex: 1;
            padding: 2px 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }
        .timeline-content {
            flex: 1;
        }
        .step-subtitle {
            font-size: 11px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 2px;
        }
        .step-main-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 2px;
        }
        .timeline-step.pending .step-main-title {
            font-weight: 500;
            color: #1e293b;
        }
        .step-time-detail {
            font-size: 11px;
            color: #64748b;
            margin-top: 1px;
        }

        /* Badge status kanan */
        .step-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            border-radius: 999px;
            padding: 3px 12px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .badge-selesai {
            background: #033B59;
            color: #ffffff;
        }
        .badge-proses {
            background: #708A9B;
            color: #ffffff;
        }
        .icon-pending-edit {
            color: #C084FC;
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* ── Floating Kurir Card ─────────────────────────── */
        .kurir-bottom-card {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 402px;
            background: #ffffff;
            border-radius: 20px 20px 0 0;
            box-shadow: 0px -4px 16px rgba(0,0,0,0.08);
            padding: 16px 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            z-index: 100;
        }
        .kurir-avatar-box {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .kurir-details { flex: 1; }
        .kurir-name-text {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        .kurir-vehicle-text {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
        .btn-chat-kurir {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #033B59;
            color: #ffffff;
            border-radius: 10px;
            padding: 9px 15px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-chat-kurir:hover {
            background: #022c44;
            color: #ffffff;
        }
    </style>
</head>
<body>
<div class="mobile-wrap">

    <!-- Header -->
    <div class="page-header">
        <a href="<?= $backTrackingUrl ?>" class="btn-back" title="Kembali">
            &#8592;
        </a>
        <span class="page-title">Tracking Pesanan</span>
    </div>

    <!-- Kotak ID Pesanan (Top Box) -->
    <div class="order-id-box">
        <div class="order-id-sub">ID Pesanan</div>
        <div class="order-id-val"><?= $e($idPesananFormatted) ?></div>
    </div>

    <!-- Kotak Utama Tracking -->
    <div class="main-tracking-card">

        <!-- Header Status Terkini di Dalam Kartu -->
        <div class="tracking-status-head">
            <?php if ($st === 'selesai'): ?>
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <?php else: ?>
                <i class="bi bi-arrow-repeat spin-icon"></i>
            <?php endif; ?>
            <span><?= $e($headerStatusTitle) ?></span>
        </div>

        <!-- Ilustrasi Mesin Cuci SVG Sesuai Desain Mockup -->
        <div class="illus-wrap">
            <svg class="washing-machine-svg" viewBox="0 0 200 230" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Bayangan bawah -->
                <ellipse cx="100" cy="218" rx="72" ry="7" fill="#CBD5E1" opacity="0.6"/>

                <!-- Body Mesin Cuci -->
                <rect x="36" y="38" width="128" height="170" rx="10" fill="#9FBCCE" stroke="#1E293B" stroke-width="2.5"/>
                <rect x="42" y="42" width="116" height="162" rx="7" fill="#FFFFFF"/>

                <!-- Panel Atas -->
                <rect x="42" y="42" width="116" height="34" fill="#F1F5F9" stroke="#E2E8F0" stroke-width="1"/>
                <line x1="42" y1="76" x2="158" y2="76" stroke="#1E293B" stroke-width="2"/>

                <!-- Knob & Tombol Panel -->
                <circle cx="132" cy="59" r="8" fill="#FFFFFF" stroke="#1E293B" stroke-width="2"/>
                <line x1="132" y1="53" x2="132" y2="59" stroke="#1E293B" stroke-width="2" stroke-linecap="round"/>
                <rect x="52" y="53" width="22" height="12" rx="3" fill="#E2E8F0" stroke="#1E293B" stroke-width="1.5"/>
                <circle cx="82" cy="59" r="2.5" fill="#1E293B"/>
                <circle cx="90" cy="59" r="2.5" fill="#1E293B"/>
                <circle cx="98" cy="59" r="2.5" fill="#1E293B"/>

                <!-- Pintu Kaca Mesin Cuci Luar (Outer Ring) -->
                <circle cx="100" cy="136" r="46" fill="#F8FAFC" stroke="#1E293B" stroke-width="2.5"/>
                <circle cx="100" cy="136" r="38" fill="#E2E8F0" stroke="#94A3B8" stroke-width="2"/>

                <!-- Drum Air Bagian Dalam (Inner Drum with water) -->
                <circle cx="100" cy="136" r="34" fill="#38BDF8"/>
                <!-- Gelombang Air Berputar di Drum -->
                <path d="M68 140 C76 130, 88 152, 100 138 C112 124, 124 148, 132 138 L132 168 C122 172, 78 172, 68 168 Z" fill="#0284C7" opacity="0.85"/>
                <circle cx="100" cy="136" r="34" stroke="#1E293B" stroke-width="2"/>

                <!-- Gelembung di Dalam Drum -->
                <circle cx="88" cy="144" r="3" fill="#FFFFFF" opacity="0.85"/>
                <circle cx="108" cy="132" r="4.5" fill="#FFFFFF" opacity="0.85"/>
                <circle cx="114" cy="148" r="2.5" fill="#FFFFFF" opacity="0.85"/>
                <circle cx="96" cy="154" r="3.5" fill="#FFFFFF" opacity="0.85"/>
                <circle cx="82" cy="134" r="2" fill="#FFFFFF" opacity="0.85"/>

                <!-- Gagang Pintu Mesin Cuci -->
                <path d="M138 126 C143 131, 143 141, 138 146" stroke="#1E293B" stroke-width="3" stroke-linecap="round"/>

                <!-- Sudut Kaki Mesin Cuci -->
                <rect x="44" y="208" width="14" height="6" rx="2" fill="#1E293B"/>
                <rect x="142" y="208" width="14" height="6" rx="2" fill="#1E293B"/>

                <!-- Gelembung Sabun di Luar Mesin Cuci (Animated) -->
                <g class="bubble-anim-1">
                    <circle cx="156" cy="38" r="5" fill="#BAE6FD" stroke="#0284C7" stroke-width="1.2"/>
                    <circle cx="154" cy="36" r="1.5" fill="#FFFFFF"/>
                </g>
                <g class="bubble-anim-2">
                    <circle cx="164" cy="54" r="7" fill="#E0F2FE" stroke="#0284C7" stroke-width="1.2"/>
                    <circle cx="162" cy="52" r="2.5" fill="#FFFFFF"/>
                </g>
                <g class="bubble-anim-3">
                    <circle cx="152" cy="72" r="4.5" fill="#BAE6FD" stroke="#0284C7" stroke-width="1.2"/>
                    <circle cx="150" cy="71" r="1.5" fill="#FFFFFF"/>
                </g>
                <g class="bubble-anim-1">
                    <circle cx="168" cy="88" r="6" fill="#E0F2FE" stroke="#0284C7" stroke-width="1.2"/>
                    <circle cx="166" cy="86" r="2" fill="#FFFFFF"/>
                </g>
                <g class="bubble-anim-2">
                    <circle cx="174" cy="112" r="5" fill="#BAE6FD" stroke="#0284C7" stroke-width="1.2"/>
                </g>
            </svg>
        </div>

        <!-- Timeline 6 Tahapan Sinkron Status Admin -->
        <div class="tracking-timeline">

            <!-- 1. Menunggu Penjemputan (status: menunggu / dikonfirmasi) -->
            <?php
            $isDone1   = ($activeStage > 1);
            $isActive1 = ($activeStage === 1);
            ?>
            <div class="timeline-step <?= $isActive1 ? 'active' : ($isDone1 ? 'done' : 'pending') ?>">
                <div class="timeline-left">
                    <div class="step-icon-circle">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div class="step-dash-line"></div>
                </div>
                <div class="timeline-body">
                    <div class="timeline-content">
                        <div class="step-subtitle">Menunggu Penjemputan</div>
                        <div class="step-main-title">Pesanan dikonfirmasi, kurir akan segera menjemput pakaian Anda</div>
                        <div class="step-time-detail">Waktu: <?= date('H:i', $baseTime) ?></div>
                    </div>
                    <?php if ($isDone1): ?>
                        <span class="step-badge badge-selesai">Selesai</span>
                    <?php elseif ($isActive1): ?>
                        <span class="step-badge badge-proses">Sedang Proses</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2. Cucian Dijemput (status: dijemput) -->
            <?php
            $isDone2   = ($activeStage > 2);
            $isActive2 = ($activeStage === 2);
            ?>
            <div class="timeline-step <?= $isActive2 ? 'active' : ($isDone2 ? 'done' : 'pending') ?>">
                <div class="timeline-left">
                    <div class="step-icon-circle">
                        <i class="bi bi-bicycle"></i>
                    </div>
                    <div class="step-dash-line"></div>
                </div>
                <div class="timeline-body">
                    <div class="timeline-content">
                        <div class="step-subtitle">Cucian Dijemput</div>
                        <div class="step-main-title"><?= $activeStage < 2 ? 'Menunggu kurir menjemput pakaian' : 'Pakaian Anda sudah dijemput dan dikirim ke laundry' ?></div>
                        <div class="step-time-detail">Waktu: <?= date('H:i', $baseTime + 1800) ?></div>
                    </div>
                    <?php if ($isDone2): ?>
                        <span class="step-badge badge-selesai">Selesai</span>
                    <?php elseif ($isActive2): ?>
                        <span class="step-badge badge-proses">Sedang Proses</span>
                    <?php else: ?>
                        <i class="bi bi-pencil icon-pending-edit"></i>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 3. Sedang Dicuci (status: dicuci) -->
            <?php
            $isDone3   = ($activeStage > 3);
            $isActive3 = ($activeStage === 3);
            ?>
            <div class="timeline-step <?= $isActive3 ? 'active' : ($isDone3 ? 'done' : 'pending') ?>">
                <div class="timeline-left">
                    <div class="step-icon-circle">
                        <i class="bi bi-basket2-fill"></i>
                    </div>
                    <div class="step-dash-line"></div>
                </div>
                <div class="timeline-body">
                    <div class="timeline-content">
                        <div class="step-subtitle">Proses Cuci</div>
                        <div class="step-main-title"><?= $activeStage < 3 ? 'Menunggu proses pencucian dimulai' : 'Pakaian Anda sedang dicuci bersih' ?></div>
                        <?php if ($isActive3): ?>
                            <div class="step-time-detail">Estimasi selesai: <?= $estimasiHari ?> hari</div>
                        <?php elseif ($isDone3): ?>
                            <div class="step-time-detail">Waktu: <?= date('H:i', $baseTime + 7200) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($isDone3): ?>
                        <span class="step-badge badge-selesai">Selesai</span>
                    <?php elseif ($isActive3): ?>
                        <span class="step-badge badge-proses">Sedang Proses</span>
                    <?php else: ?>
                        <i class="bi bi-pencil icon-pending-edit"></i>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 4. Sedang Disetrika (status: disetrika) -->
            <?php
            $isDone4   = ($activeStage > 4);
            $isActive4 = ($activeStage === 4);
            ?>
            <div class="timeline-step <?= $isActive4 ? 'active' : ($isDone4 ? 'done' : 'pending') ?>">
                <div class="timeline-left">
                    <div class="step-icon-circle">
                        <i class="bi bi-stars"></i>
                    </div>
                    <div class="step-dash-line"></div>
                </div>
                <div class="timeline-body">
                    <div class="timeline-content">
                        <div class="step-subtitle">Proses Setrika</div>
                        <div class="step-main-title"><?= $activeStage < 4 ? 'Menunggu proses penyetrikaan' : 'Pakaian sedang disetrika & dirapikan' ?></div>
                        <?php if ($isActive4): ?>
                            <div class="step-time-detail">Perkiraan selesai: Hari ini</div>
                        <?php elseif ($isDone4): ?>
                            <div class="step-time-detail">Waktu: <?= date('H:i', $baseTime + 10800) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($isDone4): ?>
                        <span class="step-badge badge-selesai">Selesai</span>
                    <?php elseif ($isActive4): ?>
                        <span class="step-badge badge-proses">Sedang Proses</span>
                    <?php else: ?>
                        <i class="bi bi-pencil icon-pending-edit"></i>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 5. Sedang Diantar (status: diantar) -->
            <?php
            $isDone5   = ($activeStage > 5);
            $isActive5 = ($activeStage === 5);
            ?>
            <div class="timeline-step <?= $isActive5 ? 'active' : ($isDone5 ? 'done' : 'pending') ?>">
                <div class="timeline-left">
                    <div class="step-icon-circle">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="step-dash-line"></div>
                </div>
                <div class="timeline-body">
                    <div class="timeline-content">
                        <div class="step-subtitle">Sedang Diantar</div>
                        <div class="step-main-title"><?= $activeStage < 5 ? 'Menunggu pakaian siap dikirim' : 'Kurir sedang mengantar ke alamat Anda' ?></div>
                        <?php if ($isActive5): ?>
                            <div class="step-time-detail">Perkiraan tiba: Segera tiba</div>
                        <?php elseif ($isDone5): ?>
                            <div class="step-time-detail">Waktu: <?= date('H:i', $baseTime + 18000) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($isDone5): ?>
                        <span class="step-badge badge-selesai">Selesai</span>
                    <?php elseif ($isActive5): ?>
                        <span class="step-badge badge-proses">Sedang Proses</span>
                    <?php else: ?>
                        <i class="bi bi-pencil icon-pending-edit"></i>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 6. Pesanan Selesai (status: selesai) -->
            <?php
            $isDone6 = ($activeStage === 6);
            ?>
            <div class="timeline-step <?= $isDone6 ? 'done' : 'pending' ?>">
                <div class="timeline-left">
                    <div class="step-icon-circle">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="timeline-body">
                    <div class="timeline-content">
                        <div class="step-subtitle">Pesanan Selesai</div>
                        <div class="step-main-title"><?= $isDone6 ? 'Pakaian telah diterima. Terima kasih!' : 'Menunggu konfirmasi penerimaan' ?></div>
                        <?php if ($isDone6): ?>
                            <div class="step-time-detail">Waktu: <?= date('H:i', $baseTime + 21600) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($isDone6): ?>
                        <span class="step-badge badge-selesai">Selesai</span>
                    <?php else: ?>
                        <i class="bi bi-pencil icon-pending-edit"></i>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- /.tracking-timeline -->

    </div><!-- /.main-tracking-card -->

</div><!-- /.mobile-wrap -->

<!-- Floating Kurir Card (Tampil jika ada kurir) -->
<?php if (! empty($namaKurir)): ?>
<div class="kurir-bottom-card">
    <div class="kurir-avatar-box"><i class="bi bi-person-circle" style="font-size:28px;"></i></div>
    <div class="kurir-details">
        <div class="kurir-name-text"><?= $e($namaKurir) ?></div>
        <div class="kurir-vehicle-text"><?= $e($kendaraanKurir) ?></div>
    </div>
    <?php
    $noHpClean = preg_replace('/[^0-9]/', '', $noHpKurir);
    if (str_starts_with($noHpClean, '0')) {
        $noHpClean = '62' . substr($noHpClean, 1);
    }
    $waUrl = ! empty($noHpClean) ? 'https://wa.me/' . $noHpClean : '#';
    ?>
    <a href="<?= $e($waUrl) ?>" target="_blank" class="btn-chat-kurir">
        <i class="bi bi-chat-dots-fill"></i> Chat Kurir
    </a>
</div>
<?php endif; ?>

</body>
</html>
