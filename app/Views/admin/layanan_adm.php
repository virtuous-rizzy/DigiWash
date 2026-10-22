<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e             = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$daftarLayanan = (array) ($daftarLayanan ?? []);
$statusFilter  = (string) ($statusFilter ?? 'semua');
$keyword       = (string) ($keyword ?? '');
$ringkasan     = (array) ($ringkasan ?? ['semua' => 0, 'aktif' => 0, 'nonaktif' => 0]);

$popularIcons = [
    ['icon' => 'bi-basket2-fill',          'label' => 'Keranjang'],
    ['icon' => 'bi-lightning-charge-fill', 'label' => 'Kilat/Express'],
    ['icon' => 'bi-tag-fill',              'label' => 'Satuan'],
    ['icon' => 'bi-layers-fill',           'label' => 'Bed Cover'],
    ['icon' => 'bi-stars',                 'label' => 'Premium'],
    ['icon' => 'bi-water',                 'label' => 'Cuci Basah'],
    ['icon' => 'bi-fire',                  'label' => 'Setrika Panas'],
    ['icon' => 'bi-box-seam',              'label' => 'Paket Hemat'],
    ['icon' => 'bi-shield-check',          'label' => 'Higienis'],
    ['icon' => 'bi-clock-history',         'label' => 'Cepat/Reguler'],
];
?>

<style>
    .layanan-page-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .layanan-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .layanan-title-group h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #033B59;
    }

    .btn-tambah-layanan {
        background: #033B59;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(3, 59, 89, 0.18);
        cursor: pointer;
    }

    .btn-tambah-layanan:hover {
        background: #02273b;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(3, 59, 89, 0.25);
    }

    /* Filter & Search Bar */
    .layanan-filter-bar {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 22px;
    }

    .filter-chips {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 4px;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 30px;
        background: #f1f5f9;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
    }

    .filter-chip.active {
        background: #033B59;
        color: #ffffff;
        border-color: #033B59;
    }

    .filter-chip .chip-count {
        background: rgba(0, 0, 0, 0.08);
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 11px;
    }

    .filter-chip.active .chip-count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    .search-box {
        position: relative;
        width: 100%;
    }

    .search-box input {
        width: 100%;
        padding: 10px 16px 10px 42px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-box input:focus {
        border-color: #033B59;
        box-shadow: 0 0 0 3px rgba(3, 59, 89, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }

    /* Kartu Layanan */
    .layanan-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 30px;
    }

    @media (min-width: 768px) {
        .layanan-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .layanan-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        position: relative;
        overflow: hidden;
    }

    .layanan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(3, 59, 89, 0.08);
        border-color: #cbd5e1;
    }

    .layanan-card.is-nonaktif {
        background: #f8fafc;
        border-style: dashed;
        opacity: 0.88;
    }

    .layanan-card-header {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 12px;
    }

    .layanan-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #033B59;
        font-size: 26px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(3, 59, 89, 0.1);
    }

    .layanan-card.is-nonaktif .layanan-icon-box {
        background: #e2e8f0;
        color: #64748b;
    }

    .layanan-meta {
        flex: 1;
        min-width: 0;
    }

    .layanan-name-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
    }

    .layanan-name {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
        word-break: break-word;
    }

    .badge-status {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 20px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-status.aktif {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-status.nonaktif {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-jenis {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        display: inline-block;
        margin-top: 2px;
    }

    .layanan-desc {
        font-size: 12.5px;
        color: #64748b;
        margin: 8px 0 14px;
        line-height: 1.5;
        min-height: 38px;
    }

    /* Rincian Tarif & Estimasi */
    .layanan-pricing-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 14px;
        margin-bottom: 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px 18px;
        font-size: 12px;
    }

    .pricing-item {
        display: flex;
        flex-direction: column;
    }

    .pricing-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 500;
    }

    .pricing-val {
        color: #0f172a;
        font-size: 13.5px;
        font-weight: 700;
    }

    /* Card Actions */
    .layanan-card-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
    }

    .btn-act {
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .btn-act-edit {
        background: #f0f9ff;
        color: #0369a1;
        border-color: #bae6fd;
    }

    .btn-act-edit:hover {
        background: #033B59;
        color: #ffffff;
        border-color: #033B59;
    }

    .btn-act-toggle {
        background: #f8fafc;
        color: #475569;
        border-color: #cbd5e1;
    }

    .btn-act-toggle:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn-act-toggle.btn-turn-off {
        color: #b91c1c;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .btn-act-toggle.btn-turn-off:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .btn-act-toggle.btn-turn-on {
        color: #15803d;
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .btn-act-toggle.btn-turn-on:hover {
        background: #dcfce7;
        border-color: #86efac;
    }

    .btn-act-delete {
        background: #fff;
        color: #dc2626;
        border-color: #fee2e2;
    }

    .btn-act-delete:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }

    /* Modal Styling */
    .icon-preview-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #033B59;
        border: 1px solid #cbd5e1;
        flex-shrink: 0;
    }

    .popular-icon-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 11.5px;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-right: 4px;
        margin-bottom: 6px;
    }

    .popular-icon-pill:hover,
    .popular-icon-pill.selected {
        background: #033B59;
        color: #ffffff;
        border-color: #033B59;
    }
