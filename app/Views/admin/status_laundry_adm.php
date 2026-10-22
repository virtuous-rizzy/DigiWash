<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn(string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$tab = $tab ?? 'semua';
$q = $q ?? '';
$kurirList = $kurirList ?? [];

$bulanIndo = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember',
];
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

    .filter-tabs::-webkit-scrollbar {
        display: none;
    }

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

    .badge-blue {
        background: #2563eb;
    }

    .badge-red {
        background: #dc2626;
    }

    .badge-gray {
        background: #64748b;
    }

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
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
    }

    .order-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .order-id-label {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0;
    }

    .btn-preview-tracking {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 700;
        color: #0284c7;
        background: #e0f2fe;
        border: 1px solid #bae6fd;
        padding: 4px 10px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-preview-tracking:hover {
        background: #0284c7;
        color: #fff;
    }

    .kurir-badge-row {
        margin-bottom: 12px;
    }

    .assigned-kurir-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 8px;
    }

    .unassigned-kurir-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fefce8;
        border: 1px solid #fef08a;
        color: #854d0e;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 8px;
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

    .btn-detail-order {
        width: 100%;
        padding: 10px 16px;
        margin-top: 8px;
        background: #fff;
        color: #033B59;
        border: 1.5px solid #033B59;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-detail-order:hover {
        background: #033B59;
        color: #fff;
    }

    .btn-detail-order:active {
        transform: scale(0.99);
    }

    /* Warna teks Alert Status */
    .alert-status-batal,
    .alert-status-tolak {
        background-color: #FEF2F2 !important;
        border: 1px solid #FECACA !important;
        color: #B91C1C !important;
    }

    .alert-status-batal strong,
    .alert-status-tolak strong {
        color: #991B1B !important;
    }

    .alert-status-batal span,
    .alert-status-tolak span {
        color: #B91C1C !important;
    }

    .alert-status-batal i,
    .alert-status-tolak i {
        color: #DC2626 !important;
    }

    .alert-status-selesai {
        background-color: #F0FDF4 !important;
        border: 1px solid #BBF7D0 !important;
        color: #166534 !important;
    }

    .alert-status-selesai strong {
        color: #14532D !important;
    }

    .alert-status-selesai span {
        color: #166534 !important;
    }

    .alert-status-selesai i {
        color: #16A34A !important;
    }

    .alert-status-menunggu {
        background-color: #FFFBEB !important;
        border: 1px solid #FDE68A !important;
        color: #B45309 !important;
    }

    .alert-status-menunggu strong {
        color: #92400E !important;
    }

    .alert-status-menunggu span {
        color: #B45309 !important;
    }

    .alert-status-menunggu a {
        color: #92400E !important;
    }

    .alert-status-menunggu i {
        color: #D97706 !important;
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
    <a href="<?= base_url('admin/status-laundry?tab=semua' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
        class="filter-tab-btn <?= $tab === 'semua' ? 'active' : '' ?>">
        Semua
    </a>
    <a href="<?= base_url('admin/status-laundry?tab=diproses' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
        class="filter-tab-btn <?= $tab === 'diproses' ? 'active' : '' ?>">
        Diproses
        <span class="badge-count badge-blue"><?= (int) ($counts['diproses'] ?? 0) ?></span>
    </a>
    <a href="<?= base_url('admin/status-laundry?tab=diantar' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
        class="filter-tab-btn <?= $tab === 'diantar' ? 'active' : '' ?>">
        Diantar
        <span class="badge-count badge-red"><?= (int) ($counts['diantar'] ?? 0) ?></span>
    </a>
    <a href="<?= base_url('admin/status-laundry?tab=selesai' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
        class="filter-tab-btn <?= $tab === 'selesai' ? 'active' : '' ?>">
        Selesai
        <span class="badge-count badge-gray"><?= (int) ($counts['selesai'] ?? 0) ?></span>
    </a>
    <a href="<?= base_url('admin/status-laundry?tab=dibatalkan' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
        class="filter-tab-btn <?= $tab === 'dibatalkan' ? 'active' : '' ?>">
        Dibatalkan User
        <span class="badge-count badge-red"><?= (int) ($counts['dibatalkan'] ?? 0) ?></span>
    </a>
    <a href="<?= base_url('admin/status-laundry?tab=ditolak' . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"
        class="filter-tab-btn <?= $tab === 'ditolak' ? 'active' : '' ?>">
        Ditolak Admin
        <span class="badge-count badge-red"><?= (int) ($counts['ditolak'] ?? 0) ?></span>
    </a>
</div>

<!-- Search & Filter Form -->
<form method="get" action="<?= base_url('admin/status-laundry') ?>" class="search-filter-row">
    <input type="hidden" name="tab" value="<?= $e($tab) ?>">
    <div class="search-input-wrap">
        <i class="bi bi-search search-icon"></i>
        <input type="text" name="q" value="<?= $e($q) ?>" class="search-input-box"
            placeholder="Cari nama / no pesanan...">
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
        $tglFormat = !empty($p['tanggal']) ? date('dmY', strtotime((string) $p['tanggal'])) : date('dmY');
        $kodePesanan = 'PSN-' . $tglFormat . '-' . str_pad((string) $idPesanan, 3, '0', STR_PAD_LEFT);

        $st = (string) ($p['status_pesanan'] ?? 'menunggu');
        $statusBayar = (string) ($p['status_bayar'] ?? 'belum_dibayar');
        $catatanPsn = (string) ($p['catatan'] ?? '');
        $catatanLower = strtolower($catatanPsn);
        $idAdminPsn = $p['id_admin'] ?? null;
        $idAdminBayar = $p['id_admin_bayar'] ?? null;

        // Bedakan dibatalkan pelanggan vs ditolak admin
        $isBatalPelanggan = str_contains($catatanLower, 'dibatalkan oleh pelanggan')
            || str_contains($catatanLower, 'dibatalkan pelanggan')
            || (str_contains($catatanLower, 'dibatalkan') && !str_contains($catatanLower, 'ditolak') && !str_contains($catatanLower, 'oleh admin'))
            || ($st === 'dibatalkan' && empty($idAdminPsn) && empty($idAdminBayar) && !str_contains($catatanLower, 'ditolak'));

        $isDitolakAdmin = str_contains($catatanLower, 'ditolak')
            || str_contains($catatanLower, 'oleh admin')
            || ($statusBayar === 'gagal' && (!empty($idAdminPsn) || !empty($idAdminBayar)) && !$isBatalPelanggan);

        if ($statusBayar === 'gagal' && !$isBatalPelanggan && !$isDitolakAdmin) {
            if ($st === 'dibatalkan' && empty($idAdminPsn)) {
                $isBatalPelanggan = true;
            } else {
                $isDitolakAdmin = true;
            }
        }

        $isBatal = ($st === 'dibatalkan');
        $isBatalAtauTolak = ($isBatalPelanggan || $isDitolakAdmin || $isBatal || $statusBayar === 'gagal');

        // Pemetaan label status terkini
        $statusLabelMap = [
            'menunggu' => 'Menunggu Kurir Menjemput',
            'dikonfirmasi' => 'Menunggu Kurir Menjemput',
            'dijemput' => 'Cucian Tiba di Outlet (Dijemput Kurir)',
            'dicuci' => 'Proses Cuci',
            'disetrika' => 'Proses Setrika (Siap Diantar)',
            'diantar' => 'Sedang Diantar Kurir ke Pelanggan',
            'selesai' => 'Pesanan Selesai',
            'dibatalkan' => 'Pesanan Dibatalkan',
        ];

        if ($isBatalPelanggan) {
            $statusLabel = 'Dibatalkan Pelanggan';
        } elseif ($isDitolakAdmin) {
            $statusLabel = 'Pembayaran Ditolak Admin';
        } elseif ($isBatal) {
            $statusLabel = 'Pesanan Dibatalkan';
        } else {
            $statusLabel = $statusLabelMap[$st] ?? ucfirst($st);
        }

        // Menentukan level aktif pada 5 stepper (1=Menunggu Kurir, 2=Cuci, 3=Setrika, 4=Diantar, 5=Selesai)
        if ($isBatalAtauTolak) {
            $levelStepper = 0;
        } else {
            $levelStepper = match ($st) {
                'menunggu', 'dikonfirmasi' => 1,
                'dijemput' => 1.5,
                'dicuci' => 2,
                'disetrika' => 3,
                'diantar' => 4,
                'selesai' => 5,
                default => 0,
            };
        }

        // Inisial atau avatar pelanggan
        $namaPlg = (string) ($p['nama_pelanggan'] ?? 'Pelanggan');
        $inisialPlg = $namaPlg !== '' ? strtoupper(substr($namaPlg, 0, 1)) : 'P';
        $fotoPlg = url_foto_profil($p['foto_pelanggan'] ?? null);

        // Format Tanggal & Jam Indonesia (Contoh: 03 Juni 2026 • 08.00)
        $tglRaw = $p['tanggal'] ?? $p['created_at'] ?? 'now';
        $tglObj = strtotime((string) $tglRaw);
        $tglTeks = date('d F Y', $tglObj);
        $tglIndo = strtr($tglTeks, $bulanIndo);
        $jamIndo = date('H.i', $tglObj);

        $beratVal = (float) ($p['berat'] ?? 1.0);
        $totalHarga = (float) ($p['total_bayar'] ?? 0);
        if ($totalHarga <= 0) {
            $flat = (float) ($p['harga_flat'] ?? 0);
            $perKg = (float) ($p['harga_per_kg'] ?? 0);
            $totalHarga = $flat > 0 ? $flat : ($perKg * $beratVal);
        }

        // Siapkan data JSON untuk modal detail
        $dataJson = htmlspecialchars(json_encode([
            'id_pesanan' => $idPesanan,
            'kode_pesanan' => $kodePesanan,
            'nama_pelanggan' => $namaPlg,
            'no_hp_pelanggan' => (string) ($p['no_hp_pelanggan'] ?? '-'),
            'nama_layanan' => (string) ($p['nama_layanan'] ?? 'Laundry Reguler'),
            'berat' => $beratVal,
            'total_harga' => number_format($totalHarga, 0, ',', '.'),
            'tanggal_indo' => $tglIndo . ' • ' . $jamIndo,
            'status_pesanan' => $st,
            'status_bayar' => $statusBayar,
            'metode_bayar' => (string) ($p['metode'] ?? 'Transfer Bank'),
            'alamat_ambil' => (string) ($p['alamat_pengambilan'] ?? '-'),
            'alamat_antar' => (string) ($p['alamat_pengantaran'] ?? '-'),
            'catatan' => $catatanPsn,
            'id_kurir' => (int) ($p['id_kurir'] ?? 0),
            'nama_kurir' => (string) ($p['nama_kurir'] ?? 'Belum Ditugaskan'),
            'is_batal_pelanggan' => $isBatalPelanggan,
            'is_ditolak_admin' => $isDitolakAdmin,
        ]), ENT_QUOTES, 'UTF-8');
        ?>

        <div class="order-card">
            <div class="order-header-row">
                <div class="order-id-label">
                    <?= $e($kodePesanan) ?>
                </div>

            </div>

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
                        <?= $e((string) ($p['nama_layanan'] ?? 'Laundry Reguler')) ?> · <?= (float) ($p['berat'] ?? 1.0) ?> KG
                        <?php if (!empty($p['alamat_pengambilan'])): ?>
                            <br><i class="bi bi-geo-alt"></i> <?= $e((string) $p['alamat_pengambilan']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($catatanPsn) && $isBatalAtauTolak): ?>
                <div class="alert alert-danger py-1 px-3 fw-medium small rounded-2 mb-3 d-flex align-items-center gap-2"
                    style="font-size: 12px; background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B;">
                    <i class="bi bi-info-circle-fill"></i>
                    <div><?= $e($catatanPsn) ?></div>
                </div>
            <?php endif; ?>

            <!-- Status Penugasan Kurir: Dikosongkan jika dibatalkan atau ditolak -->
            <?php if (!$isBatalAtauTolak): ?>
                <div class="kurir-badge-row">
                    <?php if (!empty($p['nama_kurir'])): ?>
                        <div class="assigned-kurir-badge">
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Kurir: <strong><?= $e($p['nama_kurir']) ?></strong>
                                <?= !empty($p['no_hp_kurir']) ? '(' . $e($p['no_hp_kurir']) . ')' : '' ?></span>
                        </div>
                    <?php else: ?>
                        <div class="unassigned-kurir-badge">
                            <i class="bi bi-clock-history"></i>
                            <span>Belum ada kurir ditugaskan</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="current-status-title">
                Status Saat Ini:
                <?php if ($isBatalPelanggan): ?>
                    <span style="color: #dc2626;"><i class="bi bi-slash-circle me-1"></i>Dibatalkan Pelanggan</span>
                <?php elseif ($isDitolakAdmin): ?>
                    <span style="color: #dc2626;"><i class="bi bi-x-circle me-1"></i>Pembayaran Ditolak Admin</span>
                <?php elseif ($isBatal): ?>
                    <span style="color: #dc2626;"><i class="bi bi-x-circle me-1"></i>Dibatalkan</span>
                <?php elseif ($st === 'selesai'): ?>
                    <span style="color: #16a34a;"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span>
                <?php elseif ($statusBayar !== 'lunas'): ?>
                    <span style="color: #d97706;"><i class="bi bi-clock-history me-1"></i>Menunggu Pembayaran</span>
                <?php else: ?>
                    <span><?= $e($statusLabel) ?></span>
                <?php endif; ?>
            </div>

            <!-- Stepper 5 Langkah -->
            <div class="stepper-container">
                <div class="stepper-line"></div>

                <!-- 1. Menunggu Kurir / Cucian Dijemput -->
                <div class="step-item">
                    <div class="step-circle <?= $levelStepper >= 1 ? 'active' : 'inactive' ?>" title="<?= in_array($st, ['menunggu', 'dikonfirmasi'], true) ? 'Menunggu Kurir' : 'Cucian Tiba di Outlet' ?>">
                        <?php if ($st === 'dijemput' || $levelStepper >= 2): ?>
                            <i class="bi bi-check-lg"></i>
                        <?php else: ?>
                            <i class="bi bi-box-seam-fill"></i>
                        <?php endif; ?>
                    </div>
                    <span class="step-label <?= $levelStepper >= 1 ? 'active' : '' ?>">
                        <?= in_array($st, ['menunggu', 'dikonfirmasi'], true) ? 'Menunggu kurir' : 'Cucian dijemput' ?>
                    </span>
                </div>

                <!-- 2. Proses Cuci -->
                <div class="step-item">
                    <div class="step-circle <?= $levelStepper >= 2 ? 'active' : 'inactive' ?>" title="Proses Cuci">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
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
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
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

            <!-- Form Ubah Status & Tugaskan Kurir -->
            <?php if ($isBatalPelanggan): ?>
                <div class="alert alert-danger alert-status-batal py-2 px-3 fw-bold small rounded-3 mb-0 d-flex align-items-center gap-2 mt-3"
                    style="background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C;">
                    <i class="bi bi-slash-circle-fill fs-5" style="color: #DC2626;"></i>
                    <div style="color: #B91C1C;">
                        <strong style="color: #991B1B;">Pesanan Dibatalkan oleh Pelanggan</strong><br>
                        <span class="fw-normal" style="color: #B91C1C;">Pesanan ini telah dibatalkan oleh pelanggan. Status telah
                            terkunci dan proses laundry dihentikan.</span>
                    </div>
                </div>
                <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                    <i class="bi bi-info-circle me-1"></i> Detail
                </button>
            <?php elseif ($isDitolakAdmin): ?>
                <div class="alert alert-danger alert-status-tolak py-2 px-3 fw-bold small rounded-3 mb-0 d-flex align-items-center gap-2 mt-3"
                    style="background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C;">
                    <i class="bi bi-x-circle-fill fs-5" style="color: #DC2626;"></i>
                    <div style="color: #B91C1C;">
                        <strong style="color: #991B1B;">Pembayaran Ditolak oleh Admin</strong><br>
                        <span class="fw-normal" style="color: #B91C1C;">Transaksi pembayaran untuk pesanan ini telah ditolak oleh
                            Admin. Pesanan dibatalkan dan status laundry tidak dapat diproses lagi.</span>
                    </div>
                </div>
                <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                    <i class="bi bi-info-circle me-1"></i> Detail
                </button>
            <?php elseif ($isBatal): ?>
                <div class="alert alert-danger alert-status-batal py-2 px-3 fw-bold small rounded-3 mb-0 d-flex align-items-center gap-2 mt-3"
                    style="background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C;">
                    <i class="bi bi-x-circle-fill fs-5" style="color: #DC2626;"></i>
                    <div style="color: #B91C1C;">
                        <strong style="color: #991B1B;">Pesanan Dibatalkan</strong><br>
                        <span class="fw-normal" style="color: #B91C1C;">Pesanan ini telah dibatalkan. Status telah terkunci dan
                            tidak dapat diubah lagi.</span>
                    </div>
                </div>
                <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                    <i class="bi bi-info-circle me-1"></i> Detail
                </button>
            <?php elseif ($st === 'selesai'): ?>
                <div class="alert alert-success alert-status-selesai py-2 px-3 fw-bold small rounded-3 mb-0 d-flex align-items-center gap-2 mt-3"
                    style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534;">
                    <i class="bi bi-check-circle-fill fs-5" style="color: #16A34A;"></i>
                    <div style="color: #166534;">
                        <strong style="color: #14532D;">Pesanan Selesai</strong><br>
                        <span class="fw-normal" style="color: #166534;">Pesanan telah selesai dan pakaian telah diterima oleh
                            pelanggan. Status telah final.</span>
                    </div>
                </div>
                <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                    <i class="bi bi-info-circle me-1"></i> Detail
                </button>
            <?php elseif ($statusBayar !== 'lunas'): ?>
                <div class="alert alert-warning alert-status-menunggu py-2 px-3 fw-bold small rounded-3 mb-0 d-flex align-items-center gap-2 mt-3"
                    style="background: #FFFBEB; border: 1px solid #FDE68A; color: #B45309;">
                    <i class="bi bi-clock-history fs-5" style="color: #D97706;"></i>
                    <div style="color: #B45309;">
                        <strong style="color: #92400E;">Menunggu Konfirmasi Pembayaran</strong><br>
                        <span class="fw-normal" style="color: #B45309;">Pembayaran belum berstatus Lunas
                            (<?= $statusBayar === 'menunggu_verifikasi' ? 'menunggu verifikasi Admin' : 'belum dibayar' ?>). Silakan
                            <a href="<?= base_url('admin/transaksi') ?>" class="fw-bold text-decoration-underline"
                                style="color: #92400E;">verifikasi pembayaran di menu Transaksi</a>
                            terlebih dahulu sebelum memproses status laundry.</span>
                    </div>
                </div>
                <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                    <i class="bi bi-info-circle me-1"></i> Detail
                </button>
            <?php elseif ($st === 'diantar'): ?>
                <!-- Status Sedang Diantar: Hanya Kurir yang menyelesaikan pengantaran -->
                <div class="alert alert-primary py-2 px-3 fw-bold small rounded-3 mb-0 d-flex align-items-center gap-2 mt-3"
                    style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF;">
                    <i class="bi bi-truck fs-5" style="color: #2563EB;"></i>
                    <div style="color: #1E40AF;">
                        <strong style="color: #1E3A8A;">Sedang Diantar oleh Kurir</strong><br>
                        <span class="fw-normal" style="color: #1E40AF;">
                            Pakaian sedang dalam perjalanan diantar oleh kurir ke alamat pelanggan. Status <strong>Selesai</strong> hanya dapat dikonfirmasi oleh Kurir setelah pakaian berhasil diterima oleh pelanggan.
                        </span>
                    </div>
                </div>
                <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                    <i class="bi bi-info-circle me-1"></i> Detail
                </button>
            <?php elseif (in_array($st, ['menunggu', 'dikonfirmasi'], true)): ?>
                <!-- Status Menunggu Penjemputan Kurir: Admin belum bisa mengubah status proses cucian -->
                <div class="alert alert-info py-2 px-3 small rounded-3 mb-0 d-flex align-items-center gap-2 mt-3"
                    style="background: #F0F9FF; border: 1px solid #BAE6FD; color: #0369A1;">
                    <i class="bi bi-hourglass-split fs-5 text-primary flex-shrink-0"></i>
                    <div style="color: #0369A1; font-size: 12.5px;">
                        <strong style="color: #075985;">Menunggu Kurir Mengantar Cucian ke Outlet</strong><br>
                        <span>Cucian masih berada di tangan pelanggan. Admin baru dapat mengubah status proses cucian setelah kurir selesai menjemput dan mengantar cucian ke outlet laundry (status <strong>Dijemput</strong>).</span>
                    </div>
                </div>
                <form action="<?= base_url('admin/pesanan/update-status/' . $idPesanan) ?>" method="post" class="form-ubah-wrap mt-2">
                    <?= csrf_field() ?>
                    <div class="row g-2 mb-2">
                        <div class="col-12 col-sm-6">
                            <label class="form-ubah-label">Tugaskan Kurir Penjemput</label>
                            <select name="id_kurir" class="status-dropdown">
                                <option value="">-- Bebas Diambil Kurir --</option>
                                <?php foreach ($kurirList as $k): ?>
                                    <option value="<?= (int) $k['id_kurir'] ?>" <?= ((int) ($p['id_kurir'] ?? 0) === (int) $k['id_kurir']) ? 'selected' : '' ?>>
                                        <?= $e($k['nama']) ?> (<?= ucfirst($k['status']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-ubah-label">Status Laundry</label>
                            <select name="status_pesanan" class="status-dropdown" required>
                                <option value="dikonfirmasi" selected>Menunggu Penjemputan Kurir (Terkunci)</option>
                                <option value="dibatalkan">Batalkan Pesanan</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-simpan-status">Simpan Penugasan Kurir</button>
                    <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                        <i class="bi bi-info-circle me-1"></i> Detail
                    </button>
                </form>
            <?php else: ?>
                <!-- Kurir sudah selesai mengantar cucian ke outlet (status dijemput, dicuci, disetrika) -->
                <?php if ($st === 'dijemput'): ?>
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-0 d-flex align-items-center gap-2 mt-3"
                        style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534;">
                        <i class="bi bi-box2-fill fs-5 text-success flex-shrink-0"></i>
                        <div style="color: #166534; font-size: 12.5px;">
                            <strong style="color: #14532D;">Cucian Telah Tiba di Outlet</strong><br>
                            <span>Kurir telah selesai menjemput dan mengantar cucian ke outlet laundry. Anda dapat memulai proses pencucian sekarang.</span>
                        </div>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url('admin/pesanan/update-status/' . $idPesanan) ?>" method="post" class="form-ubah-wrap mt-2">
                    <?= csrf_field() ?>
                    <div class="row g-2 mb-2">
                        <div class="col-12 col-sm-6">
                            <label class="form-ubah-label">Kurir Penanggung Jawab</label>
                            <select name="id_kurir" class="status-dropdown">
                                <option value="">-- Bebas Diambil Kurir --</option>
                                <?php foreach ($kurirList as $k): ?>
                                    <option value="<?= (int) $k['id_kurir'] ?>" <?= ((int) ($p['id_kurir'] ?? 0) === (int) $k['id_kurir']) ? 'selected' : '' ?>>
                                        <?= $e($k['nama']) ?> (<?= ucfirst($k['status']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-ubah-label">Status Laundry</label>
                            <select name="status_pesanan" class="status-dropdown" required>
                                <?php if ($st === 'dijemput'): ?>
                                    <option value="dijemput" selected>Cucian Tiba di Outlet</option>
                                    <option value="dicuci">Mulai Proses Cuci</option>
                                <?php elseif ($st === 'dicuci'): ?>
                                    <option value="dicuci" selected>Sedang Proses Cuci</option>
                                    <option value="disetrika">Lanjut Proses Setrika (Siap Diantar)</option>
                                <?php elseif ($st === 'disetrika'): ?>
                                    <option value="disetrika" selected>Proses Setrika Selesai (Siap Diantar)</option>
                                    <option value="diantar">Serahkan ke Kurir (Sedang Diantar)</option>
                                <?php endif; ?>
                                <option value="dibatalkan">Batalkan Pesanan</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-simpan-status">Simpan Perubahan</button>
                    <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                        <i class="bi bi-info-circle me-1"></i> Detail
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- MODAL DETAIL PESANAN                                       -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalDetailPesanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <div class="modal-content"
            style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #033B59; color: #fff; padding: 14px 20px;">
                <h6 class="modal-title fw-bold" style="margin: 0; font-size: 15px;">
                    <i class="bi bi-info-circle me-2"></i>Rincian Pesanan
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 13px;">
                <!-- Ringkasan Pelanggan -->
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3"
                    style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div>
                        <div class="fw-bold fs-6 text-dark" id="detNamaPelanggan"></div>
                        <div class="text-muted small" id="detNoHpPelanggan"></div>
                    </div>
                    <a id="detBtnWa" href="#" target="_blank" class="btn btn-sm btn-success fw-bold rounded-pill px-3"
                        style="font-size: 12px;">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                    </a>
                </div>

                <!-- Informasi Pesanan -->
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Kode Pesanan</span>
                    <strong class="text-dark" id="detKodePesanan"></strong>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Waktu Transaksi</span>
                    <span class="text-dark fw-bold" id="detTanggal"></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Layanan &amp; Berat</span>
                    <span class="text-dark fw-bold" id="detLayananBerat"></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Total Pembayaran</span>
                    <span class="text-success fw-bold fs-6" id="detTotalHarga"></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom align-items-center">
                    <span class="text-muted">Status Pembayaran</span>
                    <span id="detStatusBayarBadge"></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom align-items-center">
                    <span class="text-muted">Status Laundry</span>
                    <span id="detStatusPesananBadge"></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Kurir Penanggung Jawab</span>
                    <span class="text-dark fw-bold" id="detNamaKurir"></span>
                </div>

                <!-- Alamat -->
                <div class="mt-3">
                    <div class="text-muted small fw-bold mb-1">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Alamat Pengambilan:
                    </div>
                    <div class="p-2 rounded-2 bg-light text-dark small" id="detAlamatAmbil"></div>
                </div>
                <div class="mt-2">
                    <div class="text-muted small fw-bold mb-1">
                        <i class="bi bi-truck text-primary me-1"></i> Alamat Pengantaran:
                    </div>
                    <div class="p-2 rounded-2 bg-light text-dark small" id="detAlamatAntar"></div>
                </div>

                <!-- Catatan -->
                <div class="mt-2" id="wrapDetCatatan" style="display: none;">
                    <div class="text-muted small fw-bold mb-1">
                        <i class="bi bi-chat-left-text me-1"></i> Catatan Khusus:
                    </div>
                    <div class="p-2 rounded-2 bg-warning-subtle text-dark small" id="detCatatan"></div>
                </div>
            </div>
            <div class="modal-footer"
                style="background: #f8f9fa; border-top: 1px solid #eee; display: flex; justify-content: flex-end; padding: 12px 20px;">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function bukaModalDetail(data) {
        document.getElementById('detNamaPelanggan').textContent = data.nama_pelanggan;
        document.getElementById('detNoHpPelanggan').textContent = data.no_hp_pelanggan;
        document.getElementById('detKodePesanan').textContent = data.kode_pesanan;
        document.getElementById('detTanggal').textContent = data.tanggal_indo;
        document.getElementById('detLayananBerat').textContent = data.nama_layanan + ' • ' + data.berat + ' Kg';
        document.getElementById('detTotalHarga').textContent = 'Rp ' + data.total_harga;
        document.getElementById('detAlamatAmbil').textContent = data.alamat_ambil;
        document.getElementById('detAlamatAntar').textContent = data.alamat_antar;
        // Kurir Penanggung Jawab
        var namaKurirEl = document.getElementById('detNamaKurir');
        if (data.is_batal_pelanggan || data.is_ditolak_admin || data.status_pesanan === 'dibatalkan' || data.status_bayar === 'gagal') {
            namaKurirEl.textContent = '-';
        } else {
            namaKurirEl.textContent = data.nama_kurir;
        }

        // Tombol WhatsApp
        var waPhone = (data.no_hp_pelanggan || '').replace(/[^0-9]/g, '');
        if (waPhone.startsWith('0')) waPhone = '62' + waPhone.substring(1);
        var btnWa = document.getElementById('detBtnWa');
        if (waPhone && waPhone.length > 5) {
            btnWa.href = 'https://wa.me/' + waPhone + '?text=Halo%20' + encodeURIComponent(data.nama_pelanggan) + ',%20mengenai%20pesanan%20' + encodeURIComponent(data.kode_pesanan) + '%20di%20DigiWash...';
            btnWa.style.display = 'inline-flex';
        } else {
            btnWa.style.display = 'none';
        }

        // Status Pembayaran Badge
        var stBayarEl = document.getElementById('detStatusBayarBadge');
        if (data.status_bayar === 'lunas') {
            stBayarEl.innerHTML = '<span class="badge bg-success rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i> Lunas</span>';
        } else if (data.is_batal_pelanggan) {
            stBayarEl.innerHTML = '<span class="badge bg-danger rounded-pill px-2 py-1"><i class="bi bi-slash-circle me-1"></i> Dibatalkan Pelanggan</span>';
        } else if (data.is_ditolak_admin || data.status_bayar === 'gagal') {
            stBayarEl.innerHTML = '<span class="badge bg-danger rounded-pill px-2 py-1"><i class="bi bi-x-circle me-1"></i> Ditolak Admin</span>';
        } else if (data.status_bayar === 'menunggu_verifikasi') {
            stBayarEl.innerHTML = '<span class="badge bg-warning text-dark rounded-pill px-2 py-1"><i class="bi bi-clock-history me-1"></i> Menunggu Verifikasi</span>';
        } else {
            stBayarEl.innerHTML = '<span class="badge bg-secondary rounded-pill px-2 py-1"><i class="bi bi-hourglass-split me-1"></i> Belum Dibayar</span>';
        }

        // Status Pesanan Badge
        var stPsnEl = document.getElementById('detStatusPesananBadge');
        var labelSt = (data.status_pesanan || '').toUpperCase();
        if (data.is_batal_pelanggan) {
            stPsnEl.innerHTML = '<span class="badge bg-danger rounded-pill px-2 py-1"><i class="bi bi-slash-circle me-1"></i> DIBATALKAN PELANGGAN</span>';
        } else if (data.is_ditolak_admin || data.status_bayar === 'gagal') {
            stPsnEl.innerHTML = '<span class="badge bg-danger rounded-pill px-2 py-1"><i class="bi bi-x-circle me-1"></i> DITOLAK ADMIN</span>';
        } else if (labelSt === 'DIBATALKAN') {
            stPsnEl.innerHTML = '<span class="badge bg-danger rounded-pill px-2 py-1">DIBATALKAN</span>';
        } else if (labelSt === 'SELESAI') {
            stPsnEl.innerHTML = '<span class="badge bg-success rounded-pill px-2 py-1">SELESAI</span>';
        } else {
            stPsnEl.innerHTML = '<span class="badge bg-secondary rounded-pill px-2 py-1">' + labelSt + '</span>';
        }

        // Catatan
        var wrapCatatan = document.getElementById('wrapDetCatatan');
        if (data.catatan && data.catatan.trim() !== '') {
            document.getElementById('detCatatan').textContent = data.catatan;
            wrapCatatan.style.display = 'block';
        } else {
            wrapCatatan.style.display = 'none';
        }

        var bsModal = new bootstrap.Modal(document.getElementById('modalDetailPesanan'));
        bsModal.show();
    }
</script>

<?= $this->endSection() ?>