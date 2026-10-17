<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$tab = $tab ?? 'semua';
$q = $q ?? '';
$ringkasan = $ringkasan ?? ['total' => 0, 'pengantaran' => 0, 'penjemputan' => 0];
$daftarRiwayat = $daftarRiwayat ?? [];
?>

<style>
/* ── Halaman Riwayat Kurir ── */
.riwayat-header {
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
    transition: transform 0.15s;
}
.btn-back-krr:hover {
    transform: translateX(-2px);
    color: #000;
}
.riwayat-title {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size: 18px;
    font-weight: 800;
    color: #000;
    margin: 0;
    white-space: nowrap;
}

/* ── 3 Kartu Metrik Ringkasan Riwayat ── */
.krr-summary-row-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
    margin-bottom: 16px;
}
.krr-summary-card-sm {
    border-radius: 14px;
    padding: 10px 10px 8px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}
.krr-summary-card-sm.c-green {
    background: #DCFCE7;
    border: 1px solid #86EFAC;
}
.krr-summary-card-sm.c-blue {
    background: #DBEAFE;
    border: 1px solid #93C5FD;
}
.krr-summary-card-sm.c-purple {
    background: #F3E8FF;
    border: 1px solid #D8B4FE;
}
.krr-summary-sm-icon {
    font-size: 18px;
    margin-bottom: 2px;
}
.krr-summary-sm-num {
    font-size: 18px;
    font-weight: 900;
    color: #0F172A;
    line-height: 1.1;
}
.krr-summary-sm-label {
    font-size: 9.5px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    margin-top: 2px;
}

/* ── Search & Filter Input ── */
.krr-search-form {
    margin-bottom: 12px;
}
.krr-search-box {
    position: relative;
    display: flex;
    align-items: center;
}
.krr-search-input {
    width: 100%;
    padding: 10px 38px 10px 38px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 999px;
    border: 1.5px solid #CBD5E1;
    background: #FFFFFF;
    color: #0F172A;
    outline: none;
    transition: all 0.2s;
}
.krr-search-input:focus {
    border-color: #0047AB;
    box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.12);
}
.krr-search-icon {
    position: absolute;
    left: 14px;
    font-size: 14px;
    color: #64748B;
}
.krr-clear-search {
    position: absolute;
    right: 12px;
    font-size: 14px;
    color: #94A3B8;
    text-decoration: none;
    cursor: pointer;
}
.krr-clear-search:hover {
    color: #475569;
}

/* ── Filter Tabs Segmented Control ── */
.krr-filter-tabs {
    background: #E5E7EB;
    border-radius: 999px;
    display: flex;
    padding: 4px;
    margin-bottom: 16px;
}
.krr-tab-item {
    flex: 1;
    text-align: center;
    padding: 7px 4px;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    color: #1E293B;
    border-radius: 999px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.krr-tab-item:hover {
    color: #000;
}
.krr-tab-item.active {
    background: #93C5FD;
    color: #000;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

/* ── History Task Card ── */
.krr-history-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 14px;
    margin-bottom: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: transform 0.15s, box-shadow 0.15s;
}
.krr-history-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.krr-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}
.krr-badge-tipe-selesai {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10.5px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}
.krr-badge-tipe-selesai.green {
    background: #DCFCE7;
    color: #15803D;
    border: 1px solid #86EFAC;
}
.krr-badge-tipe-selesai.blue {
    background: #DBEAFE;
    color: #1D4ED8;
    border: 1px solid #93C5FD;
}
.krr-task-code {
    font-size: 13.5px;
    font-weight: 900;
    color: #0F172A;
}

/* Timestamp Row */
.krr-timestamp-row {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #64748B;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px dashed #E2E8F0;
}
.krr-timestamp-row i {
    font-size: 12px;
    color: #0047AB;
}

/* Details Section */
.krr-history-body {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 12px;
}
.krr-history-row {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 12.5px;
    color: #1E293B;
}
.krr-history-row i {
    font-size: 14px;
    color: #475569;
    margin-top: 2px;
    flex-shrink: 0;
}
.krr-history-row.customer-row {
    justify-content: space-between;
    align-items: center;
}
.krr-customer-name {
    font-size: 14px;
    font-weight: 800;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 6px;
}
.krr-contact-links {
    display: flex;
    gap: 6px;
}
.btn-wa-sm {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #25D366;
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    text-decoration: none;
    transition: opacity 0.15s;
}
.btn-wa-sm:hover {
    color: #fff;
    opacity: 0.9;
}