</style>

<!-- Header & Navigasi -->
<div class="layanan-page-head">
    <div class="layanan-title-group">
        <a href="<?= base_url('admin/dashboard') ?>" class="kembali" title="Kembali ke Beranda">&larr;</a>
        <div>
            <h3>Kelola Layanan Laundry</h3>
            <p class="text-muted small mb-0">Kelola tarif, jenis, dan status aktif layanan DigiWash</p>
        </div>
    </div>
    <button type="button" class="btn-tambah-layanan" data-bs-toggle="modal" data-bs-target="#modalLayanan" onclick="siapkanModalTambah()">
        <i class="bi bi-plus-circle-fill"></i> Tambah Layanan Baru
    </button>
</div>

<!-- Filter Chips & Search Bar -->
<div class="layanan-filter-bar">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="filter-chips">
            <a href="<?= base_url('admin/kelola-layanan?status=semua' . ($keyword !== '' ? '&q=' . rawurlencode($keyword) : '')) ?>"
                class="filter-chip <?= $statusFilter === 'semua' ? 'active' : '' ?>">
                Semua <span class="chip-count"><?= (int) ($ringkasan['semua'] ?? 0) ?></span>
            </a>
            <a href="<?= base_url('admin/kelola-layanan?status=aktif' . ($keyword !== '' ? '&q=' . rawurlencode($keyword) : '')) ?>"
                class="filter-chip <?= $statusFilter === 'aktif' ? 'active' : '' ?>">
                <i class="bi bi-check-circle-fill text-success" style="font-size:11px;"></i> Aktif
                <span class="chip-count"><?= (int) ($ringkasan['aktif'] ?? 0) ?></span>
            </a>
            <a href="<?= base_url('admin/kelola-layanan?status=nonaktif' . ($keyword !== '' ? '&q=' . rawurlencode($keyword) : '')) ?>"
                class="filter-chip <?= $statusFilter === 'nonaktif' ? 'active' : '' ?>">
                <i class="bi bi-slash-circle-fill text-danger" style="font-size:11px;"></i> Nonaktif
                <span class="chip-count"><?= (int) ($ringkasan['nonaktif'] ?? 0) ?></span>
            </a>
        </div>

        <form method="get" action="<?= base_url('admin/kelola-layanan') ?>" class="search-box" style="max-width: 320px;">
            <input type="hidden" name="status" value="<?= $e($statusFilter) ?>">
            <i class="bi bi-search"></i>
            <input type="search" name="q" value="<?= $e($keyword) ?>" placeholder="Cari nama atau keterangan...">
        </form>
    </div>
</div>

<!-- Daftar Layanan -->
<?php if (empty($daftarLayanan)): ?>
    <div class="panel kosong" style="text-align: center; padding: 48px 20px; background: #fff; border: 1.5px dashed #cbd5e1; border-radius: 18px; margin-bottom: 24px;">
        <i class="bi bi-inbox text-muted" style="font-size: 42px;"></i>
        <h6 class="fw-bold text-dark mt-3 mb-1">Layanan Tidak Ditemukan</h6>
        <p class="text-muted small mb-3">Tidak ada data layanan yang sesuai dengan filter atau kata kunci pencarian.</p>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#modalLayanan" onclick="siapkanModalTambah()">
            <i class="bi bi-plus-circle me-1"></i> Tambah Layanan Sekarang
        </button>
    </div>
