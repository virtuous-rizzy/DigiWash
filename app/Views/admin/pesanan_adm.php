<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn(string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$pesanan = (array) ($pesanan ?? []);
$q = (string) ($q ?? '');
$statusFilter = (string) ($statusFilter ?? 'semua');
$bayarFilter = (string) ($bayarFilter ?? 'semua');
$kurirList = (array) ($kurirList ?? []);

// Kamus nama bulan Indonesia
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
    /* ── Halaman Memproses Pesanan Admin (Sesuai Mockup) ── */
    .memproses-header {
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
        width: 34px;
        height: 34px;
        border-radius: 50%;
        text-decoration: none;
        color: #000;
        font-size: 20px;
        transition: background 0.15s;
    }

    .btn-back-krr:hover {
        background: #e2e8f0;
        color: #000;
    }

    .memproses-title {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-size: 18px;
        font-weight: 800;
        color: #000;
        margin: 0;
        white-space: nowrap;
    }

    /* ── Baris Pencarian & Tombol Filter ── */
    .search-filter-row {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 16px;
    }

    .search-input-wrap {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input-wrap .search-icon {
        position: absolute;
        left: 12px;
        font-size: 15px;
        color: #000;
        font-weight: bold;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 10px 36px 10px 38px;
        border: 1.5px solid #d1d5db;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        outline: none;
        background: #fff;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .search-input:focus {
        border-color: #033B59;
        box-shadow: 0 0 0 3px rgba(3, 59, 89, 0.12);
    }

    .search-clear {
        position: absolute;
        right: 12px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    .search-clear:hover {
        color: #475569;
    }

    .btn-filter-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 16px;
        background: #fff;
        border: 1.5px solid #d1d5db;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        cursor: pointer;
        white-space: nowrap;
        position: relative;
        transition: all 0.15s ease;
    }

    .btn-filter-trigger:hover {
        background: #f8fafc;
        border-color: #033B59;
        color: #033B59;
    }

    .filter-indicator-dot {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ef4444;
    }

    /* ── Active Filter Bar ── */
    .active-filters-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        margin-bottom: 14px;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #e0f2fe;
        color: #033B59;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
    }

    .filter-chip a {
        color: #033B59;
        text-decoration: none;
        font-size: 13px;
        line-height: 1;
    }

    /* ── Kartu Pesanan (Sesuai Mockup) ── */
    .order-cards-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }

    .order-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .order-card-main {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .order-card-left {
        display: flex;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .order-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        color: #fff;
        overflow: hidden;
        background: #033B59;
    }

    .order-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .order-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .order-cust-name {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .order-code-text {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        line-height: 1.2;
    }

    .order-service-text {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        margin-top: 2px;
    }

    .order-date-text {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 500;
        color: #64748b;
        margin-top: 2px;
    }

    .order-card-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .order-price-text {
        font-size: 17px;
        font-weight: 900;
        color: #000;
        line-height: 1.2;
        margin-bottom: 8px;
    }

    /* ── Baris Tombol Aksi Pesanan ── */
    .order-actions-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-detail-order {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #ef4444;
        color: #ef4444;
        background: #ffffff;
        padding: 5px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-detail-order:hover {
        background: #ef4444;
        color: #ffffff;
    }

    .btn-proses-order {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #019D13;
        color: #ffffff;
        border: 1.5px solid #019D13;
        padding: 5px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s ease;
    }

    .btn-proses-order:hover {
        background: #018310;
        border-color: #018310;
        color: #ffffff;
    }

    .btn-status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        color: #ffffff;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        transition: opacity 0.15s;
    }

    .btn-status-badge:hover {
        opacity: 0.9;
        color: #fff;
    }

    .badge-antar {
        background: #2196F3;
    }

    /* Sesuai warna Sedang diantar mockup */
    .badge-jemput {
        background: #0284c7;
    }

    .badge-cuci {
        background: #6366f1;
    }

    .badge-setrika {
        background: #8b5cf6;
    }

    .badge-selesai {
        background: #10b981;
    }

    .badge-batal {
        background: #ef4444;
    }

    /* ── Desktop Responsiveness ── */
    @media (min-width: 900px) {
        .order-cards-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
    }
</style>

<!-- Header Memproses Pesanan -->
<div class="memproses-header">
    <a href="<?= base_url('admin/dashboard') ?>" class="btn-back-krr" title="Kembali ke Dashboard">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="memproses-title">Memproses Pesanan</h1>
</div>


<!-- Baris Pencarian & Filter -->
<form action="<?= base_url('admin/pesanan') ?>" method="get" class="search-filter-row">
    <input type="hidden" name="status" value="<?= $e($statusFilter) ?>">
    <input type="hidden" name="bayar" value="<?= $e($bayarFilter) ?>">

    <div class="search-input-wrap">
        <i class="bi bi-search search-icon"></i>
        <input type="text" name="q" class="search-input" placeholder="Cari no. transaksi / pelanggan..."
            value="<?= $e($q) ?>">
        <?php if ($q !== ''): ?>
            <a href="<?= base_url('admin/pesanan?status=' . urlencode($statusFilter) . '&bayar=' . urlencode($bayarFilter)) ?>"
                class="search-clear" title="Hapus pencarian">
                <i class="bi bi-x-circle-fill"></i>
            </a>
        <?php endif; ?>
    </div>

    <button type="button" class="btn-filter-trigger" data-bs-toggle="modal" data-bs-target="#modalFilterPesanan">
        <i class="bi bi-funnel"></i>
        <span>Filter</span>
        <?php if ($statusFilter !== 'semua' || $bayarFilter !== 'semua'): ?>
            <span class="filter-indicator-dot"></span>
        <?php endif; ?>
    </button>
</form>


<!-- Daftar Kartu Pesanan -->
<?php if (empty($pesanan)): ?>
    <div class="panel kosong text-center py-5 bg-white rounded-4 border">
        <div class="mb-2">
            <i class="bi bi-inbox text-muted" style="font-size: 42px;"></i>
        </div>
        <h6 class="fw-bold text-dark mb-1">Tidak Ada Pesanan</h6>
        <p class="text-muted small mb-3">Tidak ada data pesanan yang sesuai dengan filter atau kata kunci pencarian Anda.
        </p>
        <a href="<?= base_url('admin/pesanan') ?>" class="btn btn-sm btn-outline-secondary rounded-3 px-3 fw-bold">
            Tampilkan Semua Pesanan
        </a>
    </div>
<?php else: ?>
    <div class="order-cards-list">
        <?php foreach ($pesanan as $p): ?>
            <?php
            $idPsn = (int) ($p['id_pesanan'] ?? 0);
            $namaPlg = (string) ($p['nama_pelanggan'] ?? 'Pelanggan');
            $inisialPlg = strtoupper(substr($namaPlg, 0, 1));
            $fotoPlg = (string) ($p['foto_pelanggan'] ?? '');
            $namaLayanan = (string) ($p['nama_layanan'] ?? 'Laundry Reguler');
            $beratVal = (float) ($p['berat'] ?? 0);
            $stPesanan = (string) ($p['status_pesanan'] ?? 'menunggu');
            $stBayar = (string) ($p['status_bayar'] ?? 'belum_dibayar');

            // Format Kode Pesanan sesuai format mockup: PSN-03062026-001
            $tglRaw = $p['tanggal'] ?? $p['created_at'] ?? 'now';
            $tglObj = strtotime($tglRaw);
            $kodePesanan = 'PSN-' . date('dmY', $tglObj) . '-' . str_pad((string) $idPsn, 3, '0', STR_PAD_LEFT);

            // Format Tanggal & Jam Indonesia (Contoh: 03 Juni 2026 • 08.00)
            $tglTeks = date('d F Y', $tglObj);
            $tglIndo = strtr($tglTeks, $bulanIndo);
            $jamIndo = date('H.i', $tglObj);

            // Hitung Total Bayar
            $totalHarga = (float) ($p['total_bayar'] ?? 0);
            if ($totalHarga <= 0) {
                $flat = (float) ($p['harga_flat'] ?? 0);
                $perKg = (float) ($p['harga_per_kg'] ?? 0);
                $totalHarga = $flat > 0 ? $flat : ($perKg * $beratVal);
            }

            // Siapkan data JSON lengkap untuk modal detail
            $dataJson = htmlspecialchars(json_encode([
                'id_pesanan' => $idPsn,
                'kode_pesanan' => $kodePesanan,
                'nama_pelanggan' => $namaPlg,
                'no_hp_pelanggan' => (string) ($p['no_hp_pelanggan'] ?? '-'),
                'nama_layanan' => $namaLayanan,
                'berat' => $beratVal,
                'total_harga' => number_format($totalHarga, 0, ',', '.'),
                'tanggal_indo' => $tglIndo . ' • ' . $jamIndo,
                'status_pesanan' => $stPesanan,
                'status_bayar' => $stBayar,
                'metode_bayar' => (string) ($p['metode'] ?? 'Transfer Bank'),
                'alamat_ambil' => (string) ($p['alamat_pengambilan'] ?? '-'),
                'alamat_antar' => (string) ($p['alamat_pengantaran'] ?? '-'),
                'catatan' => (string) ($p['catatan'] ?? ''),
                'id_kurir' => (int) ($p['id_kurir'] ?? 0),
                'nama_kurir' => (string) ($p['nama_kurir'] ?? 'Belum Ditugaskan'),
            ]), ENT_QUOTES, 'UTF-8');
            ?>

            <div class="order-card">
                <div class="order-card-main">
                    <!-- Sisi Kiri: Avatar & Info Pelanggan -->
                    <div class="order-card-left">
                        <div class="order-avatar">
                            <?php $urlFotoPlg = url_foto_profil($fotoPlg); ?>
                            <?php if ($urlFotoPlg): ?>
                                <img src="<?= $e($urlFotoPlg) ?>" alt="<?= $e($namaPlg) ?>">
                            <?php else: ?>
                                <?= $e($inisialPlg) ?>
                            <?php endif; ?>
                        </div>
                        <div class="order-info">
                            <span class="order-cust-name"><?= $e($namaPlg) ?></span>
                            <span class="order-code-text"><?= $e($kodePesanan) ?></span>
                            <span class="order-service-text"><?= $e($namaLayanan) ?> • <?= $beratVal ?> Kg</span>
                            <span class="order-date-text">
                                <i class="bi bi-calendar3"></i> <?= $tglIndo ?> • <?= $jamIndo ?>
                            </span>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Harga & Tombol Aksi -->
                    <div class="order-card-right">
                        <div class="order-price-text">
                            Rp <?= number_format($totalHarga, 0, ',', '.') ?>
                        </div>

                        <div class="order-actions-row">
                            <!-- Tombol 1: Detail (Merah Outline Sesuai Mockup) -->
                            <button type="button" class="btn-detail-order" onclick="bukaModalDetail(<?= $dataJson ?>)">
                                Detail
                            </button>

                            <!-- Tombol 2: Status / Proses Sesuai Mockup -->
                            <?php if (in_array($stPesanan, ['menunggu', 'dikonfirmasi'], true)): ?>
                                <!-- Tombol Hijau Proses -->
                                <button type="button" class="btn-proses-order"
                                    onclick="bukaModalProses(<?= $idPsn ?>, '<?= $e($kodePesanan) ?>', '<?= $e($namaPlg) ?>', '<?= $stPesanan ?>', <?= (int) ($p['id_kurir'] ?? 0) ?>)">
                                    Proses
                                </button>
                            <?php elseif ($stPesanan === 'diantar'): ?>
                                <!-- Tombol Biru Sedang diantar Sesuai Mockup -->
                                <button type="button" class="btn-status-badge badge-antar"
                                    onclick="bukaModalProses(<?= $idPsn ?>, '<?= $e($kodePesanan) ?>', '<?= $e($namaPlg) ?>', '<?= $stPesanan ?>', <?= (int) ($p['id_kurir'] ?? 0) ?>)">
                                    Sedang diantar
                                </button>
                            <?php elseif ($stPesanan === 'dijemput'): ?>
                                <button type="button" class="btn-status-badge badge-jemput"
                                    onclick="bukaModalProses(<?= $idPsn ?>, '<?= $e($kodePesanan) ?>', '<?= $e($namaPlg) ?>', '<?= $stPesanan ?>', <?= (int) ($p['id_kurir'] ?? 0) ?>)">
                                    Sedang dijemput
                                </button>
                            <?php elseif ($stPesanan === 'dicuci'): ?>
                                <button type="button" class="btn-status-badge badge-cuci"
                                    onclick="bukaModalProses(<?= $idPsn ?>, '<?= $e($kodePesanan) ?>', '<?= $e($namaPlg) ?>', '<?= $stPesanan ?>', <?= (int) ($p['id_kurir'] ?? 0) ?>)">
                                    Sedang dicuci
                                </button>
                            <?php elseif ($stPesanan === 'disetrika'): ?>
                                <button type="button" class="btn-status-badge badge-setrika"
                                    onclick="bukaModalProses(<?= $idPsn ?>, '<?= $e($kodePesanan) ?>', '<?= $e($namaPlg) ?>', '<?= $stPesanan ?>', <?= (int) ($p['id_kurir'] ?? 0) ?>)">
                                    Sedang disetrika
                                </button>
                            <?php elseif ($stPesanan === 'selesai'): ?>
                                <span class="btn-status-badge badge-selesai" style="cursor: default;">
                                    Selesai
                                </span>
                            <?php else: ?>
                                <span class="btn-status-badge badge-batal" style="cursor: default;">
                                    Dibatalkan
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- MODAL 1: DETAIL PESANAN                                    -->
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
                style="background: #f8f9fa; border-top: 1px solid #eee; display: flex; justify-content: space-between; padding: 12px 20px;">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" id="btnAlihKeProses" class="btn btn-sm btn-primary rounded-3 px-3 fw-bold"
                    style="background: #033B59; border-color: #033B59;">
                    <i class="bi bi-arrow-repeat me-1"></i> Ubah Status Laundry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- MODAL 2: PROSES / UBAH STATUS LAUNDRY                      -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalProsesPesanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content"
            style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <form id="formProsesPesanan" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header" style="background: #033B59; color: #fff; padding: 14px 20px;">
                    <h6 class="modal-title fw-bold" style="margin: 0; font-size: 15px;">
                        <i class="bi bi-gear-fill me-2"></i>Proses / Update Pesanan
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 p-3 rounded-3" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                        <div class="small text-muted">Pesanan yang diproses:</div>
                        <div class="fw-bold fs-6 text-dark" id="modalProsesKode"></div>
                        <div class="small fw-semibold text-primary" id="modalProsesPelanggan"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Status Laundry Baru:</label>
                        <select name="status_pesanan" id="selectStatusPesanan" class="form-select form-select-sm py-2"
                            required>
                            <!-- Diisi dinamis via JavaScript sesuai statusSaatIni -->
                        </select>
                        <div id="helpTextStatusPesanan" class="small text-muted mt-1" style="font-size: 11px;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Tugaskan Kurir:</label>
                        <select name="id_kurir" id="selectKurirPesanan" class="form-select form-select-sm py-2">
                            <option value="">-- Belum Ada Kurir / Tanpa Kurir --</option>
                            <?php foreach ($kurirList as $k): ?>
                                <option value="<?= (int) $k['id_kurir'] ?>">
                                    <?= $e((string) $k['nama']) ?> (<?= $e(ucfirst((string) $k['status'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted" style="font-size: 11px;">
                            Pilih kurir untuk tahap penjemputan atau pengantaran pakaian.
                        </small>
                    </div>
                </div>
                <div class="modal-footer"
                    style="background: #f8f9fa; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 8px; padding: 12px 20px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3"
                        data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-success rounded-3 px-3 fw-bold"
                        style="background: #019D13; border-color: #019D13;">
                        <i class="bi bi-check2-circle me-1"></i> Simpan &amp; Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- MODAL 3: FILTER PESANAN                                    -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalFilterPesanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content"
            style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <form action="<?= base_url('admin/pesanan') ?>" method="get">
                <input type="hidden" name="q" value="<?= $e($q) ?>">

                <div class="modal-header" style="background: #033B59; color: #fff; padding: 14px 20px;">
                    <h6 class="modal-title fw-bold" style="margin: 0; font-size: 15px;">
                        <i class="bi bi-funnel-fill me-2"></i>Filter Pesanan
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Status Pesanan:</label>
                        <select name="status" class="form-select form-select-sm py-2">
                            <option value="semua" <?= $statusFilter === 'semua' ? 'selected' : '' ?>>Semua Status</option>
                            <option value="diproses" <?= $statusFilter === 'diproses' ? 'selected' : '' ?>>Sedang Diproses
                                (Aktif)</option>
                            <option value="menunggu" <?= $statusFilter === 'menunggu' ? 'selected' : '' ?>>Menunggu
                            </option>
                            <option value="dikonfirmasi" <?= $statusFilter === 'dikonfirmasi' ? 'selected' : '' ?>>
                                Dikonfirmasi</option>
                            <option value="dijemput" <?= $statusFilter === 'dijemput' ? 'selected' : '' ?>>Sedang Dijemput
                            </option>
                            <option value="dicuci" <?= $statusFilter === 'dicuci' ? 'selected' : '' ?>>Sedang Dicuci
                            </option>
                            <option value="disetrika" <?= $statusFilter === 'disetrika' ? 'selected' : '' ?>>Sedang
                                Disetrika</option>
                            <option value="diantar" <?= $statusFilter === 'diantar' ? 'selected' : '' ?>>Sedang Diantar
                            </option>
                            <option value="selesai" <?= $statusFilter === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="dibatalkan" <?= $statusFilter === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan
                            </option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark small">Status Pembayaran:</label>
                        <select name="bayar" class="form-select form-select-sm py-2">
                            <option value="semua" <?= $bayarFilter === 'semua' ? 'selected' : '' ?>>Semua Pembayaran
                            </option>
                            <option value="lunas" <?= $bayarFilter === 'lunas' ? 'selected' : '' ?>>Sudah Lunas</option>
                            <option value="belum_lunas" <?= $bayarFilter === 'belum_lunas' ? 'selected' : '' ?>>Belum Lunas
                                / Menunggu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"
                    style="background: #f8f9fa; border-top: 1px solid #eee; display: flex; justify-content: space-between; padding: 12px 20px;">
                    <a href="<?= base_url('admin/pesanan') ?>" class="btn btn-sm btn-outline-danger rounded-3 px-3">
                        Reset Filter
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary rounded-3 px-3 fw-bold"
                        style="background: #033B59; border-color: #033B59;">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SCRIPT JS INTERAKTIF                                      -->
<!-- ═══════════════════════════════════════════════════════════ -->
<script>
    var currDetailData = null;

    function bukaModalDetail(data) {
        currDetailData = data;
        document.getElementById('detNamaPelanggan').textContent = data.nama_pelanggan;
        document.getElementById('detNoHpPelanggan').textContent = data.no_hp_pelanggan;
        document.getElementById('detKodePesanan').textContent = data.kode_pesanan;
        document.getElementById('detTanggal').textContent = data.tanggal_indo;
        document.getElementById('detLayananBerat').textContent = data.nama_layanan + ' • ' + data.berat + ' Kg';
        document.getElementById('detTotalHarga').textContent = 'Rp ' + data.total_harga;
        document.getElementById('detAlamatAmbil').textContent = data.alamat_ambil;
        document.getElementById('detAlamatAntar').textContent = data.alamat_antar;
        document.getElementById('detNamaKurir').textContent = data.nama_kurir;

        // Tombol WhatsApp
        var waPhone = (data.no_hp_pelanggan || '').replace(/[^0-9]/g, '');
        if (waPhone.startsWith('0')) waPhone = '62' + waPhone.substring(1);
        var btnWa = document.getElementById('detBtnWa');
        if (waPhone) {
            btnWa.href = 'https://wa.me/' + waPhone + '?text=Halo%20' + encodeURIComponent(data.nama_pelanggan) + ',%20mengenai%20pesanan%20' + encodeURIComponent(data.kode_pesanan) + '%20di%20DigiWash...';
            btnWa.style.display = 'inline-flex';
        } else {
            btnWa.style.display = 'none';
        }

        // Status Pembayaran Badge
        var stBayarEl = document.getElementById('detStatusBayarBadge');
        if (data.status_bayar === 'lunas') {
            stBayarEl.innerHTML = '<span class="badge bg-success rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i> Lunas</span>';
        } else {
            stBayarEl.innerHTML = '<span class="badge bg-warning text-dark rounded-pill px-2 py-1"><i class="bi bi-hourglass-split me-1"></i> Belum Lunas</span>';
        }

        // Status Pesanan Badge
        var stPsnEl = document.getElementById('detStatusPesananBadge');
        var labelSt = (data.status_pesanan || '').toUpperCase();
        stPsnEl.innerHTML = '<span class="badge bg-secondary rounded-pill px-2 py-1">' + labelSt + '</span>';

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

    // Buka Modal Proses / Update Status
    function bukaModalProses(idPsn, kodePsn, namaPlg, statusSaatIni, idKurirSaatIni) {
        var form = document.getElementById('formProsesPesanan');
        form.action = '<?= base_url('admin/pesanan/update-status/') ?>' + idPsn;

        document.getElementById('modalProsesKode').textContent = kodePsn;
        document.getElementById('modalProsesPelanggan').textContent = 'Pelanggan: ' + namaPlg;

        var selectStatus = document.getElementById('selectStatusPesanan');
        selectStatus.innerHTML = '';
        var helpText = document.getElementById('helpTextStatusPesanan');
        var btnSubmit = form.querySelector('button[type="submit"]');

        if (statusSaatIni === 'menunggu' || statusSaatIni === 'dikonfirmasi') {
            selectStatus.innerHTML = '<option value="dikonfirmasi" selected>Menunggu Penjemputan Kurir (Terkunci)</option><option value="dibatalkan">Batalkan Pesanan</option>';
            if (helpText) helpText.textContent = 'Cucian belum tiba di outlet. Admin baru dapat memproses cuci setelah kurir selesai menjemput dan mengantar cucian ke outlet (status Dijemput).';
            if (btnSubmit) btnSubmit.disabled = false;
        } else if (statusSaatIni === 'dijemput') {
            selectStatus.innerHTML = '<option value="dijemput" selected>Cucian Tiba di Outlet</option><option value="dicuci">Mulai Proses Cuci</option><option value="dibatalkan">Batalkan Pesanan</option>';
            if (helpText) helpText.textContent = 'Cucian telah tiba di outlet. Anda dapat mulai memproses pencucian.';
            if (btnSubmit) btnSubmit.disabled = false;
        } else if (statusSaatIni === 'dicuci') {
            selectStatus.innerHTML = '<option value="dicuci" selected>Sedang Proses Cuci</option><option value="disetrika">Lanjut Proses Setrika (Siap Diantar)</option><option value="dibatalkan">Batalkan Pesanan</option>';
            if (helpText) helpText.textContent = 'Cucian sedang dicuci. Pilih Proses Setrika jika pencucian telah selesai.';
            if (btnSubmit) btnSubmit.disabled = false;
        } else if (statusSaatIni === 'disetrika') {
            selectStatus.innerHTML = '<option value="disetrika" selected>Proses Setrika Selesai (Siap Diantar)</option><option value="diantar">Serahkan ke Kurir (Sedang Diantar)</option><option value="dibatalkan">Batalkan Pesanan</option>';
            if (helpText) helpText.textContent = 'Pakaian telah disetrika rapi dan siap diantarkan oleh kurir.';
            if (btnSubmit) btnSubmit.disabled = false;
        } else if (statusSaatIni === 'diantar') {
            selectStatus.innerHTML = '<option value="diantar" selected>Sedang Diantar Kurir</option>';
            if (helpText) helpText.textContent = 'Pakaian sedang diantar oleh Kurir ke pelanggan. Status Selesai hanya dapat diselesaikan oleh Kurir setelah pakaian diterima pelanggan.';
            if (btnSubmit) btnSubmit.disabled = true;
        } else {
            selectStatus.innerHTML = '<option value="' + statusSaatIni + '" selected>' + statusSaatIni.toUpperCase() + '</option>';
            if (helpText) helpText.textContent = 'Status pesanan telah final.';
            if (btnSubmit) btnSubmit.disabled = true;
        }

        var selectKurir = document.getElementById('selectKurirPesanan');
        selectKurir.value = idKurirSaatIni || '';

        var bsModal = new bootstrap.Modal(document.getElementById('modalProsesPesanan'));
        bsModal.show();
    }

    // Tombol di dalam modal detail untuk membuka modal proses
    document.getElementById('btnAlihKeProses').addEventListener('click', function () {
        var modalDetailEl = document.getElementById('modalDetailPesanan');
        var modalDetail = bootstrap.Modal.getInstance(modalDetailEl);
        if (modalDetail) {
            modalDetail.hide();
        }
        if (currDetailData) {
            setTimeout(function () {
                bukaModalProses(
                    currDetailData.id_pesanan,
                    currDetailData.kode_pesanan,
                    currDetailData.nama_pelanggan,
                    currDetailData.status_pesanan,
                    currDetailData.id_kurir
                );
            }, 300);
        }
    });
</script>

<?= $this->endSection() ?>