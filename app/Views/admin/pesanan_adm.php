<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$tab = $tab ?? 'semua';
$q   = $q ?? '';
?>

<style>
/* ── Halaman Mengupdate Status Laundry (Sisi Admin) ── */
.page-title-box {
    margin-bottom: 16px;
}
.btn-back-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    color: #1e293b;
    font-size: 20px;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-back-link:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.page-heading {
    font-size: 19px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
    margin: 0;
}
.page-subheading {
    font-size: 12px;
    color: #64748b;
    margin: 0;
}

/* Tab filter pill */
.filter-tabs {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 10px;
    margin-bottom: 12px;
    scrollbar-width: none;
}
.filter-tabs::-webkit-scrollbar { display: none; }
.filter-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    color: #334155;
    background: #fff;
    border: 1px solid #cbd5e1;
    white-space: nowrap;
    transition: all 0.2s;
}
.filter-tab-btn.active {
    background: #0f172a;
    color: #fff;
    border-color: #0f172a;
}
.filter-tab-btn .badge-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 18px;
    height: 18px;
    padding: 0 5px;
    font-size: 11px;
    font-weight: 700;
    border-radius: 999px;
    color: #fff;
}
.badge-blue { background: #2563eb; }
.badge-red  { background: #dc2626; }
.badge-gray { background: #64748b; }

/* Search & Filter Bar */
.search-filter-row {
    display: flex;
    gap: 8px;
    margin-bottom: 18px;
}
.search-input-wrap {
    position: relative;
    flex: 1;
}
.search-input-wrap .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 15px;
    pointer-events: none;
}
.search-input-box {
    width: 100%;
    padding: 9px 12px 9px 36px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    background: #fff;
    font-size: 13px;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.search-input-box:focus {
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
}
.btn-filter-funnel {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    background: #fff;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-filter-funnel:hover {
    background: #f1f5f9;
}

/* Kartu Pesanan */
.order-card {
    background: #fff;
    border-radius: 16px;
    padding: 18px 16px;
    margin-bottom: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 14px rgba(0,0,0,0.04);
}
.order-id-label {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 12px;
}
.customer-section {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}
.customer-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    color: #1e293b;
    overflow: hidden;
    flex-shrink: 0;
}
.customer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.customer-info .customer-name {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
}
.customer-info .customer-desc {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}
.current-status-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
}
.current-status-title span {
    font-weight: 800;
    color: #033B59;
}

/* Stepper 5 Tahap */
.stepper-container {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    position: relative;
    margin: 16px 0 20px;
    padding: 0 4px;
}
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
    position: relative;
    z-index: 2;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    margin-bottom: 6px;
    transition: all 0.25s ease;
}
.step-circle.active {
    background: #22c55e;
    color: #fff;
    box-shadow: 0 3px 8px rgba(34, 197, 94, 0.35);
}
.step-circle.inactive {
    background: #e2e8f0;
    color: #64748b;
}
.step-label {
    font-size: 10px;
    font-weight: 600;
    line-height: 1.2;
    color: #475569;
    max-width: 62px;
}
.step-label.active {
    color: #15803d;
    font-weight: 700;
}
.stepper-line {
    position: absolute;
    top: 20px;
    height: 2px;
    width: 100%;
    background: #e2e8f0;
    z-index: 1;
}

/* Form Ubah Status */
.form-ubah-wrap {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px dashed #e2e8f0;
}
.form-ubah-label {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
    display: block;
}
.status-dropdown {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    background-color: #f8fafc;
    font-size: 13px;
    font-weight: 500;
    color: #1e293b;
    outline: none;
    margin-bottom: 12px;
    transition: border-color 0.2s;
}
.status-dropdown:focus {
    border-color: #0284c7;
    background-color: #fff;
}
.btn-simpan-status {
    width: 100%;
    padding: 11px 16px;
    background: #019D13;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(1, 157, 19, 0.25);
    transition: background 0.2s, transform 0.1s;
}
.btn-simpan-status:hover {
    background: #018310;
}
.btn-simpan-status:active {
    transform: scale(0.99);
}
</style>