/* Info Cucian & Pembayaran */
.krr-meta-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 8px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 4px;
}
.krr-meta-item {
    font-size: 11.5px;
    color: #475569;
}
.krr-meta-item strong {
    color: #0F172A;
}
.krr-meta-lunas {
    font-size: 11px;
    font-weight: 800;
    color: #16A34A;
    background: #DCFCE7;
    padding: 2px 7px;
    border-radius: 6px;
}

/* Card Footer */
.krr-history-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding-top: 10px;
    border-top: 1px solid #F1F5F9;
}
.krr-status-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    color: #166534;
}
.btn-detail-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 700;
    color: #0047AB;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 6px;
    background: #EFF6FF;
    transition: all 0.15s;
}
.btn-detail-link:hover {
    background: #DBEAFE;
    color: #003685;
}

/* Empty State */
.krr-empty-box {
    background: #FFFFFF;
    border: 1.5px dashed #CBD5E1;
    border-radius: 14px;
    padding: 36px 20px;
    text-align: center;
    color: #64748B;
    margin-top: 10px;
}
.krr-empty-icon {
    font-size: 42px;
    color: #94A3B8;
    margin-bottom: 8px;
}
.krr-empty-title {
    font-size: 15px;
    font-weight: 800;
    color: #1E293B;
    margin-bottom: 4px;
}
.krr-empty-desc {
    font-size: 12px;
    color: #64748B;
    line-height: 1.4;
}
</style>

<!-- Header Riwayat Kurir -->
<div class="riwayat-header">
    <a href="<?= base_url('kurir/dashboard') ?>" class="btn-back-krr" title="Kembali ke Dashboard">&#8592;</a>
    <h1 class="riwayat-title">Riwayat Pengantaran</h1>
</div>

<!-- 3 Kartu Metrik Ringkasan -->
<div class="krr-summary-row-3">
    <!-- Total Selesai -->
    <div class="krr-summary-card-sm c-green">
        <div class="krr-summary-sm-icon">🎉</div>
        <div class="krr-summary-sm-num"><?= (int)($ringkasan['total'] ?? 0) ?></div>
        <div class="krr-summary-sm-label">Total Selesai</div>
    </div>

    <!-- Pengantaran Selesai -->
    <div class="krr-summary-card-sm c-blue">
        <div class="krr-summary-sm-icon">🚚</div>
        <div class="krr-summary-sm-num"><?= (int)($ringkasan['pengantaran'] ?? 0) ?></div>
        <div class="krr-summary-sm-label">Pengantaran</div>
    </div>

    <!-- Penjemputan Selesai -->
    <div class="krr-summary-card-sm c-purple">
        <div class="krr-summary-sm-icon">📦</div>
        <div class="krr-summary-sm-num"><?= (int)($ringkasan['penjemputan'] ?? 0) ?></div>
        <div class="krr-summary-sm-label">Penjemputan</div>
    </div>
</div>

<!-- Form Pencarian Riwayat -->
<form method="get" action="<?= base_url('kurir/riwayat') ?>" class="krr-search-form">
    <input type="hidden" name="tab" value="<?= $e($tab) ?>">
    <div class="krr-search-box">
        <i class="bi bi-search krr-search-icon"></i>
        <input type="text"
               name="q"
               value="<?= $e($q) ?>"
               class="krr-search-input"
               placeholder="Cari no. pesanan, pelanggan, alamat...">
        <?php if ($q !== ''): ?>
            <a href="<?= base_url('kurir/riwayat?tab=' . urlencode($tab)) ?>" class="krr-clear-search" title="Hapus filter">
                <i class="bi bi-x-circle-fill"></i>
            </a>
        <?php endif; ?>
    </div>
</form>