<?php else: ?>
    <div class="layanan-grid">
        <?php foreach ($daftarLayanan as $ly): ?>
            <?php
            $idLy        = (int) $ly['id_layanan'];
            $namaLy      = (string) $ly['nama_layanan'];
            $jenisLy     = strtolower((string) ($ly['jenis_layanan'] ?? 'kiloan'));
            $hargaPerKg  = (float) ($ly['harga_per_kg'] ?? 0);
            $hargaFlat   = (float) ($ly['harga_flat'] ?? 0);
            $estimasi    = (int) ($ly['estimasi_hari'] ?? 1);
            $desc        = (string) ($ly['deskripsi'] ?? '-');
            $ikon        = (string) ($ly['ikon'] ?? 'bi-basket2-fill');
            $isActive    = (int) ($ly['is_active'] ?? 1) === 1;
            $hasPesanan  = ! empty($ly['punya_pesanan']);
            ?>
            <div class="layanan-card <?= ! $isActive ? 'is-nonaktif' : '' ?>">
                <div>
                    <div class="layanan-card-header">
                        <div class="layanan-icon-box">
                            <i class="bi <?= $e($ikon) ?>"></i>
                        </div>
                        <div class="layanan-meta">
                            <div class="layanan-name-row">
                                <h4 class="layanan-name"><?= $e($namaLy) ?></h4>
                                <?php if ($isActive): ?>
                                    <span class="badge-status aktif"><i class="bi bi-dot"></i> Aktif</span>
                                <?php else: ?>
                                    <span class="badge-status nonaktif"><i class="bi bi-slash-circle"></i> Nonaktif</span>
                                <?php endif; ?>
                            </div>
                            <span class="badge-jenis">
                                <?= $jenisLy === 'satuan' ? '<i class="bi bi-tag me-1"></i>Satuan' : '<i class="bi bi-box me-1"></i>Kiloan' ?>
                            </span>
                        </div>
                    </div>

                    <p class="layanan-desc"><?= $e($desc) ?></p>

                    <div class="layanan-pricing-box">
                        <div class="pricing-item">
                            <span class="pricing-label">Harga / Kg</span>
                            <span class="pricing-val">
                                <?= $hargaPerKg > 0 ? 'Rp ' . number_format($hargaPerKg, 0, ',', '.') : '-' ?>
                            </span>
                        </div>
                        <div class="pricing-item">
                            <span class="pricing-label">Tarif Flat</span>
                            <span class="pricing-val">
                                <?= $hargaFlat > 0 ? 'Rp ' . number_format($hargaFlat, 0, ',', '.') : '-' ?>
                            </span>
                        </div>
                        <div class="pricing-item">
                            <span class="pricing-label">Estimasi Pengerjaan</span>
                            <span class="pricing-val"><?= $estimasi ?> Hari kerja</span>
                        </div>
                    </div>
                </div>

                <div class="layanan-card-actions">
                    <!-- Tombol Edit -->
                    <button type="button" class="btn-act btn-act-edit"
                        data-bs-toggle="modal" data-bs-target="#modalLayanan"
                        data-id="<?= $idLy ?>"
                        data-nama="<?= $e($namaLy) ?>"
                        data-jenis="<?= $e($jenisLy) ?>"
                        data-kilo="<?= $hargaPerKg ?>"
                        data-flat="<?= $hargaFlat ?>"
                        data-estimasi="<?= $estimasi ?>"
                        data-desc="<?= $e($desc) ?>"
                        data-ikon="<?= $e($ikon) ?>"
                        data-active="<?= $isActive ? '1' : '0' ?>"
                        onclick="siapkanModalEdit(this)">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>

                    <!-- Tombol Aktif / Nonaktifkan -->
                    <form action="<?= base_url('admin/kelola-layanan/status/' . $idLy) ?>" method="post" style="display:inline;"
                        data-konfirmasi="<?= $isActive ? 'Nonaktifkan layanan ini? Layanan tidak akan muncul pada menu Pilih Layanan pelanggan.' : 'Aktifkan kembali layanan ini agar dapat dipilih pelanggan?' ?>">
                        <?= csrf_field() ?>
                        <?php if ($isActive): ?>
                            <button type="submit" class="btn-act btn-act-toggle btn-turn-off" title="Nonaktifkan layanan">
                                <i class="bi bi-slash-circle"></i> Nonaktifkan
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn-act btn-act-toggle btn-turn-on" title="Aktifkan layanan">
                                <i class="bi bi-check-circle"></i> Aktifkan
                            </button>
                        <?php endif; ?>
                    </form>

                    <!-- Tombol Hapus -->
                    <form action="<?= base_url('admin/kelola-layanan/hapus/' . $idLy) ?>" method="post" style="display:inline;"
                        data-konfirmasi="<?= $hasPesanan ? 'Layanan ini memiliki riwayat pesanan. Sistem akan menonaktifkannya alih-alih menghapus permanen agar data pesanan pelanggan tetap utuh. Lanjutkan?' : 'Apakah Anda yakin ingin menghapus permanen layanan ini?' ?>"
                        data-danger="1"
                        data-judul="Hapus Layanan">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-act btn-act-delete" title="Hapus layanan">
                            <i class="bi bi-trash3"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- ══ MODAL TAMBAH & EDIT LAYANAN ══ -->
