<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$tab = $tab ?? 'semua';
$ringkasan = $ringkasan ?? ['aktif' => 2, 'selesai' => 1];
$daftarTugas = $daftarTugas ?? [];
?>

<style>
/* ── Halaman Tugas Kurir ── */
.tugas-header {
    display: flex;
    align-items: center;
    position: relative;
    padding: 10px 0 16px;
    margin-bottom: 8px;
}
.btn-back-krr {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    text-decoration: none;
    color: #000;
    font-size: 22px;
    font-weight: 800;
}
.tugas-title {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size: 18px;
    font-weight: 800;
    color: #000;
    margin: 0;
    white-space: nowrap;
}

/* ── 2 Kartu Metrik Ringkasan (Pill Card) ── */
.krr-summary-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 16px;
}
.krr-summary-card {
    border-radius: 18px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.krr-summary-card.c-lilac {
    background: #E9D5FF;
}
.krr-summary-card.c-mint {
    background: #DCFCE7;
}
.krr-summary-icon {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.krr-summary-info {
    display: flex;
    flex-direction: column;
}
.krr-summary-label {
    font-size: 10px;
    font-weight: 800;
    color: #000;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}
.krr-summary-num {
    font-size: 22px;
    font-weight: 900;
    color: #000;
    line-height: 1.1;
}

/* ── Filter Tabs Segmented Control ── */
.krr-filter-tabs {
    background: #E5E7EB;
    border-radius: 999px;
    display: flex;
    padding: 4px;
    margin-bottom: 20px;
}
.krr-tab-item {
    flex: 1;
    text-align: center;
    padding: 8px 4px;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    color: #1E293B;
    border-radius: 999px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.krr-tab-item.active {
    background: #93C5FD;
    color: #000;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

/* ── Task Card ── */
.krr-task-card {
    background: #DDE2E5;
    border: 1.5px solid #1E293B;
    border-radius: 10px;
    padding: 14px 14px 12px;
    margin-bottom: 18px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.04);
}
.krr-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.krr-badge-tipe {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #93C5FD;
    color: #000;
    font-size: 11px;
    font-weight: 800;
    padding: 4px 12px;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}
.krr-task-code {
    font-size: 15px;
    font-weight: 900;
    color: #000;
}

/* Middle info with map sketch */
.krr-card-middle {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
}
.krr-details-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.krr-detail-row {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: #000;
}
.krr-detail-row.bold-name {
    font-size: 15px;
    font-weight: 800;
}
.krr-detail-row i {
    font-size: 15px;
    color: #1E293B;
    flex-shrink: 0;
}
.krr-map-sketch-col {
    flex-shrink: 0;
    margin-top: 2px;
}

/* Status badge */
.krr-status-row {
    margin-bottom: 12px;
}
.krr-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    color: #000;
}
.krr-status-pill.peach {
    background: #FFB295;
}
.krr-status-pill.green {
    background: #A7F3D0;
}

/* Tombol Lihat Detail */
.btn-lihat-detail {
    display: block;
    width: 100%;
    text-align: center;
    background: #0047AB;
    color: #FFFFFF;
    font-size: 13px;
    font-weight: 700;
    padding: 9px 0;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-lihat-detail:hover {
    background: #003685;
    color: #FFFFFF;
}
</style>

<!-- Header Tugas Kurir -->
<div class="tugas-header">
    <a href="<?= base_url('kurir/dashboard') ?>" class="btn-back-krr"><i class="bi bi-arrow-left"></i></a>
    <h1 class="tugas-title">Tugas Kurir</h1>
</div>

<?php if (session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success py-2 px-3 mb-3 fw-bold small rounded-3">
        <?= session()->getFlashdata('pesan') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger py-2 px-3 mb-3 fw-bold small rounded-3">
        <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
            <div><?= esc($err) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 2 Kartu Metrik Ringkasan -->
<div class="krr-summary-row">
    <!-- 1. Tugas Aktif -->
    <div class="krr-summary-card c-lilac">
        <div class="krr-summary-icon">
            <!-- 3D Box Outline Icon -->
            <svg width="36" height="36" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 5L34 12V28L20 35L6 28V12L20 5Z" stroke="#1E293B" stroke-width="2.2" stroke-linejoin="round" fill="#FFFFFF"/>
                <path d="M20 5V35" stroke="#1E293B" stroke-width="2"/>
                <path d="M6 12L20 20L34 12" stroke="#1E293B" stroke-width="2"/>
                <path d="M13 8.5L27 16.5" stroke="#1E293B" stroke-width="1.8" stroke-dasharray="2 2"/>
            </svg>
        </div>
        <div class="krr-summary-info">
            <span class="krr-summary-label">Tugas Aktif</span>
            <span class="krr-summary-num"><?= (int)($ringkasan['aktif'] ?? 2) ?></span>
        </div>
    </div>

    <!-- 2. Selesai -->
    <div class="krr-summary-card c-mint">
        <div class="krr-summary-icon">
            <!-- Green Circle Checkmark Icon -->
            <svg width="34" height="34" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="18" cy="18" r="16" fill="#22C55E"/>
                <path d="M11 18.5L15.5 23L25 13.5" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="krr-summary-info">
            <span class="krr-summary-label">Selesai</span>
            <span class="krr-summary-num"><?= (int)($ringkasan['selesai'] ?? 1) ?></span>
        </div>
    </div>
</div>

<!-- Segmented Filter Tabs -->
<div class="krr-filter-tabs">
    <a href="<?= base_url('kurir/tugas?tab=semua') ?>"
       class="krr-tab-item <?= $tab === 'semua' ? 'active' : '' ?>">
        SEMUA
    </a>
    <a href="<?= base_url('kurir/tugas?tab=penjemputan') ?>"
       class="krr-tab-item <?= $tab === 'penjemputan' ? 'active' : '' ?>">
        PENJEMPUTAN
    </a>
    <a href="<?= base_url('kurir/tugas?tab=pengantaran') ?>"
       class="krr-tab-item <?= $tab === 'pengantaran' ? 'active' : '' ?>">
        PENGANTARAN
    </a>
</div>

<!-- Daftar Kartu Tugas -->
<?php if (empty($daftarTugas)): ?>
    <div class="panel kosong text-center py-4">
        <p class="text-muted fw-bold">Tidak ada tugas pada filter ini.</p>
    </div>
<?php else: ?>
    <?php foreach ($daftarTugas as $t): ?>
        <?php
        $isJemput = ($t['tipe'] === 'penjemputan');
        ?>
        <div class="krr-task-card">
            <!-- Top Row: Badge Tipe & Kode -->
            <div class="krr-card-top">
                <div class="krr-badge-tipe">
                    <?php if ($isJemput): ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        PENJEMPUTAN
                    <?php else: ?>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        PENGANTARAN
                    <?php endif; ?>
                </div>
                <div class="krr-task-code"><?= $e((string)$t['kode']) ?></div>
            </div>

            <!-- Middle Section: Customer Details & Map Sketch Icon -->
            <div class="krr-card-middle">
                <div class="krr-details-col">
                    <div class="krr-detail-row bold-name">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span><?= $e((string)$t['nama']) ?></span>
                    </div>
                    <div class="krr-detail-row">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span><?= $e((string)$t['alamat']) ?></span>
                    </div>
                    <div class="krr-detail-row">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span><?= $e((string)$t['jam']) ?></span>
                    </div>
                </div>

                <!-- Folded Map Sketch Icon (Sesuai Mockup) -->
                <div class="krr-map-sketch-col">
                    <svg width="48" height="48" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="2" width="50" height="50" rx="8" stroke="#1E293B" stroke-width="2" fill="#FFFFFF"/>
                        <!-- Folded Map Lines -->
                        <path d="M10 16L20 11L33 16L44 11V37L33 42L20 37L10 42V16Z" stroke="#1E293B" stroke-width="1.8" stroke-linejoin="round"/>
                        <line x1="20" y1="11" x2="20" y2="37" stroke="#1E293B" stroke-width="1.8"/>
                        <line x1="33" y1="16" x2="33" y2="42" stroke="#1E293B" stroke-width="1.8"/>
                        <!-- Location Markers on Map -->
                        <path d="M19 21 C19 19, 23 19, 23 21 C23 23, 21 26, 21 26 C21 26, 19 23, 19 21 Z" fill="#1E293B"/>
                        <path d="M31 27 C31 25, 35 25, 35 27 C35 29, 33 32, 33 32 C33 32, 31 29, 31 27 Z" fill="#1E293B"/>
                    </svg>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="krr-status-row">
                <span class="krr-status-pill <?= $t['status_color'] === 'green' ? 'green' : 'peach' ?>">
                    <?php if ($t['status_color'] === 'green'): ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    <?php else: ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    <?php endif; ?>
                    <?= $e((string)$t['status_label']) ?>
                </span>
            </div>

            <!-- Action Button: Lihat Detail -->
            <a href="<?= base_url('kurir/tugas/detail/' . (int)$t['id_pesanan']) ?>" class="btn-lihat-detail">
                Lihat Detail &gt;
            </a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
function tampilkanDetailInfo(kode, nama) {
    var toast = document.getElementById('toast');
    if (toast) {
        toast.textContent = 'Detail tugas ' + kode + ' (' + nama + ') segera hadir';
        toast.classList.add('show');
        setTimeout(function() {
            toast.classList.remove('show');
        }, 2200);
    }
}
</script>

<?= $this->endSection() ?>
