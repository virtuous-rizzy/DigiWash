<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn(string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$transaksi = (array) ($transaksi ?? []);
$statusFilter = (string) ($statusFilter ?? 'semua');
$keyword = (string) ($keyword ?? '');
$totalMenunggu = (int) ($totalMenunggu ?? 0);
$totalLunas = (int) ($totalLunas ?? 0);
$totalDitolak = (int) ($totalDitolak ?? 0);
$totalSemua = (int) ($totalSemua ?? count($transaksi));
?>

<style>
    /* ── Header Memproses Transaksi ── */
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

    /* ── Search & Filter Controls ── */
    .search-filter-row {
        display: flex;
        gap: 8px;
        align-items: center;
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
        border-color: #033B59;
        box-shadow: 0 0 0 3px rgba(3, 59, 89, 0.12);
    }

    .btn-filter-toggle {
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
        white-space: nowrap;
    }

    .btn-filter-toggle:hover,
    .btn-filter-toggle.active {
        background: #f1f5f9;
        border-color: #033B59;
        color: #033B59;
    }

    /* ── Filter Pills / Chips ── */
    .filter-chips {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 10px;
        margin-bottom: 12px;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .filter-chips::-webkit-scrollbar {
        display: none;
    }

    .chip-btn {
        border: 1px solid #DCE0E5;
        background: #fff;
        border-radius: 20px;
        height: 34px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        text-decoration: none;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }

    .chip-btn:hover {
        border-color: #033B59;
        color: #033B59;
    }

    .chip-btn.active {
        background: #033B59;
        border-color: #033B59;
        color: #fff;
    }

    .chip-badge {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 1px 6px;
        font-size: 10px;
        font-weight: 700;
    }

    .chip-btn.active .chip-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
    }

    /* ── Cards List ── */
    .transaksi-list {
        padding: 4px 0 24px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .transaksi-card {
        background: #FFFFFF;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        padding: 16px;
        transition: box-shadow 0.2s, transform 0.15s;
    }

    .transaksi-card:hover {
        box-shadow: 0 4px 14px rgba(3, 59, 89, 0.08);
    }

    /* Card Header */
    .card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .trx-id {
        font-size: 13px;
        font-weight: 700;
        color: #111;
        letter-spacing: 0.2px;
    }

    .trx-date {
        font-size: 11px;
        color: #718096;
        font-weight: 400;
    }

    /* Card Content */
    .card-body-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .cust-info-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .cust-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #E2E8F0;
        color: #033B59;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .cust-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cust-details {
        min-width: 0;
    }

    .cust-name {
        font-size: 15px;
        font-weight: 700;
        color: #1A202C;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cust-order-desc {
        font-size: 12px;
        color: #718096;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cust-meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
    }

    .method-tag {
        font-size: 10px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 6px;
        background: #EDF2F7;
        color: #4A5568;
    }

    .btn-view-receipt {
        font-size: 11px;
        font-weight: 600;
        color: #033B59;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .btn-view-receipt:hover {
        text-decoration: underline;
    }

    /* Card Right: Price & Status */
    .card-price-right {
        text-align: right;
        flex-shrink: 0;
    }

    .trx-price {
        font-size: 17px;
        font-weight: 800;
        color: #E65100;
        line-height: 1.2;
    }

    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        margin-top: 5px;
    }

    .status-menunggu {
        background: #FFF3E0;
        color: #E65100;
    }

    .status-lunas {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .status-gagal {
        background: #FFEBEE;
        color: #C62828;
    }

    .status-batal {
        background: #FEE2E2;
        color: #991B1B;
    }

    .trx-cancel-note {
        font-size: 11px;
        font-weight: 500;
        color: #991B1B;
        background: #FEF2F2;
        border: 1px solid #FECACA;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-top: 6px;
        max-width: 100%;
        word-break: break-word;
    }

    /* Card Actions (Footer) */
    .card-actions-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding-top: 8px;
        border-top: 1px dashed #EDF2F7;
    }

    .btn-tolak {
        padding: 6px 18px;
        border-radius: 8px;
        border: 1.5px solid #FF3B30;
        background: #FFFFFF;
        color: #FF3B30;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-tolak:hover {
        background: #FFF5F5;
        color: #D32F2F;
        border-color: #D32F2F;
    }

    .btn-verifikasi {
        padding: 6px 20px;
        border-radius: 8px;
        border: none;
        background: #009624;
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-verifikasi:hover {
        background: #007E1F;
    }

    .btn-lunas-static {
        padding: 6px 20px;
        border-radius: 8px;
        border: none;
        background: #3F8CFF;
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 600;
        cursor: default;
    }

    .btn-ditolak-static {
        padding: 6px 16px;
        border-radius: 8px;
        border: 1px solid #E57373;
        background: #FFEBEE;
        color: #C62828;
        font-size: 12px;
        font-weight: 600;
        cursor: default;
        display: inline-flex;
        align-items: center;
    }

    .btn-batal-static {
        padding: 6px 16px;
        border-radius: 8px;
        border: 1px solid #FCA5A5;
        background: #FEF2F2;
        color: #991B1B;
        font-size: 12px;
        font-weight: 600;
        cursor: default;
        display: inline-flex;
        align-items: center;
    }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 54px;
        color: #CBD5E0;
        margin-bottom: 12px;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 700;
        color: #4A5568;
        margin-bottom: 4px;
    }

    .empty-desc {
        font-size: 13px;
        color: #A0AEC0;
    }

    /* ── Responsive Tablet & Desktop ── */
    @media (min-width: 768px) {
        .transaksi-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }
    }