<div class="page-title-box d-flex align-items-center gap-2">
    <a href="<?= base_url('admin/dashboard') ?>" class="btn-back-link" title="Kembali ke Dashboard">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="page-heading">Mengupdate Status Laundry</h1>
        <p class="page-subheading">Ubah status pesanan</p>
    </div>
</div>

<!-- Tab Filter -->
<div class="filter-tabs">
    <a href="<?= base_url('admin/pesanan?tab=semua' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
       class="filter-tab-btn <?= $tab === 'semua' ? 'active' : '' ?>">
        Semua
    </a>
    <a href="<?= base_url('admin/pesanan?tab=diproses' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
       class="filter-tab-btn <?= $tab === 'diproses' ? 'active' : '' ?>">
        Diproses
        <span class="badge-count badge-blue"><?= (int)($counts['diproses'] ?? 0) ?></span>
    </a>
    <a href="<?= base_url('admin/pesanan?tab=diantar' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
       class="filter-tab-btn <?= $tab === 'diantar' ? 'active' : '' ?>">
        Diantar
        <span class="badge-count badge-red"><?= (int)($counts['diantar'] ?? 0) ?></span>
    </a>
    <a href="<?= base_url('admin/pesanan?tab=selesai' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
       class="filter-tab-btn <?= $tab === 'selesai' ? 'active' : '' ?>">
        Selesai
        <span class="badge-count badge-gray"><?= (int)($counts['selesai'] ?? 0) ?></span>
    </a>
</div>

<!-- Search & Filter Form -->
<form method="get" action="<?= base_url('admin/pesanan') ?>" class="search-filter-row">
    <input type="hidden" name="tab" value="<?= $e($tab) ?>">
    <div class="search-input-wrap">
        <i class="bi bi-search search-icon"></i>
        <input type="text" name="q" value="<?= $e($q) ?>" class="search-input-box" placeholder="Cari nama / no pesanan...">
    </div>
    <button type="submit" class="btn-filter-funnel">
        <i class="bi bi-funnel"></i> Filter
    </button>
</form>

<!-- Daftar Pesanan -->
<?php if (empty($pesanan)): ?>
    <div class="order-card text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
        <p class="mb-0 fw-semibold">Tidak ada pesanan pada filter ini.</p>
    </div>
