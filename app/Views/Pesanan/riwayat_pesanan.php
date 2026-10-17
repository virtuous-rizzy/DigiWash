<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - DigiWash</title>
    <meta name="description" content="Lihat riwayat pesanan laundry kamu di DigiWash. Filter pesanan berdasarkan status: Diproses, Selesai, atau Dibatalkan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #F0EFEF;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .mobile-wrap {
            width: 100%;
            max-width: 402px;
            background: #F0EFEF;
            min-height: 100vh;
            position: relative;
            padding-bottom: 40px;
        }

        /* ── Header ──────────────────────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            padding: 54px 24px 16px;
            position: relative;
        }
        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            text-decoration: none;
            color: #000;
            font-size: 20px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .page-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 600;
            font-size: 20px;
            color: #000;
            white-space: nowrap;
        }

        /* ── Tab Filter ──────────────────────────────────────── */

        /* Wrapper: posisi relative agar pseudo-element bisa ditempel */
        .tab-bar-wrap {
            position: relative;
            /* Fade gradient kanan sebagai scroll hint */
        }
        .tab-bar-wrap::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40px;
            height: 100%;
            background: linear-gradient(to right, transparent, #F0EFEF);
            pointer-events: none;
            border-radius: 0;
            transition: opacity 0.2s;
        }
        /* Sembunyikan fade kalau sudah di ujung kanan */
        .tab-bar-wrap.at-end::after { opacity: 0; }

        .tab-bar {
            display: flex;
            flex-wrap: nowrap;          /* paksa satu baris */
            gap: 8px;
            padding: 0 20px 16px;
            overflow-x: auto;           /* scroll horizontal */
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;   /* smooth di iOS */
            scroll-behavior: smooth;
            scrollbar-width: none;      /* Firefox: sembunyikan scrollbar */
            cursor: grab;               /* tanda bisa di-drag */
        }
        .tab-bar::-webkit-scrollbar { display: none; } /* Chrome/Safari */
        .tab-bar.dragging { cursor: grabbing; user-select: none; }

        .tab-btn {
            flex: 0 0 auto;             /* jangan menyusut/melebar */
            padding: 8px 18px;
            border-radius: 999px;
            border: 1.5px solid #D0D0D0;
            background: transparent;
            font-family: 'Roboto', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            cursor: pointer;
            transition: background 0.18s, color 0.18s, border-color 0.18s;
            white-space: nowrap;        /* teks tidak pecah */
            -webkit-tap-highlight-color: transparent;
        }
        .tab-btn.active {
            background: #033B59;
            border-color: #033B59;
            color: #fff;
            font-weight: 600;
        }
        .tab-btn:hover:not(.active) {
            background: rgba(3, 59, 89, 0.07);
            border-color: #033B59;
            color: #033B59;
        }

        /* Badge jumlah pesanan di dalam tombol tab */
        .tab-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: rgba(255,255,255,0.25);
            font-size: 10px;
            font-weight: 700;
            line-height: 1;
            vertical-align: middle;
        }
        .tab-btn.active .tab-count {
            background: rgba(255,255,255,0.3);
            color: #fff;
        }
        .tab-btn:not(.active) .tab-count {
            background: #033B59;
            color: #fff;
        }

        /* ── Responsive: di layar >= md Bootstrap (≥ 768px) ─────
           Mobile-wrap diperlebar sehingga tab ikut full-width     */
        @media (min-width: 576px) {
            .mobile-wrap { max-width: 100%; }
        }
        @media (min-width: 768px) {
            .mobile-wrap {
                max-width: 720px;
                padding-left: 0;
                padding-right: 0;
            }
        }
        @media (min-width: 992px) {
            .mobile-wrap { max-width: 900px; }
        }

        /* ── Pesanan List ────────────────────────────────────── */
        .pesanan-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 0 20px;
        }

        /* ── Pesanan Card ────────────────────────────────────── */
        .pesanan-card {
            background: #fff;
            border-radius: 14px;
            padding: 16px 18px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07);
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .pesanan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(3,59,89,0.13);
            color: inherit;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }
        .order-id {
            font-weight: 700;
            font-size: 14px;
            color: #111;
        }

        /* Status badges */
        .badge-status {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 999px;
            white-space: nowrap;
        }
        .badge-dicuci   { background: #E8F1FF; color: #1565C0; }
        .badge-diproses { background: #E8F1FF; color: #1565C0; }
        .badge-selesai  { background: #E6F7EA; color: #1B7B34; }
        .badge-dibatalkan { background: #FDECEA; color: #B71C1C; }
        .badge-menunggu { background: #FFF3E0; color: #E65100; }

        .card-date {
            font-size: 12px;
            color: #777;
            margin-bottom: 4px;
        }
        .card-layanan {
            font-size: 13px;
            color: #333;
        }
        .card-divider {
            height: 1px;
            background: #F0F0F0;
            margin: 10px 0;
        }
        .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-berat { font-size: 13px; color: #555; }
        .card-harga { font-weight: 700; font-size: 14px; color: #111; }
        .card-keterangan { font-weight: 600; font-size: 13px; color: #E65100; }

        /* ── Empty state ─────────────────────────────────────── */
        .empty-state {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 64px 24px;
            text-align: center;
        }
        .empty-state.show { display: flex; }
        .empty-icon { font-size: 60px; margin-bottom: 16px; opacity: 0.45; }
        .empty-title { font-weight: 700; font-size: 16px; color: #333; margin-bottom: 6px; }
        .empty-sub   { font-size: 13px; color: #888; }

        /* ── Tab panel visibility ────────────────────────────── */
        .tab-panel { display: none; }
        .tab-panel.show { display: block; }
    </style>
</head>
<?php
// ── Helper lokal ─────────────────────────────────────────────
$e = static fn(string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');

$semua      = $semua      ?? [];
$diproses   = $diproses   ?? [];
$selesai    = $selesai    ?? [];
$dibatalkan = $dibatalkan ?? [];

$badgeClass = static function (string $status): string {
    return match ($status) {
        'dicuci', 'disetrika' => 'badge-dicuci',
        'selesai'             => 'badge-selesai',
        'dibatalkan'          => 'badge-dibatalkan',
        'diantar'             => 'badge-menunggu',
        default               => 'badge-diproses',
    };
};

$labelStatus = static function (string $status): string {
    return match ($status) {
        'menunggu'     => 'Menunggu Konfirmasi',
        'dikonfirmasi' => 'Dikonfirmasi',
        'dijemput'     => 'Sedang Dijemput',
        'dicuci'       => 'Sedang Dicuci',
        'disetrika'    => 'Disetrika',
        'diantar'      => 'Sedang Diantar',
        'selesai'      => 'Selesai',
        'dibatalkan'   => 'Dibatalkan',
        default        => ucfirst($status),
    };
};

$formatRupiah = static function ($angka): string {
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
};

$formatTanggal = static function (string $tgl): string {
    if ($tgl === '' || $tgl === '0000-00-00' || $tgl === '0000-00-00 00:00:00') return '-';
    $time = strtotime($tgl);
    if ($time === false) return htmlspecialchars($tgl, ENT_QUOTES, 'UTF-8');
    $bulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    $m = (int) date('n', $time);
    return date('j', $time) . ' ' . ($bulan[$m] ?? date('M', $time)) . ' ' . date('Y', $time);
};

$kartuPesanan = static function (array $p, int $idx) use ($e, $badgeClass, $labelStatus, $formatRupiah, $formatTanggal): void {
    $nomorPesanan = 'PSN-' . str_pad((string)($p['id_pesanan'] ?? 0), 8, '0', STR_PAD_LEFT);
    $tanggal      = $formatTanggal((string)($p['tanggal'] ?? ''));
    $layanan      = $e((string)($p['nama_layanan'] ?? '-'));
    $berat        = number_format((float)($p['berat'] ?? 0), 1, ',', '.') . ' Kg';
    $status       = (string)($p['status_pesanan'] ?? 'menunggu');
    $total        = (float)($p['total_harga'] ?? 0);
    $badge        = $badgeClass($status);
    $label        = $labelStatus($status);
    $harga        = $formatRupiah($total);
    $isAntar      = ($status === 'diantar');
    ?>
    <a class="pesanan-card" href="<?= base_url('pesanan/tracking/' . (int)$p['id_pesanan']) ?>" id="card-<?= $e($status) ?>-<?= $idx ?>">
        <div class="card-top">
            <span class="order-id"><?= $e($nomorPesanan) ?></span>
            <span class="badge-status <?= $e($badge) ?>"><?= $e($label) ?></span>
        </div>
        <div class="card-date"><?= $e($tanggal) ?></div>
        <div class="card-layanan"><?= $layanan ?></div>
        <div class="card-divider"></div>
        <div class="card-bottom">
            <span class="card-berat"><?= $e($berat) ?></span>
            <?php if ($isAntar): ?>
                <span class="card-keterangan">Menunggu Penjemputan</span>
            <?php else: ?>
                <span class="card-harga"><?= $e($harga) ?></span>
            <?php endif; ?>
        </div>
    </a>
    <?php
};

$emptyState = static function (string $icon, string $judul, string $sub, string $id): void { ?>
    <div class="empty-state show" id="<?= $id ?>">
        <div class="empty-icon"><?= $icon ?></div>
        <div class="empty-title"><?= $judul ?></div>
        <div class="empty-sub"><?= $sub ?></div>
    </div>
<?php };
?>
<body>
<div class="mobile-wrap">

    <!-- Header -->
    <div class="page-header">
        <a href="<?= base_url('dashboard') ?>" class="btn-back" id="btn-back-riwayat">&#8592;</a>
        <span class="page-title">Riwayat Pesanan</span>
    </div>

    <!-- Tab Filter -->
    <div class="tab-bar-wrap" id="riwayat-tab-wrap">
        <div class="tab-bar" id="riwayat-tab-bar" role="tablist">
            <button class="tab-btn active" data-tab="semua"      id="tab-semua"      role="tab" aria-selected="true">
                Semua<?php if (count($semua)):    ?> <span class="tab-count"><?= count($semua)      ?></span><?php endif; ?>
            </button>
            <button class="tab-btn"        data-tab="diproses"   id="tab-diproses"   role="tab" aria-selected="false">
                Diproses<?php if (count($diproses)):  ?> <span class="tab-count"><?= count($diproses)   ?></span><?php endif; ?>
            </button>
            <button class="tab-btn"        data-tab="selesai"    id="tab-selesai"    role="tab" aria-selected="false">
                Selesai<?php if (count($selesai)):   ?> <span class="tab-count"><?= count($selesai)    ?></span><?php endif; ?>
            </button>
            <button class="tab-btn"        data-tab="dibatalkan" id="tab-dibatalkan" role="tab" aria-selected="false">
                Dibatalkan<?php if (count($dibatalkan)):?> <span class="tab-count"><?= count($dibatalkan) ?></span><?php endif; ?>
            </button>
        </div>
    </div>

    <!-- ══ TAB: SEMUA ══════════════════════════════════════════ -->
    <div class="tab-panel show" id="panel-semua" role="tabpanel">
        <?php if (empty($semua)): ?>
            <?php $emptyState('🧺', 'Belum Ada Pesanan', 'Kamu belum pernah membuat pesanan laundry.', 'empty-semua'); ?>
        <?php else: ?>
            <div class="pesanan-list">
                <?php foreach ($semua as $i => $p): $kartuPesanan($p, $i + 1); endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ══ TAB: DIPROSES ═══════════════════════════════════════ -->
    <div class="tab-panel" id="panel-diproses" role="tabpanel">
        <?php if (empty($diproses)): ?>
            <?php $emptyState('✅', 'Tidak Ada Pesanan Aktif', 'Saat ini tidak ada pesanan yang sedang diproses.', 'empty-diproses'); ?>
        <?php else: ?>
            <div class="pesanan-list">
                <?php foreach ($diproses as $i => $p): $kartuPesanan($p, $i + 1); endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ══ TAB: SELESAI ════════════════════════════════════════ -->
    <div class="tab-panel" id="panel-selesai" role="tabpanel">
        <?php if (empty($selesai)): ?>
            <?php $emptyState('📦', 'Belum Ada Pesanan Selesai', 'Pesanan yang sudah selesai akan muncul di sini.', 'empty-selesai'); ?>
        <?php else: ?>
            <div class="pesanan-list">
                <?php foreach ($selesai as $i => $p): $kartuPesanan($p, $i + 1); endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ══ TAB: DIBATALKAN ═════════════════════════════════════ -->
    <div class="tab-panel" id="panel-dibatalkan" role="tabpanel">
        <?php if (empty($dibatalkan)): ?>
            <?php $emptyState('🚫', 'Tidak Ada Pesanan Dibatalkan', 'Belum ada pesanan yang dibatalkan.', 'empty-dibatalkan'); ?>
        <?php else: ?>
            <div class="pesanan-list">
                <?php foreach ($dibatalkan as $i => $p): $kartuPesanan($p, $i + 1); endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div><!-- /.mobile-wrap -->

<script>
(function () {
    'use strict';

    /* ── 1. Tab switching ───────────────────────────────────── */
    var tabs   = document.querySelectorAll('#riwayat-tab-bar [data-tab]');
    var panels = {
        semua      : document.getElementById('panel-semua'),
        diproses   : document.getElementById('panel-diproses'),
        selesai    : document.getElementById('panel-selesai'),
        dibatalkan : document.getElementById('panel-dibatalkan'),
    };

    function activate(key) {
        tabs.forEach(function (btn) {
            var isActive = btn.getAttribute('data-tab') === key;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        Object.keys(panels).forEach(function (k) {
            panels[k].classList.toggle('show', k === key);
        });
    }

    tabs.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activate(btn.getAttribute('data-tab'));
        });
    });

    /* ── 2. Mouse drag-to-scroll (desktop) ─────────────────── */
    var bar        = document.getElementById('riwayat-tab-bar');
    var wrap       = document.getElementById('riwayat-tab-wrap');
    var isDragging = false;
    var startX, scrollLeft;

    bar.addEventListener('mousedown', function (e) {
        isDragging = true;
        startX     = e.pageX - bar.offsetLeft;
        scrollLeft = bar.scrollLeft;
        bar.classList.add('dragging');
    });
    document.addEventListener('mouseup', function () {
        if (!isDragging) return;
        isDragging = false;
        bar.classList.remove('dragging');
    });
    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;
        e.preventDefault();
        var x    = e.pageX - bar.offsetLeft;
        var walk = (x - startX) * 1.5;
        bar.scrollLeft = scrollLeft - walk;
    });

    /* ── 3. Fade gradient hilang di ujung kanan ─────────────── */
    function checkScroll() {
        var atEnd = bar.scrollLeft + bar.clientWidth >= bar.scrollWidth - 4;
        wrap.classList.toggle('at-end', atEnd);
    }
    bar.addEventListener('scroll', checkScroll, { passive: true });
    checkScroll();
    window.addEventListener('load', checkScroll);
    window.addEventListener('resize', checkScroll);

})();
</script>
</body>
</html>