</style>

<!-- ══ HEADER ══ -->
<div class="page-title-box d-flex align-items-center gap-2">
    <a href="<?= base_url('admin/dashboard') ?>" class="btn-back-link" title="Kembali ke Dashboard">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="page-heading">Memproses Transaksi</h1>
        <p class="page-subheading">Verifikasi dan kelola pembayaran pelanggan</p>
    </div>
</div>

<!-- ══ SEARCH & CONTROLS ══ -->
<form method="get" action="<?= base_url('admin/transaksi') ?>" class="search-filter-row" id="formSearch">
    <input type="hidden" name="status" value="<?= esc($statusFilter ?? 'semua') ?>">

    <div class="search-input-wrap">
        <i class="bi bi-search search-icon"></i>
        <input type="text" name="q" class="search-input-box" placeholder="Cari no. transaksi / pelanggan..."
            value="<?= esc($keyword ?? '') ?>">
    </div>

    <button type="button"
        class="btn-filter-toggle <?= (!empty($statusFilter) && $statusFilter !== 'semua') ? 'active' : '' ?>"
        id="btnToggleFilter" onclick="toggleFilterChips()">
        <i class="bi bi-funnel"></i>
        Filter
    </button>
</form>

<!-- ══ FILTER CHIPS ══ -->
<div class="filter-chips" id="filterChipsRow">
    <a href="<?= base_url('admin/transaksi?status=semua' . ($keyword !== '' ? '&q=' . urlencode($keyword) : '')) ?>"
        class="chip-btn <?= ($statusFilter === 'semua' || empty($statusFilter)) ? 'active' : '' ?>">
        Semua
        <span class="chip-badge"><?= (int) ($totalSemua ?? 0) ?></span>
    </a>

    <a href="<?= base_url('admin/transaksi?status=menunggu_verifikasi' . ($keyword !== '' ? '&q=' . urlencode($keyword) : '')) ?>"
        class="chip-btn <?= ($statusFilter === 'menunggu_verifikasi') ? 'active' : '' ?>">
        <i class="bi bi-clock-history text-warning"></i>
        Menunggu
        <span class="chip-badge"><?= (int) ($totalMenunggu ?? 0) ?></span>
    </a>

    <a href="<?= base_url('admin/transaksi?status=lunas' . ($keyword !== '' ? '&q=' . urlencode($keyword) : '')) ?>"
        class="chip-btn <?= ($statusFilter === 'lunas') ? 'active' : '' ?>">
        <i class="bi bi-check2-circle text-success"></i>
        Lunas
        <span class="chip-badge"><?= (int) ($totalLunas ?? 0) ?></span>
    </a>

    <a href="<?= base_url('admin/transaksi?status=dibatalkan' . ($keyword !== '' ? '&q=' . urlencode($keyword) : '')) ?>"
        class="chip-btn <?= ($statusFilter === 'dibatalkan') ? 'active' : '' ?>">
        <i class="bi bi-slash-circle text-danger"></i>
        Dibatalkan
        <span class="chip-badge"><?= (int) ($totalDibatalkan ?? 0) ?></span>
    </a>

    <a href="<?= base_url('admin/transaksi?status=ditolak' . ($keyword !== '' ? '&q=' . urlencode($keyword) : '')) ?>"
        class="chip-btn <?= ($statusFilter === 'ditolak' || $statusFilter === 'gagal') ? 'active' : '' ?>">
        <i class="bi bi-x-circle text-danger"></i>
        Ditolak
        <span class="chip-badge"><?= (int) ($totalDitolak ?? 0) ?></span>
    </a>
</div>