<div class="modal fade" id="modalLayanan" tabindex="-1" aria-labelledby="modalLayananTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.18);">
            <div class="modal-header" style="background: #033B59; color: #ffffff; padding: 16px 22px;">
                <h6 class="modal-title fw-bold" id="modalLayananTitle" style="margin: 0; font-size: 16px;">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Layanan Baru
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            
            <form action="<?= base_url('admin/kelola-layanan/simpan') ?>" method="post" id="formLayanan">
                <?= csrf_field() ?>
                <input type="hidden" name="id_layanan" id="inputLayananId" value="">

                <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                    
                    <!-- Nama Layanan -->
                    <div class="mb-3">
                        <label for="inputNamaLayanan" class="form-label fw-bold text-dark small mb-1">
                            Nama Layanan <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_layanan" id="inputNamaLayanan" class="form-control rounded-3" 
                               placeholder="Contoh: Laundry Reguler, Cuci Bed Cover" required maxlength="100">
                    </div>

                    <!-- Jenis Layanan -->
                    <div class="mb-3">
                        <label for="inputJenisLayanan" class="form-label fw-bold text-dark small mb-1">
                            Jenis Layanan <span class="text-danger">*</span>
                        </label>
                        <select name="jenis_layanan" id="inputJenisLayanan" class="form-select rounded-3" required>
                            <option value="kiloan">Kiloan (Dihitung per Kilogram)</option>
                            <option value="satuan">Satuan (Dihitung per Potong/Item)</option>
                        </select>
                    </div>

                    <!-- Harga Per Kg & Harga Flat -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="inputHargaKilo" class="form-label fw-bold text-dark small mb-1">
                                Harga per Kg (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small">Rp</span>
                                <input type="number" name="harga_per_kg" id="inputHargaKilo" class="form-control rounded-end-3" 
                                       min="0" step="500" placeholder="0" required value="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="inputHargaFlat" class="form-label fw-bold text-dark small mb-1">
                                Harga Flat (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small">Rp</span>
                                <input type="number" name="harga_flat" id="inputHargaFlat" class="form-control rounded-end-3" 
                                       min="0" step="500" placeholder="0" required value="0">
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted" style="font-size: 11px;">
                                <i class="bi bi-info-circle me-1"></i>Harga flat digunakan untuk tarif dasar atau minimum paket (misal cuci bed cover Rp35.000 atau satuan Rp5.000).
                            </small>
                        </div>
                    </div>

                    <!-- Estimasi Hari -->
                    <div class="mb-3">
                        <label for="inputEstimasi" class="form-label fw-bold text-dark small mb-1">
                            Estimasi Pengerjaan (Hari) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="estimasi_hari" id="inputEstimasi" class="form-control rounded-start-3" 
                                   min="1" max="30" value="3" required>
                            <span class="input-group-text bg-light text-muted small">Hari</span>
                        </div>
                    </div>

                    <!-- Kode Ikon Bootstrap -->
                    <div class="mb-3">
                        <label for="inputIkon" class="form-label fw-bold text-dark small mb-1">
                            Kode Ikon Bootstrap <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="icon-preview-box" id="iconPreviewBox">
                                <i class="bi bi-basket2-fill" id="iconPreviewTarget"></i>
                            </div>
                            <input type="text" name="ikon" id="inputIkon" class="form-control rounded-3" 
                                   placeholder="bi-basket2-fill" required value="bi-basket2-fill" oninput="updateIconPreview(this.value)">
                        </div>

                        <!-- Rekomendasi Icon -->
                        <div class="mt-2">
                            <small class="d-block text-muted mb-1" style="font-size: 11px; font-weight: 600;">
                                Rekomendasi Ikon Laundry (Klik untuk pilih):
                            </small>
                            <div class="d-flex flex-wrap">
                                <?php foreach ($popularIcons as $pi): ?>
                                    <span class="popular-icon-pill" onclick="pilihIcon('<?= $pi['icon'] ?>')">
                                        <i class="bi <?= $pi['icon'] ?>"></i> <?= $e($pi['label']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Layanan -->
                    <div class="mb-3">
                        <label for="inputDeskripsi" class="form-label fw-bold text-dark small mb-1">
                            Deskripsi Layanan
                        </label>
                        <textarea name="deskripsi" id="inputDeskripsi" class="form-control rounded-3" rows="3" 
                                  placeholder="Tuliskan keterangan detail layanan yang akan dibaca pelanggan..."></textarea>
                    </div>

                    <!-- Status Aktif -->
                    <div class="form-check form-switch p-0 pt-2 d-flex align-items-center justify-content-between">
                        <div>
                            <label class="form-check-label fw-bold text-dark small" for="checkIsActive">
                                Status Layanan Aktif
                            </label>
                            <div class="text-muted" style="font-size: 11px;">
                                Layanan aktif akan langsung tampil pada menu Pilih Layanan pelanggan.
                            </div>
                        </div>
                        <input class="form-check-input ms-0" type="checkbox" role="switch" id="checkIsActive" name="is_active" value="1" checked style="width: 2.4em; height: 1.3em;">
                    </div>

                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 22px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-3 px-4 py-2 fw-bold" style="background: #033B59; border-color: #033B59;">
                        <i class="bi bi-save me-1"></i> Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateIconPreview(iconClass) {
        var clean = (iconClass || '').trim().replace(/^bi\s+/, '');
        if (clean !== '' && !clean.startsWith('bi-')) {
            clean = 'bi-' + clean;
        }
        var target = document.getElementById('iconPreviewTarget');
        if (target) {
            target.className = 'bi ' + (clean || 'bi-question-circle');
        }
    }

    function pilihIcon(iconName) {
        var input = document.getElementById('inputIkon');
        if (input) {
            input.value = iconName;
            updateIconPreview(iconName);
        }
    }

    function bukaModalLayananInstance() {
        var modalEl = document.getElementById('modalLayanan');
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var inst = bootstrap.Modal.getOrCreateInstance(modalEl);
            if (inst) inst.show();
        }
    }

    function siapkanModalTambah() {
        var form = document.getElementById('formLayanan');
        if (form) form.reset();
        var idInput = document.getElementById('inputLayananId');
        if (idInput) idInput.value = '';
        var title = document.getElementById('modalLayananTitle');
        if (title) title.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambah Layanan Baru';
        
        var nama = document.getElementById('inputNamaLayanan');
        if (nama) nama.value = '';
        var jenis = document.getElementById('inputJenisLayanan');
        if (jenis) jenis.value = 'kiloan';
        var kilo = document.getElementById('inputHargaKilo');
        if (kilo) kilo.value = '0';
        var flat = document.getElementById('inputHargaFlat');
        if (flat) flat.value = '0';
        var estimasi = document.getElementById('inputEstimasi');
        if (estimasi) estimasi.value = '3';
        var ikon = document.getElementById('inputIkon');
        if (ikon) ikon.value = 'bi-basket2-fill';
        var desc = document.getElementById('inputDeskripsi');
        if (desc) desc.value = '';
        var active = document.getElementById('checkIsActive');
        if (active) active.checked = true;

        updateIconPreview('bi-basket2-fill');
        bukaModalLayananInstance();
    }

    function siapkanModalEdit(btn) {
        var id       = btn.getAttribute('data-id') || '';
        var nama     = btn.getAttribute('data-nama') || '';
        var jenis    = btn.getAttribute('data-jenis') || 'kiloan';
        var kilo     = btn.getAttribute('data-kilo') || '0';
        var flat     = btn.getAttribute('data-flat') || '0';
        var estimasi = btn.getAttribute('data-estimasi') || '1';
        var desc     = btn.getAttribute('data-desc') || '';
        var ikon     = btn.getAttribute('data-ikon') || 'bi-basket2-fill';
        var active   = btn.getAttribute('data-active') === '1';

        var idInput = document.getElementById('inputLayananId');
        if (idInput) idInput.value = id;
        var title = document.getElementById('modalLayananTitle');
        if (title) title.innerHTML = '<i class="bi bi-pencil-square me-2"></i>Edit Data Layanan';
        
        var elNama = document.getElementById('inputNamaLayanan');
        if (elNama) elNama.value = nama;
        var elJenis = document.getElementById('inputJenisLayanan');
        if (elJenis) elJenis.value = jenis;
        var elKilo = document.getElementById('inputHargaKilo');
        if (elKilo) elKilo.value = kilo;
        var elFlat = document.getElementById('inputHargaFlat');
        if (elFlat) elFlat.value = flat;
        var elEstimasi = document.getElementById('inputEstimasi');
        if (elEstimasi) elEstimasi.value = estimasi;
        var elDesc = document.getElementById('inputDeskripsi');
        if (elDesc) elDesc.value = desc;
        var elIkon = document.getElementById('inputIkon');
        if (elIkon) elIkon.value = ikon;
        var elActive = document.getElementById('checkIsActive');
        if (elActive) elActive.checked = active;

        updateIconPreview(ikon);
        bukaModalLayananInstance();
    }
</script>
<?= $this->endSection() ?>