<!-- Filter Tabs Segmented Control -->
<div class="krr-filter-tabs">
    <a href="<?= base_url('kurir/riwayat?tab=semua' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
       class="krr-tab-item <?= $tab === 'semua' ? 'active' : '' ?>">
        SEMUA (<?= (int)($ringkasan['total'] ?? 0) ?>)
    </a>
    <a href="<?= base_url('kurir/riwayat?tab=pengantaran' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
       class="krr-tab-item <?= $tab === 'pengantaran' ? 'active' : '' ?>">
        PENGANTARAN (<?= (int)($ringkasan['pengantaran'] ?? 0) ?>)
    </a>
    <a href="<?= base_url('kurir/riwayat?tab=penjemputan' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
       class="krr-tab-item <?= $tab === 'penjemputan' ? 'active' : '' ?>">
        PENJEMPUTAN (<?= (int)($ringkasan['penjemputan'] ?? 0) ?>)
    </a>
</div>

<!-- Daftar Kartu Riwayat -->
<?php if (empty($daftarRiwayat)): ?>
    <div class="krr-empty-box">
        <div class="krr-empty-icon">
            <i class="bi bi-clock-history"></i>
        </div>
        <div class="krr-empty-title">Belum Ada Riwayat</div>
        <div class="krr-empty-desc">
            <?= $q !== '' ? 'Tidak ditemukan riwayat tugas dengan kata kunci "' . $e($q) . '".' : 'Belum ada tugas pengantaran atau penjemputan yang diselesaikan.' ?>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($daftarRiwayat as $r): ?>
        <?php
        $isJemput = (bool) $r['is_jemput'];
        $cleanPhone = preg_replace('/[^0-9]/', '', (string)$r['no_hp']);
        if ($cleanPhone !== '' && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        ?>
        <div class="krr-history-card">
            <!-- Top Row: Badge Tipe & Kode -->
            <div class="krr-card-top">
                <div class="krr-badge-tipe-selesai <?= $isJemput ? 'blue' : 'green' ?>">
                    <?php if ($isJemput): ?>
                        <i class="bi bi-box-seam-fill"></i> PENJEMPUTAN SELESAI
                    <?php else: ?>
                        <i class="bi bi-truck"></i> PENGANTARAN SELESAI
                    <?php endif; ?>
                </div>
                <div class="krr-task-code"><?= $e((string)$r['kode']) ?></div>
            </div>

            <!-- Timestamp Waktu Selesai -->
            <div class="krr-timestamp-row">
                <i class="bi bi-calendar-check-fill"></i>
                <span>Selesai pada: <strong><?= $e((string)$r['waktu']) ?></strong></span>
            </div>

            <!-- Details Section -->
            <div class="krr-history-body">
                <!-- Customer Name & Quick Contact -->
                <div class="krr-history-row customer-row">
                    <div class="krr-customer-name">
                        <i class="bi bi-person-fill"></i>
                        <span><?= $e((string)$r['nama']) ?></span>
                    </div>
                    <?php if ($cleanPhone !== ''): ?>
                        <div class="krr-contact-links">
                            <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="btn-wa-sm" title="Chat WhatsApp">
                                <i class="bi bi-whatsapp"></i> WA
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Alamat Tujuan / Pengambilan -->
                <div class="krr-history-row">
                    <i class="bi bi-geo-alt-fill text-danger"></i>
                    <span><?= $e((string)$r['alamat']) ?></span>
                </div>

                <!-- Layanan & Total Pembayaran -->
                <div class="krr-meta-box">
                    <div class="krr-meta-item">
                        <span>Layanan:</span> <strong><?= $e((string)$r['layanan']) ?></strong> (<?= (float)$r['berat'] ?> KG)
                    </div>
                    <div class="krr-meta-lunas">
                        ✔ Rp <?= number_format((float)$r['total'], 0, ',', '.') ?> (Lunas)
                    </div>
                </div>
            </div>

            <!-- Footer Card -->
            <div class="krr-history-footer">
                <div class="krr-status-tag">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span><?= $e((string)$r['status_label']) ?></span>
                </div>
                <a href="<?= base_url('kurir/tugas/detail/' . (int)$r['id_pesanan']) ?>" class="btn-detail-link">
                    Rincian Tugas &rarr;
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