<!-- ══ TRANSAKSI LIST ══ -->
<div class="transaksi-list">

    <?php if (empty($transaksi)): ?>
        <div class="empty-state">
            <i class="bi bi-inbox empty-icon"></i>
            <div class="empty-title">Tidak Ada Transaksi</div>
            <div class="empty-desc">
                <?= !empty($keyword) ? 'Tidak ditemukan transaksi dengan kata kunci tersebut.' : 'Belum ada transaksi pada filter ini.' ?>
            </div>
        </div>
    <?php else: ?>

        <?php foreach ($transaksi as $t):
            $idPesanan = (int) ($t['id_pesanan'] ?? 1);
            $nomorPesanan = 'PSN-' . date('dmY', strtotime((string) ($t['tanggal_pesanan'] ?? 'now'))) . '-' . str_pad((string) $idPesanan, 3, '0', STR_PAD_LEFT);
            $tglFormatted = date('j M Y - H:i', strtotime((string) ($t['tanggal_pesanan'] ?? $t['tanggal_bayar'] ?? 'now')));
            $namaPelanggan = (string) ($t['nama_pelanggan'] ?? 'Pelanggan');
            $inisial = strtoupper(substr($namaPelanggan, 0, 1));
            $fotoPelanggan = url_foto_profil($t['foto_pelanggan'] ?? null);
            $layananNama = (string) ($t['nama_layanan'] ?? 'Laundry Reguler');
            $berat = (float) ($t['berat'] ?? 1.0);
            $totalBayar = (float) ($t['total_bayar'] ?? 0);
            $statusBayar = (string) ($t['status_bayar'] ?? 'menunggu_verifikasi');
            $metodeBayar = (string) ($t['metode'] ?? 'Bank BCA');
            $buktiBayar = !empty($t['bukti_bayar']) ? base_url('uploads/bukti/' . $t['bukti_bayar']) : null;
            $catatanPsn = (string) ($t['catatan'] ?? '');
            $stPesanan = (string) ($t['status_pesanan'] ?? '');
            $idAdminTrx = $t['id_admin'] ?? null;

            // Cek apakah transaksi dibatalkan oleh pelanggan vs ditolak admin
            $catatanLower = strtolower($catatanPsn);
            $isBatalPelanggan = str_contains($catatanLower, 'dibatalkan') || ($stPesanan === 'dibatalkan' && empty($idAdminTrx) && !str_contains($catatanLower, 'ditolak'));
            ?>
            <div class="transaksi-card" id="trx-card-<?= (int) $t['id_pembayaran'] ?>">

                <!-- Top Row: No. Pesanan & Tanggal -->
                <div class="card-header-row">
                    <span class="trx-id"><?= esc($nomorPesanan) ?></span>
                    <span class="trx-date"><?= esc($tglFormatted) ?></span>
                </div>

                <!-- Middle Row: Pelanggan & Harga -->
                <div class="card-body-row">
                    <div class="cust-info-left">
                        <div class="cust-avatar">
                            <?php if ($fotoPelanggan): ?>
                                <img src="<?= esc($fotoPelanggan) ?>" alt="<?= esc($namaPelanggan) ?>">
                            <?php else: ?>
                                <?= esc($inisial) ?>
                            <?php endif; ?>
                        </div>

                        <div class="cust-details">
                            <div class="cust-name"><?= esc($namaPelanggan) ?></div>
                            <div class="cust-order-desc">
                                <?= esc($layananNama) ?> - <?= number_format($berat, 0, ',', '.') ?> Kg
                            </div>
                            <div class="cust-meta-row">
                                <span class="method-tag"><i class="bi bi-wallet2 me-1"></i><?= esc($metodeBayar) ?></span>
                                <?php if ($buktiBayar): ?>
                                    <a href="javascript:void(0)"
                                        onclick="previewBukti('<?= esc($buktiBayar) ?>', '<?= esc($nomorPesanan) ?>', '<?= esc($namaPelanggan) ?>')"
                                        class="btn-view-receipt">
                                        <i class="bi bi-image"></i> Bukti Transfer
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($catatanPsn) && ($isBatalPelanggan || str_contains($catatanLower, 'ditolak'))): ?>
                                <div class="trx-cancel-note">
                                    <i class="bi bi-info-circle me-1"></i><?= esc($catatanPsn) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-price-right">
                        <div class="trx-price">Rp <?= number_format($totalBayar, 0, ',', '.') ?></div>
                        <?php if ($statusBayar === 'menunggu_verifikasi'): ?>
                            <span class="status-badge status-menunggu">Menunggu Verifikasi</span>
                        <?php elseif ($statusBayar === 'lunas'): ?>
                            <span class="status-badge status-lunas"><i class="bi bi-check2-circle me-1"></i>Lunas</span>
                        <?php elseif ($isBatalPelanggan): ?>
                            <span class="status-badge status-batal"><i class="bi bi-slash-circle me-1"></i>Dibatalkan</span>
                        <?php else: ?>
                            <span class="status-badge status-gagal"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Bottom Row: Action Buttons -->
                <div class="card-actions-row">
                    <?php if ($statusBayar === 'menunggu_verifikasi'): ?>
                        <!-- Tolak Form -->
                        <form action="<?= base_url('admin/transaksi/tolak/' . (int) $t['id_pembayaran']) ?>" method="post"
                            id="form-tolak-<?= (int) $t['id_pembayaran'] ?>" style="margin:0;">
                            <?= csrf_field() ?>
                            <button type="button" class="btn-tolak"
                                onclick="bukaModalTolak(<?= (int) $t['id_pembayaran'] ?>, '<?= esc($nomorPesanan) ?>', '<?= esc($namaPelanggan) ?>')">
                                Tolak
                            </button>
                        </form>

                        <!-- Verifikasi Form -->
                        <form action="<?= base_url('admin/transaksi/verifikasi/' . (int) $t['id_pembayaran']) ?>" method="post"
                            id="form-verif-<?= (int) $t['id_pembayaran'] ?>" style="margin:0;">
                            <?= csrf_field() ?>
                            <button type="button" class="btn-verifikasi"
                                onclick="bukaModalVerifikasi(<?= (int) $t['id_pembayaran'] ?>, '<?= esc($nomorPesanan) ?>', '<?= esc($namaPelanggan) ?>', 'Rp <?= number_format($totalBayar, 0, ',', '.') ?>')">
                                Verifikasi
                            </button>
                        </form>

                    <?php elseif ($statusBayar === 'lunas'): ?>
                        <span class="btn-lunas-static"><i class="bi bi-check-circle-fill me-1"></i>Lunas (Dikonfirmasi)</span>

                    <?php elseif ($isBatalPelanggan): ?>
                        <span class="btn-batal-static"><i class="bi bi-slash-circle me-1"></i>Dibatalkan</span>

                    <?php else: ?>
                        <span class="btn-ditolak-static"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

