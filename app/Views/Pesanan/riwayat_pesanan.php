<?php
$judul = 'Riwayat Pesanan';
?>
<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
// ── Helper lokal ─────────────────────────────────────────────
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

$kartuPesanan = static function (array $p, int $idx) use ($badgeClass, $labelStatus, $formatRupiah, $formatTanggal): void {
    $e = static fn(string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
    $nomorPesanan  = 'PSN-' . date('dmY', strtotime((string)($p['tanggal'] ?? $p['created_at'] ?? 'now'))) . '-' . str_pad((string)($p['id_pesanan'] ?? 0), 3, '0', STR_PAD_LEFT);
    $tanggal       = $formatTanggal((string)($p['tanggal'] ?? ''));
    $layanan       = $e((string)($p['nama_layanan'] ?? '-'));
    $berat         = number_format((float)($p['berat'] ?? 0), 1, ',', '.') . ' Kg';
    $status        = (string)($p['status_pesanan'] ?? 'menunggu');
    $statusBayar   = (string)($p['status_bayar'] ?? '');
    $total         = (float)($p['total_harga'] ?? 0);
    $badge         = $badgeClass($status);
    $label         = $labelStatus($status);
    $harga         = $formatRupiah($total);
    $isAntar       = ($status === 'diantar');
    $sudahBayar    = in_array($statusBayar, ['lunas', 'menunggu_verifikasi'], true);
    $idPesanan     = (int)$p['id_pesanan'];

    // Tentukan href: tracking jika sudah bayar, jika belum bayar arahkan langsung ke upload-bukti jika metode sudah dipilih atau ke pembayaran
    $metodeDipilih = (string)($p['metode'] ?? '');
    $href = $sudahBayar
        ? base_url('pesanan/tracking/' . $idPesanan)
        : ($metodeDipilih !== '' && $metodeDipilih !== 'Belum Dipilih'
            ? base_url('pesanan/upload-bukti/' . $idPesanan)
            : base_url('pesanan/pembayaran/' . $idPesanan));
    ?>
    <a class="pesanan-card" href="<?= $e($href) ?>" id="card-<?= $e($status) ?>-<?= $idx ?>">
        <div class="card-top">
            <span class="order-id"><?= $e($nomorPesanan) ?></span>
            <span class="badge-status <?= $e($badge) ?>"><?= $e($label) ?></span>
        </div>
        <div class="card-date"><?= $e($tanggal) ?></div>
        <div class="card-layanan"><?= $layanan ?></div>
        <div class="card-divider"></div>
        <div class="card-bottom">
            <span class="card-berat"><?= $e($berat) ?></span>
            <?php if (!$sudahBayar): ?>
                <span class="card-keterangan" style="color:#E65100;font-weight:600;font-size:13px">
                    ⚠ <?= ($metodeDipilih !== '' && $metodeDipilih !== 'Belum Dipilih') ? 'Upload Bukti Bayar →' : 'Bayar Sekarang →' ?>
                </span>
            <?php elseif ($isAntar): ?>
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

<style>
    .judul-halaman {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 18px 0 16px;
    }
    .judul-halaman .kembali {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: #0b3d5c;
        font-size: 22px;
        text-decoration: none;
        background: transparent;
        transition: background 0.15s, transform 0.15s;
    }
    .judul-halaman .kembali:hover {
        background: rgba(11, 61, 92, 0.08);
        color: #0b3d5c;
        transform: translateX(-2px);
    }
    .judul-halaman h3 {
        margin: 0;
        font-weight: 700;
        color: #0b3d5c;
        font-size: 20px;
    }

    /* ── Tab Filter ──────────────────────────────────────── */
    .tab-bar-wrap {
        position: relative;
        margin-bottom: 16px;
    }
    .tab-bar-wrap::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 100%;
        background: linear-gradient(to right, transparent, rgba(255,255,255,0.85));
        pointer-events: none;
        transition: opacity 0.2s;
    }
    .tab-bar-wrap.at-end::after { opacity: 0; }

    .tab-bar {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
        padding: 4px 2px 12px;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        scrollbar-width: none;
        cursor: grab;
    }
    .tab-bar::-webkit-scrollbar { display: none; }
    .tab-bar.dragging { cursor: grabbing; user-select: none; }

    .tab-btn {
        flex: 0 0 auto;
        padding: 8px 18px;
        border-radius: 999px;
        border: 1.5px solid #D0D0D0;
        background: transparent;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        color: #555;
        cursor: pointer;
        transition: background 0.18s, color 0.18s, border-color 0.18s;
        white-space: nowrap;
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
        margin-left: 4px;
    }
    .tab-btn.active .tab-count {
        background: rgba(255,255,255,0.3);
        color: #fff;
    }
    .tab-btn:not(.active) .tab-count {
        background: #033B59;
        color: #fff;
    }

    /* ── Pesanan List ────────────────────────────────────── */
    .pesanan-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 0 0 28px;
    }

    /* ── Pesanan Card ────────────────────────────────────── */
    .pesanan-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #eef0f2;
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
    .badge-status {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
    }
    .badge-diproses   { background: #e3f2fd; color: #033B59; }
    .badge-dicuci     { background: #e8f5e9; color: #2e7d32; }
    .badge-selesai    { background: #e8f5e9; color: #1b5e20; }
    .badge-dibatalkan { background: #fee2e2; color: #c62828; }
    .badge-menunggu   { background: #fff8e1; color: #b45309; }

    .card-date {
        font-size: 12px;
        color: #666;
        margin-bottom: 8px;
    }
    .card-layanan {
        font-size: 14px;
        font-weight: 600;
        color: #033B59;
        margin-bottom: 12px;
    }
    .card-divider {
        height: 1px;
        background: #f0f0f0;
        margin-bottom: 12px;
    }
    .card-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }
    .card-berat {
        color: #555;
        font-weight: 500;
    }
    .card-keterangan {
        font-size: 12px;
        color: #888;
        font-style: italic;
    }
    .card-harga {
        font-weight: 700;
        font-size: 14px;
        color: #033B59;
    }

    /* ── Empty State ─────────────────────────────────────── */
    .empty-state {
        display: none;
        text-align: center;
        padding: 48px 24px;
        background: #fff;
        border-radius: 16px;
        margin: 8px 0 28px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #eef0f2;
    }
    .empty-state.show { display: block; }
    .empty-icon  { font-size: 40px; margin-bottom: 12px; line-height: 1; }
    .empty-title { font-weight: 700; font-size: 16px; color: #333; margin-bottom: 6px; }
    .empty-sub   { font-size: 13px; color: #888; }

    /* ── Tab panel visibility ────────────────────────────── */
    .tab-panel { display: none; }
    .tab-panel.show { display: block; }
</style>

<div class="judul-halaman">
    <a href="<?= base_url('dashboard') ?>" class="kembali" id="btn-back-riwayat" title="Kembali ke Beranda" aria-label="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h3>Riwayat Pesanan</h3>
</div>

<!-- Tab Filter -->
<div class="tab-bar-wrap" id="riwayat-tab-wrap">
    <div class="tab-bar" id="riwayat-tab-bar" role="tablist">
        <button class="tab-btn active" data-tab="semua" id="tab-semua" role="tab" aria-selected="true">
            Semua<?php if (count($semua)): ?> <span class="tab-count"><?= count($semua) ?></span><?php endif; ?>
        </button>
        <button class="tab-btn" data-tab="diproses" id="tab-diproses" role="tab" aria-selected="false">
            Diproses<?php if (count($diproses)): ?> <span class="tab-count"><?= count($diproses) ?></span><?php endif; ?>
        </button>
        <button class="tab-btn" data-tab="selesai" id="tab-selesai" role="tab" aria-selected="false">
            Selesai<?php if (count($selesai)): ?> <span class="tab-count"><?= count($selesai) ?></span><?php endif; ?>
        </button>
        <button class="tab-btn" data-tab="dibatalkan" id="tab-dibatalkan" role="tab" aria-selected="false">
            Dibatalkan<?php if (count($dibatalkan)): ?> <span class="tab-count"><?= count($dibatalkan) ?></span><?php endif; ?>
        </button>
    </div>
</div>

<!-- Tab Panels -->
<div id="panel-semua" class="tab-panel show" role="tabpanel" aria-labelledby="tab-semua">
    <?php if (!empty($semua)): ?>
        <div class="pesanan-list">
            <?php foreach ($semua as $i => $item): $kartuPesanan($item, $i); endforeach; ?>
        </div>
    <?php else: ?>
        <?php $emptyState('📋', 'Belum Ada Pesanan', 'Pesanan kamu akan muncul di sini setelah kamu melakukan pemesanan.', 'empty-semua'); ?>
    <?php endif; ?>
</div>

<div id="panel-diproses" class="tab-panel" role="tabpanel" aria-labelledby="tab-diproses">
    <?php if (!empty($diproses)): ?>
        <div class="pesanan-list">
            <?php foreach ($diproses as $i => $item): $kartuPesanan($item, $i); endforeach; ?>
        </div>
    <?php else: ?>
        <?php $emptyState('🧺', 'Tidak Ada Pesanan Diproses', 'Saat ini tidak ada pesanan yang sedang diproses.', 'empty-diproses'); ?>
    <?php endif; ?>
</div>

<div id="panel-selesai" class="tab-panel" role="tabpanel" aria-labelledby="tab-selesai">
    <?php if (!empty($selesai)): ?>
        <div class="pesanan-list">
            <?php foreach ($selesai as $i => $item): $kartuPesanan($item, $i); endforeach; ?>
        </div>
    <?php else: ?>
        <?php $emptyState('✨', 'Belum Ada Pesanan Selesai', 'Pesanan yang sudah selesai akan tersimpan di sini.', 'empty-selesai'); ?>
    <?php endif; ?>
</div>

<div id="panel-dibatalkan" class="tab-panel" role="tabpanel" aria-labelledby="tab-dibatalkan">
    <?php if (!empty($dibatalkan)): ?>
        <div class="pesanan-list">
            <?php foreach ($dibatalkan as $i => $item): $kartuPesanan($item, $i); endforeach; ?>
        </div>
    <?php else: ?>
        <?php $emptyState('❌', 'Tidak Ada Pesanan Dibatalkan', 'Pesanan yang dibatalkan akan muncul di sini.', 'empty-dibatalkan'); ?>
    <?php endif; ?>
</div>

<script>
(function () {
    /* ── 1. Tab switching ───────────────────────────────────── */
    var tabs   = document.querySelectorAll('.tab-btn');
    var panels = document.querySelectorAll('.tab-panel');

    function activate(tabName) {
        tabs.forEach(function (btn) {
            var active = btn.getAttribute('data-tab') === tabName;
            btn.classList.toggle('active', active);
            btn.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        panels.forEach(function (p) {
            p.classList.toggle('show', p.id === 'panel-' + tabName);
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
    if (bar && wrap) {
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
    }
})();
</script>
<?= $this->endSection() ?>