<?php else: ?>
    <?php foreach ($pesanan as $p): ?>
        <?php
        $idPesanan = (int) $p['id_pesanan'];
        $tglFormat = ! empty($p['tanggal']) ? date('dmY', strtotime((string)$p['tanggal'])) : date('dmY');
        $kodePesanan = 'PSN-' . $tglFormat . '-' . str_pad((string)$idPesanan, 3, '0', STR_PAD_LEFT);

        $st = (string) ($p['status_pesanan'] ?? 'menunggu');

        // Pemetaan label status terkini
        $statusLabelMap = [
            'menunggu'     => 'Menunggu Kurir',
            'dikonfirmasi' => 'Menunggu Kurir',
            'dijemput'     => 'Cucian Dijemput',
            'dicuci'       => 'Proses Cuci',
            'disetrika'    => 'Proses Setrika',
            'diantar'      => 'Sedang Diantar',
            'selesai'      => 'Selesai',
            'dibatalkan'   => 'Dibatalkan',
        ];
        $statusLabel = $statusLabelMap[$st] ?? ucfirst($st);

        // Menentukan level aktif pada 5 stepper (1=Menunggu Kurir, 2=Cuci, 3=Setrika, 4=Diantar, 5=Selesai)
        $levelStepper = match ($st) {
            'menunggu', 'dikonfirmasi' => 1,
            'dijemput'                  => 1.5,
            'dicuci'                    => 2,
            'disetrika'                 => 3,
            'diantar'                   => 4,
            'selesai'                   => 5,
            default                     => 1,
        };

        // Inisial atau avatar pelanggan
        $namaPlg = (string) ($p['nama_pelanggan'] ?? 'Pelanggan');
        $inisialPlg = $namaPlg !== '' ? strtoupper(substr($namaPlg, 0, 1)) : 'P';
        $fotoPlg = ! empty($p['foto_pelanggan']) ? base_url('uploads/foto/' . $p['foto_pelanggan']) : null;
        ?>

        <div class="order-card">
            <div class="order-id-label"><?= $e($kodePesanan) ?></div>

            <div class="customer-section">
                <div class="customer-avatar">
                    <?php if ($fotoPlg): ?>
                        <img src="<?= $e($fotoPlg) ?>" alt="<?= $e($namaPlg) ?>">
                    <?php else: ?>
                        <?= $e($inisialPlg) ?>
                    <?php endif; ?>
                </div>
                <div class="customer-info">
                    <div class="customer-name"><?= $e($namaPlg) ?></div>
                    <div class="customer-desc">
                        <?= $e((string)($p['nama_layanan'] ?? 'Laundry Reguler')) ?>
                        · <?= (float)($p['berat'] ?? 1.0) ?> KG
                    </div>
                </div>
            </div>

            <div class="current-status-title">
                Status Saat Ini: <span><?= $e($statusLabel) ?></span>
            </div>

            <!-- Stepper 5 Langkah -->
            <div class="stepper-container">
                <div class="stepper-line"></div>

                <!-- 1. Menunggu Kurir -->
                <div class="step-item">
                    <div class="step-circle <?= $levelStepper >= 1 ? 'active' : 'inactive' ?>" title="Menunggu Kurir">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <span class="step-label <?= $levelStepper >= 1 ? 'active' : '' ?>">Menunggu kurir</span>
                </div>

                <!-- 2. Proses Cuci -->
                <div class="step-item">
                    <div class="step-circle <?= $levelStepper >= 2 ? 'active' : 'inactive' ?>" title="Proses Cuci">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="3"></rect>
                            <circle cx="12" cy="13" r="5"></circle>
                            <path d="M12 10a3 3 0 0 0-3 3"></path>
                            <circle cx="6" cy="6" r="1"></circle>
                        </svg>
                    </div>
                    <span class="step-label <?= $levelStepper >= 2 ? 'active' : '' ?>">Proses cuci</span>
                </div>

                <!-- 3. Proses Setrika -->
                <div class="step-item">
                    <div class="step-circle <?= $levelStepper >= 3 ? 'active' : 'inactive' ?>" title="Proses Setrika">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 18h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H9L3 15v1a2 2 0 0 0 1 2z"></path>
                            <path d="M9 7V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v3"></path>
                        </svg>
                    </div>
                    <span class="step-label <?= $levelStepper >= 3 ? 'active' : '' ?>">Proses setrika</span>
                </div>

                <!-- 4. Sedang Diantar -->
                <div class="step-item">
                    <div class="step-circle <?= $levelStepper >= 4 ? 'active' : 'inactive' ?>" title="Sedang Diantar">
                        <i class="bi bi-bicycle"></i>
                    </div>
                    <span class="step-label <?= $levelStepper >= 4 ? 'active' : '' ?>">Sedang diantar</span>
                </div>

                <!-- 5. Selesai -->
                <div class="step-item">
                    <div class="step-circle <?= $levelStepper >= 5 ? 'active' : 'inactive' ?>" title="Selesai">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="step-label <?= $levelStepper >= 5 ? 'active' : '' ?>">Selesai</span>
                </div>
            </div>

            <!-- Form Ubah Status -->
            <form action="<?= base_url('admin/pesanan/update-status/' . $idPesanan) ?>" method="post" class="form-ubah-wrap">
                <?= csrf_field() ?>
                <label class="form-ubah-label">Ubah Status</label>
                <select name="status_pesanan" class="status-dropdown" required>
                    <option value="" disabled>Pilih Status Berikutnya</option>
                    <option value="dikonfirmasi" <?= in_array($st, ['menunggu', 'dikonfirmasi'], true) ? 'selected' : '' ?>>Menunggu Kurir</option>
                    <option value="dijemput" <?= $st === 'dijemput' ? 'selected' : '' ?>>Cucian Dijemput</option>
                    <option value="dicuci" <?= $st === 'dicuci' ? 'selected' : '' ?>>Proses Cuci</option>
                    <option value="disetrika" <?= $st === 'disetrika' ? 'selected' : '' ?>>Proses Setrika</option>
                    <option value="diantar" <?= $st === 'diantar' ? 'selected' : '' ?>>Sedang Diantar</option>
                    <option value="selesai" <?= $st === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="dibatalkan" <?= $st === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
                <button type="submit" class="btn-simpan-status">Simpan Perubahan</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
