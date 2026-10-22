<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn(string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
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

    .riwayat-title {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-size: 18px;
        font-weight: 700;
        color: #033B59;
        margin: 0;
        white-space: nowrap;
    }

    /* ── 3 Kartu Metrik Ringkasan Riwayat ── */
    .krr-summary-row-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 16px;
    }

    .krr-summary-card-sm {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 12px 8px 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .krr-summary-card-sm:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .krr-summary-sm-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 6px;
    }

    .krr-summary-card-sm.c-total .krr-summary-sm-icon {
        background: #f0fdf4;
        color: #16a34a;
    }

    .krr-summary-card-sm.c-antar .krr-summary-sm-icon {
        background: #fef3c7;
        color: #b45309;
    }

    .krr-summary-card-sm.c-jemput .krr-summary-sm-icon {
        background: #e0f2fe;
        color: #0284c7;
    }

    .krr-summary-sm-num {
        font-size: 20px;
        font-weight: 800;
        color: #033B59;
        line-height: 1.1;
    }

    .krr-summary-sm-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 4px;
    }

    /* ── Search & Filter Input ── */
    .krr-search-form {
        margin-bottom: 14px;
    }

    .krr-search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .krr-search-input {
        width: 100%;
        padding: 10px 40px 10px 42px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #0f172a;
        outline: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }

    .krr-search-input:focus {
        border-color: #033B59;
        box-shadow: 0 0 0 3px rgba(3, 59, 89, 0.12);
    }

    .krr-search-icon {
        position: absolute;
        left: 14px;
        font-size: 15px;
        color: #64748b;
    }

    .krr-clear-search {
        position: absolute;
        right: 14px;
        font-size: 15px;
        color: #94a3b8;
        text-decoration: none;
        cursor: pointer;
    }

    .krr-clear-search:hover {
        color: #475569;
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
        font-size: 11.5px;
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

    /* ── History Task Card ── */
    .krr-history-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 16px;
        margin-bottom: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }

    .krr-history-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
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

    /* Middle info with status container */
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
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .krr-detail-row i {
        font-size: 15px;
        color: #033B59;
        flex-shrink: 0;
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
        box-shadow: 0 2px 6px rgba(37, 211, 102, 0.25);
        transition: all 0.2s ease;
    }

    .btn-wa-sm:hover {
        background: #1eb956;
        color: #ffffff;
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

    /* Status & Lunas Row */
    .krr-status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 14px;
        flex-wrap: wrap;
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

    .krr-meta-lunas-tag {
        font-size: 12px;
        font-weight: 700;
        color: #033B59;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 8px;
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

    /* Empty State */
    .krr-empty-box {
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        padding: 40px 20px;
        text-align: center;
        color: #64748b;
        margin-top: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .krr-empty-icon {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #033B59;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 10px;
    }

    .krr-empty-title {
        font-size: 16px;
        font-weight: 700;
        color: #033B59;
        margin-bottom: 4px;
    }

    .krr-empty-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        max-width: 320px;
        margin: 0 auto;
    }
</style>

<!-- Header Riwayat Kurir -->
<div class="riwayat-header">
    <a href="<?= base_url('kurir/dashboard') ?>" class="btn-back-krr" title="Kembali ke Dashboard"><i
            class="bi bi-arrow-left"></i></a>
    <h1 class="riwayat-title">Riwayat Tugas</h1>
</div>

<!-- 3 Kartu Metrik Ringkasan -->
<div class="krr-summary-row-3">
    <!-- Total Selesai -->
    <div class="krr-summary-card-sm c-total">
        <div class="krr-summary-sm-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="krr-summary-sm-num"><?= (int) ($ringkasan['total'] ?? 0) ?></div>
        <div class="krr-summary-sm-label">Total Selesai</div>
    </div>

    <!-- Pengantaran Selesai -->
    <div class="krr-summary-card-sm c-antar">
        <div class="krr-summary-sm-icon"><i class="bi bi-truck"></i></div>
        <div class="krr-summary-sm-num"><?= (int) ($ringkasan['pengantaran'] ?? 0) ?></div>
        <div class="krr-summary-sm-label">Pengantaran</div>
    </div>

    <!-- Penjemputan Selesai -->
    <div class="krr-summary-card-sm c-jemput">
        <div class="krr-summary-sm-icon"><i class="bi bi-box-seam-fill"></i></div>
        <div class="krr-summary-sm-num"><?= (int) ($ringkasan['penjemputan'] ?? 0) ?></div>
        <div class="krr-summary-sm-label">Penjemputan</div>
    </div>
</div>

<!-- Form Pencarian Riwayat -->
<form method="get" action="<?= base_url('kurir/riwayat') ?>" class="krr-search-form">
    <input type="hidden" name="tab" value="<?= $e($tab) ?>">
    <div class="krr-search-box">
        <i class="bi bi-search krr-search-icon"></i>
        <input type="text" name="q" value="<?= $e($q) ?>" class="krr-search-input"
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
        SEMUA (<?= (int) ($ringkasan['total'] ?? 0) ?>)
    </a>
    <a href="<?= base_url('kurir/riwayat?tab=pengantaran' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
        class="krr-tab-item <?= $tab === 'pengantaran' ? 'active' : '' ?>">
        PENGANTARAN (<?= (int) ($ringkasan['pengantaran'] ?? 0) ?>)
    </a>
    <a href="<?= base_url('kurir/riwayat?tab=penjemputan' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
        class="krr-tab-item <?= $tab === 'penjemputan' ? 'active' : '' ?>">
        PENJEMPUTAN (<?= (int) ($ringkasan['penjemputan'] ?? 0) ?>)
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
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) $r['no_hp']);
        if ($cleanPhone !== '' && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        ?>
        <div class="krr-history-card">
            <!-- Top Row: Badge Tipe & Kode -->
            <div class="krr-card-top">
                <span class="krr-badge-tipe <?= $isJemput ? 'tipe-jemput' : 'tipe-antar' ?>">
                    <?php if ($isJemput): ?>
                        <i class="bi bi-box-seam-fill"></i> PENJEMPUTAN SELESAI
                    <?php else: ?>
                        <i class="bi bi-truck"></i> PENGANTARAN SELESAI
                    <?php endif; ?>
                </span>
                <span class="krr-task-code"><?= $e((string) $r['kode']) ?></span>
            </div>

            <!-- Middle: Info Details & Icon Box -->
            <div class="krr-card-middle">
                <div class="krr-details-col">
                    <div class="krr-detail-row bold-name">
                        <i class="bi bi-person-fill"></i>
                        <span><?= $e((string) $r['nama']) ?></span>
                        <?php if ($cleanPhone !== ''): ?>
                            <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="btn-wa-sm" title="Chat WhatsApp">
                                <i class="bi bi-whatsapp"></i> Chat WA
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="krr-detail-row">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Selesai: <strong><?= $e((string) $r['waktu']) ?></strong></span>
                    </div>
                    <div class="krr-detail-row">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                        <span><?= $e((string) $r['alamat']) ?></span>
                    </div>
                    <div class="krr-detail-row">
                        <i class="bi bi-basket2-fill"></i>
                        <span><?= $e((string) $r['layanan']) ?> (<?= (float) $r['berat'] ?> KG)</span>
                    </div>
                </div>
                <!-- Foto Profil Pelanggan -->
                <?php
                $urlFotoPlg = url_foto_profil($r['foto'] ?? null);
                $inisialPlg = strtoupper(substr((string) $r['nama'], 0, 1));
                ?>
                <div class="krr-cust-avatar-col" title="Foto Pelanggan: <?= $e((string) $r['nama']) ?>">
                    <?php if ($urlFotoPlg): ?>
                        <img src="<?= $e($urlFotoPlg) ?>" alt="<?= $e((string) $r['nama']) ?>">
                    <?php else: ?>
                        <span class="krr-cust-initial"><?= $e($inisialPlg ?: 'P') ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status Row & Meta Lunas -->
            <div class="krr-status-row">
                <div class="krr-status-pill green">
                    <i class="bi bi-check2-circle"></i>
                    <span><?= $e((string) $r['status_label']) ?></span>
                </div>
                <div class="krr-meta-lunas-tag">
                    <i class="bi bi-check-circle-fill text-success me-1"></i>Rp
                    <?= number_format((float) $r['total'], 0, ',', '.') ?> (Lunas)
                </div>
            </div>

            <!-- Tombol Detail -->
            <a href="<?= base_url('kurir/tugas/detail/' . (int) $r['id_pesanan']) ?>" class="btn-lihat-detail">
                <span>Lihat Rincian Tugas</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>