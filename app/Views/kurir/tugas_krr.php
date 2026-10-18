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
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    color: #033B59;
    font-size: 20px;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.btn-back-krr:hover {
    background: #e2e8f0;
    color: #022c44;
}
.tugas-title {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size: 18px;
    font-weight: 700;
    color: #033B59;
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
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.03);
    transition: transform 0.15s, box-shadow 0.15s;
}
.krr-summary-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}
.krr-summary-card.c-aktif .krr-summary-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #e0f2fe;
    color: #033B59;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.krr-summary-card.c-selesai .krr-summary-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #f0fdf4;
    color: #16a34a;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.krr-summary-info {
    display: flex;
    flex-direction: column;
}
.krr-summary-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}
.krr-summary-num {
    font-size: 24px;
    font-weight: 800;
    color: #033B59;
    line-height: 1.1;
    margin-top: 2px;
}
.krr-summary-card.c-selesai .krr-summary-num {
    color: #15803d;
}

/* ── Filter Tabs Segmented Control ── */
.krr-filter-tabs {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    display: flex;
    padding: 4px;
    margin-bottom: 18px;
    gap: 4px;
}
.krr-tab-item {
    flex: 1;
    text-align: center;
    padding: 8px 6px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    color: #64748b;
    border-radius: 9px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.krr-tab-item:hover {
    color: #033B59;
}
.krr-tab-item.active {
    background: #033B59;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(3, 59, 89, 0.2);
}

/* ── Task Card ── */
.krr-task-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px 16px;
    margin-bottom: 16px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.04);
    transition: all 0.2s ease;
}
.krr-task-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
}
.krr-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px dashed #e2e8f0;
}
.krr-badge-tipe {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 8px;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}
.krr-badge-tipe.tipe-jemput {
    background: #e0f2fe;
    color: #0284c7;
    border: 1px solid #bae6fd;
}
.krr-badge-tipe.tipe-antar {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
.krr-task-code {
    font-size: 14px;
    font-weight: 800;
    color: #033B59;
}

/* Middle info with map sketch */
.krr-card-middle {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 14px;
}
.krr-details-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.krr-detail-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #475569;
}
.krr-detail-row.bold-name {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}
.krr-detail-row i {
    font-size: 15px;
    color: #033B59;
    flex-shrink: 0;
}
.krr-cust-avatar-col {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #e0f2fe;
    border: 2px solid #bae6fd;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(3, 59, 89, 0.08);
}
.krr-cust-avatar-col img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.krr-cust-avatar-col .krr-cust-initial {
    font-size: 17px;
    font-weight: 800;
    color: #033B59;
}

/* Status badge */
.krr-status-row {
    margin-bottom: 14px;
}
.krr-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
}
.krr-status-pill.green {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}
.krr-status-pill.peach {
    background: #fefce8;
    color: #854d0e;
    border: 1px solid #fef08a;
}
.krr-status-pill.blue {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

/* Tombol Lihat Detail */
.btn-lihat-detail {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    text-align: center;
    background: #033B59;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(3, 59, 89, 0.2);
    transition: all 0.2s;
}
.btn-lihat-detail:hover {
    background: #022c44;
    color: #ffffff;
    box-shadow: 0 6px 14px rgba(3, 59, 89, 0.3);
}
</style>

<!-- Header Tugas Kurir -->
<div class="tugas-header">
    <a href="<?= base_url('kurir/dashboard') ?>" class="btn-back-krr" title="Kembali"><i class="bi bi-arrow-left"></i></a>
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
    <div class="krr-summary-card c-aktif">
        <div class="krr-summary-icon">
            <i class="bi bi-box-seam-fill"></i>
        </div>
        <div class="krr-summary-info">
            <span class="krr-summary-label">Tugas Aktif</span>
            <span class="krr-summary-num"><?= (int)($ringkasan['aktif'] ?? 0) ?></span>
        </div>
    </div>

    <!-- 2. Selesai -->
    <div class="krr-summary-card c-selesai">
        <div class="krr-summary-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="krr-summary-info">
            <span class="krr-summary-label">Selesai</span>
            <span class="krr-summary-num"><?= (int)($ringkasan['selesai'] ?? 0) ?></span>
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
    <div class="panel kosong text-center py-4 bg-white rounded-4 border">
        <i class="bi bi-inbox text-muted fs-1 d-block mb-2"></i>
        <p class="text-muted fw-bold mb-0">Tidak ada tugas pada filter ini.</p>
    </div>
<?php else: ?>
    <?php foreach ($daftarTugas as $t): ?>
        <?php
        $isJemput = ($t['tipe'] === 'penjemputan');
        ?>
        <div class="krr-task-card">
            <!-- Top Row: Badge Tipe & Kode -->
            <div class="krr-card-top">
                <div class="krr-badge-tipe <?= $isJemput ? 'tipe-jemput' : 'tipe-antar' ?>">
                    <i class="bi <?= $isJemput ? 'bi-box-seam-fill' : 'bi-truck' ?>"></i>
                    <?= $isJemput ? 'PENJEMPUTAN' : 'PENGANTARAN' ?>
                </div>
                <div class="krr-task-code"><?= $e((string)$t['kode']) ?></div>
            </div>

            <!-- Middle Section: Customer Details & Map Icon -->
            <div class="krr-card-middle">
                <div class="krr-details-col">
                    <div class="krr-detail-row bold-name">
                        <i class="bi bi-person-fill"></i>
                        <span><?= $e((string)$t['nama']) ?></span>
                    </div>
                    <div class="krr-detail-row">
                        <i class="bi bi-geo-alt"></i>
                        <span><?= $e((string)$t['alamat']) ?></span>
                    </div>
                    <div class="krr-detail-row">
                        <i class="bi bi-clock"></i>
                        <span><?= $e((string)$t['jam']) ?></span>
                    </div>
                </div>

                <!-- Foto Profil Pelanggan -->
                <?php
                $urlFotoPlg = url_foto_profil($t['foto'] ?? null);
                $inisialPlg = strtoupper(substr((string)$t['nama'], 0, 1));
                ?>
                <div class="krr-cust-avatar-col" title="Foto Pelanggan: <?= $e((string)$t['nama']) ?>">
                    <?php if ($urlFotoPlg): ?>
                        <img src="<?= $e($urlFotoPlg) ?>" alt="<?= $e((string)$t['nama']) ?>">
                    <?php else: ?>
                        <span class="krr-cust-initial"><?= $e($inisialPlg ?: 'P') ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="krr-status-row">
                <span class="krr-status-pill <?= $t['status_color'] === 'green' ? 'green' : ($t['status_color'] === 'blue' ? 'blue' : 'peach') ?>">
                    <i class="bi <?= $t['status_color'] === 'green' ? 'bi-check-circle-fill' : 'bi-clock-history' ?>"></i>
                    <?= $e((string)$t['status_label']) ?>
                </span>
            </div>

            <!-- Action Button: Lihat Detail -->
            <a href="<?= base_url('kurir/tugas/detail/' . (int)$t['id_pesanan']) ?>" class="btn-lihat-detail">
                Lihat Detail <i class="bi bi-chevron-right ms-1"></i>
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