<!-- ══ MODAL PREVIEW BUKTI TRANSFER ══ -->
<div class="modal fade" id="modalBuktiBayar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
            <div class="modal-header" style="background: #033B59; color: #fff;">
                <h6 class="modal-title fw-bold" id="modalBuktiTitle">
                    <i class="bi bi-receipt me-2"></i>Bukti Transfer
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3" style="background: #f8f9fa;">
                <p class="text-muted small mb-2" id="modalBuktiSub"></p>
                <div
                    style="max-height: 440px; overflow-y: auto; border-radius: 8px; background: #fff; padding: 6px; box-shadow: inset 0 0 6px rgba(0,0,0,0.06);">
                    <img id="imgBuktiPreview" src="" alt="Bukti Pembayaran"
                        style="max-width: 100%; height: auto; border-radius: 6px; display: block; margin: 0 auto;">
                </div>
            </div>
            <div class="modal-footer" style="background: #fff;">
                <button type="button" class="btn btn-secondary rounded-3 btn-sm" data-bs-dismiss="modal">Tutup</button>
                <a id="btnDownloadBukti" href="#" target="_blank" class="btn btn-primary rounded-3 btn-sm"
                    style="background: #033B59; border: none;">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka Gambar Penuh
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ══ MODAL KONFIRMASI VERIFIKASI ══ -->
<div class="modal fade" id="modalKonfirmasiVerifikasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content"
            style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
            <div class="modal-header" style="background: #033B59; color: #fff; padding: 16px 22px;">
                <h6 class="modal-title fw-bold" style="margin: 0; font-size: 16px;">
                    <i class="bi bi-check2-circle me-2"></i>Konfirmasi Verifikasi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3"
                    style="width: 70px; height: 70px; border-radius: 50%; background: #E8F5E9; color: #009624; display: inline-flex; align-items: center; justify-content: center; font-size: 36px;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: #111;">Verifikasi Pembayaran</h5>
                <p class="text-muted small mb-3">
                    Pastikan dana transfer telah masuk ke rekening sebelum menandai lunas.
                </p>
                <div class="p-3 rounded-3 text-start mb-2"
                    style="background: #F8F9FA; border: 1px solid #E9ECEF; font-size: 13px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">No. Pesanan:</span>
                        <strong id="verifNoPesanan" class="text-dark">-</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Pelanggan:</span>
                        <strong id="verifNamaPelanggan" class="text-dark">-</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Bayar:</span>
                        <strong id="verifTotalBayar" class="text-success fw-bold">-</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer"
                style="background: #fff; border-top: 1px solid #ECECEC; padding: 12px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm px-3" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="btnConfirmVerifAction" class="btn btn-success rounded-3 btn-sm px-3 fw-bold"
                    style="background: #009624; border: none;">
                    <i class="bi bi-check-circle me-1"></i>Ya, Verifikasi Lunas
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══ MODAL KONFIRMASI TOLAK ══ -->
<div class="modal fade" id="modalKonfirmasiTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content"
            style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
            <div class="modal-header" style="background: #E53935; color: #fff; padding: 16px 22px;">
                <h6 class="modal-title fw-bold" style="margin: 0; font-size: 16px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Tolak
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3"
                    style="width: 70px; height: 70px; border-radius: 50%; background: #FFEBEE; color: #E53935; display: inline-flex; align-items: center; justify-content: center; font-size: 34px;">
                    <i class="bi bi-x-lg"></i>
                </div>
                <h5 class="fw-bold mb-2 text-danger">Tolak Transaksi?</h5>
                <p class="text-muted small mb-3">
                    Transaksi ini akan ditandai <strong class="text-danger">Gagal</strong> dan pesanan pelanggan akan
                    otomatis <strong class="text-danger">Dibatalkan</strong>.
                </p>
                <div class="p-3 rounded-3 text-start mb-2"
                    style="background: #FFF5F5; border: 1px solid #FFCDD2; font-size: 13px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">No. Pesanan:</span>
                        <strong id="tolakNoPesanan" class="text-dark">-</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Pelanggan:</span>
                        <strong id="tolakNamaPelanggan" class="text-dark">-</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer"
                style="background: #fff; border-top: 1px solid #ECECEC; padding: 12px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm px-3" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="btnConfirmTolakAction" class="btn btn-danger rounded-3 btn-sm px-3 fw-bold"
                    style="background: #E53935; border: none;">
                    <i class="bi bi-x-circle me-1"></i>Ya, Tolak Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var currentVerifId = null;
    var currentTolakId = null;
    var modalVerif = null;
    var modalTolak = null;

    document.addEventListener('DOMContentLoaded', function () {
        var elVerif = document.getElementById('modalKonfirmasiVerifikasi');
        if (elVerif && typeof bootstrap !== 'undefined') modalVerif = new bootstrap.Modal(elVerif);

        var elTolak = document.getElementById('modalKonfirmasiTolak');
        if (elTolak && typeof bootstrap !== 'undefined') modalTolak = new bootstrap.Modal(elTolak);

        var btnVerif = document.getElementById('btnConfirmVerifAction');
        if (btnVerif) {
            btnVerif.addEventListener('click', function () {
                if (currentVerifId) {
                    var form = document.getElementById('form-verif-' + currentVerifId);
                    if (form) form.submit();
                }
            });
        }

        var btnTolak = document.getElementById('btnConfirmTolakAction');
        if (btnTolak) {
            btnTolak.addEventListener('click', function () {
                if (currentTolakId) {
                    var form = document.getElementById('form-tolak-' + currentTolakId);
                    if (form) form.submit();
                }
            });
        }
    });

    function bukaModalVerifikasi(id, noPesanan, namaPelanggan, totalBayar) {
        currentVerifId = id;
        document.getElementById('verifNoPesanan').textContent = noPesanan;
        document.getElementById('verifNamaPelanggan').textContent = namaPelanggan;
        document.getElementById('verifTotalBayar').textContent = totalBayar;
        if (modalVerif) modalVerif.show();
    }

    function bukaModalTolak(id, noPesanan, namaPelanggan) {
        currentTolakId = id;
        document.getElementById('tolakNoPesanan').textContent = noPesanan;
        document.getElementById('tolakNamaPelanggan').textContent = namaPelanggan;
        if (modalTolak) modalTolak.show();
    }

    function previewBukti(url, noPesanan, namaPelanggan) {
        document.getElementById('imgBuktiPreview').src = url;
        document.getElementById('btnDownloadBukti').href = url;
        document.getElementById('modalBuktiSub').textContent = 'Pesanan ' + noPesanan + ' oleh ' + namaPelanggan;
        if (typeof bootstrap !== 'undefined') {
            var modal = new bootstrap.Modal(document.getElementById('modalBuktiBayar'));
            modal.show();
        }
    }

    function toggleFilterChips() {
        var row = document.getElementById('filterChipsRow');
        if (row) {
            row.style.display = (row.style.display === 'none') ? 'flex' : 'none';
        }
    }
</script>

<?= $this->endSection() ?>