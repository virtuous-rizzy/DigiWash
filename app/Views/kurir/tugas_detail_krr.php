<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');

$isJemput = ($tipe === 'penjemputan');
$namaPlg  = (string) ($tugas['nama_pelanggan'] ?? 'Pelanggan');
$noHpPlg  = (string) ($tugas['no_hp_pelanggan'] ?? '');
$catatan  = (string) ($tugas['catatan'] ?? '');
$layanan  = (string) ($tugas['nama_layanan'] ?? 'Laundry Reguler');
$berat    = (float)  ($tugas['berat'] ?? 1.0);
$total    = (float)  ($tugas['total_bayar'] ?? 0);
$metode   = (string) ($tugas['metode'] ?? 'Transfer');
$fotoPlgUrl = url_foto_profil($tugas['foto_pelanggan'] ?? null);
$inisialPlg = strtoupper(substr($namaPlg, 0, 1));

// Format nomor WhatsApp (hapus karakter non-digit dan ganti awalan 0 ke 62)
$noHpClean = preg_replace('/[^0-9]/', '', $noHpPlg);
if (str_starts_with($noHpClean, '0')) {
    $noHpClean = '62' . substr($noHpClean, 1);
}
$waUrl = ! empty($noHpClean) ? 'https://wa.me/' . $noHpClean : '#';
$telUrl = ! empty($noHpPlg) ? 'tel:' . $noHpPlg : '#';

// URL Google Maps Search
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($alamat);
?>

<style>
/* ── Detail Tugas Kurir ── */
.detail-header {
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
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #033B59;
    text-decoration: none;
    font-size: 18px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    transition: all 0.2s ease;
}
.btn-back-krr:hover {
    background: #f1f5f9;
    color: #033B59;
    transform: translateX(-2px);
}
.detail-title {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size: 18px;
    font-weight: 700;
    color: #033B59;
    margin: 0;
    white-space: nowrap;
}

/* ── Kartu Header Tugas ── */
.task-header-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 14px rgba(0,0,0,0.04);
}
.task-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.badge-tipe-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.badge-tipe-pill.badge-jemput {
    background: #e0f2fe;
    color: #0284c7;
    border: 1px solid #bae6fd;
}
.badge-tipe-pill.badge-antar {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
.task-code-lg {
    font-size: 17px;
    font-weight: 800;
    color: #033B59;
    letter-spacing: 0.5px;
}
.task-header-status {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
}
.task-header-status strong {
    color: #033B59;
    font-weight: 700;
}

/* ── Section Card Umum ── */
.detail-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 14px rgba(0,0,0,0.04);
}
.card-sec-title {
    font-size: 14px;
    font-weight: 700;
    color: #033B59;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
}
.card-sec-title svg {
    color: #033B59;
}

/* Info Pelanggan */
.pelanggan-profile-row {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 16px;
}
.pelanggan-avatar-box {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #e0f2fe;
    border: 2px solid #bae6fd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 800;
    color: #033B59;
    flex-shrink: 0;
    overflow: hidden;
    box-shadow: 0 3px 8px rgba(3, 59, 89, 0.08);
}
.pelanggan-avatar-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.pelanggan-info-col {
    flex: 1;
    min-width: 0;
}
.pelanggan-name {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
}
.pelanggan-phone {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 0;
}

/* Quick Action Buttons (WA & Telp) */
.quick-action-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 14px;
}
.btn-action-wa {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #25D366;
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 600;
    padding: 10px 12px;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(37,211,102,0.25);
    transition: all 0.2s;
}
.btn-action-wa:hover {
    background: #1eb956;
    color: #ffffff;
    transform: translateY(-1px);
}
.btn-action-tel {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #033B59;
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 600;
    padding: 10px 12px;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(3,59,89,0.25);
    transition: all 0.2s;
}
.btn-action-tel:hover {
    background: #022c44;
    color: #ffffff;
    transform: translateY(-1px);
}

/* Alamat & Maps */
.address-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 12px 14px;
    border: 1px solid #e2e8f0;
    margin-bottom: 12px;
}
.address-text {
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    line-height: 1.5;
    margin-bottom: 10px;
}
.btn-open-maps {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e0f2fe;
    color: #0284c7;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 7px 14px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-open-maps:hover {
    background: #bae6fd;
    color: #0369a1;
}

/* Catatan Pelanggan */
.note-box {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 12px;
    color: #92400e;
    line-height: 1.5;
}

/* Rincian Cucian */
.rincian-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 13px;
    border-bottom: 1px dashed #e2e8f0;
}
.rincian-row:last-child {
    border-bottom: none;
}
.rincian-label {
    color: #64748b;
    font-weight: 500;
}
.rincian-val {
    font-weight: 700;
    color: #033B59;
}

/* Status Bayar Bebas COD */
.payment-lunas-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 12px;
    padding: 12px 16px;
    margin-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.payment-lunas-title {
    font-size: 13px;
    font-weight: 700;
    color: #15803d;
}
.payment-lunas-desc {
    font-size: 11px;
    color: #166534;
    margin-top: 2px;
}

.payment-warning-box {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 12px;
    padding: 12px 16px;
    margin-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.payment-warning-title {
    font-size: 13px;
    font-weight: 700;
    color: #b45309;
}
.payment-warning-desc {
    font-size: 11px;
    color: #92400e;
    margin-top: 2px;
}

.payment-danger-box {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 12px 16px;
    margin-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.payment-danger-title {
    font-size: 13px;
    font-weight: 700;
    color: #dc2626;
}
.payment-danger-desc {
    font-size: 11px;
    color: #b91c1c;
    margin-top: 2px;
}

/* ── Action Section Bawah ── */
.action-box {
    margin-top: 8px;
    padding-bottom: 24px;
}
.btn-action-primary {
    display: block;
    width: 100%;
    padding: 13px 18px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 12px;
    text-align: center;
    border: none;
    cursor: pointer;
    margin-bottom: 10px;
    transition: all 0.2s ease;
}
.btn-action-blue {
    background: #033B59;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(3,59,89,0.25);
}
.btn-action-blue:hover {
    background: #022c44;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(3,59,89,0.3);
}
.btn-action-green {
    background: #019D13;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(1,157,19,0.25);
}
.btn-action-green:hover {
    background: #018310;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(1,157,19,0.3);
}

.action-success-banner {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 12px;
    padding: 14px 18px;
    text-align: center;
    color: #15803d;
    font-weight: 600;
    font-size: 13px;
    line-height: 1.5;
}

.action-warning-banner {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 12px;
    padding: 14px 18px;
    text-align: center;
    color: #92400e;
    font-weight: 600;
    font-size: 13px;
    line-height: 1.5;
}

.action-danger-banner {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 14px 18px;
    text-align: center;
    color: #b91c1c;
    font-weight: 600;
    font-size: 13px;
    line-height: 1.5;
}
</style>

<!-- Header Detail Tugas -->
<div class="detail-header">
    <a href="<?= base_url('kurir/tugas') ?>" class="btn-back-krr" title="Kembali"><i class="bi bi-arrow-left"></i></a>
    <h1 class="detail-title">Detail Tugas</h1>
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

<!-- Kartu Header Tugas -->
<div class="task-header-card">
    <div class="task-header-top">
        <span class="badge-tipe-pill <?= $isJemput ? 'badge-jemput' : 'badge-antar' ?>">
            <?php if ($isJemput): ?>
                <i class="bi bi-box-seam-fill me-1"></i> PENJEMPUTAN
            <?php else: ?>
                <i class="bi bi-truck me-1"></i> PENGANTARAN
            <?php endif; ?>
        </span>
        <span class="task-code-lg"><?= $e($kode) ?></span>
    </div>
    <div class="task-header-status">
        Status Saat Ini: <strong><?= ($stPesanan === 'dikonfirmasi' && ($statusAntar ?? '') === 'proses_antar') ? 'Kurir Menuju Lokasi (OTW Jemput)' : esc(ucwords(str_replace('_', ' ', $stPesanan))) ?></strong>
    </div>
</div>

<!-- 1. Informasi Pelanggan & Alamat -->
<div class="detail-card">
    <div class="card-sec-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
        <span>Informasi Pelanggan</span>
    </div>

    <!-- Profil Pelanggan (Foto & Nama) -->
    <div class="pelanggan-profile-row">
        <div class="pelanggan-avatar-box" title="Foto Pelanggan: <?= $e($namaPlg) ?>">
            <?php if ($fotoPlgUrl): ?>
                <img src="<?= $e($fotoPlgUrl) ?>" alt="<?= $e($namaPlg) ?>">
            <?php else: ?>
                <span><?= $e($inisialPlg ?: 'P') ?></span>
            <?php endif; ?>
        </div>
        <div class="pelanggan-info-col">
            <div class="pelanggan-name"><?= $e($namaPlg) ?></div>
            <div class="pelanggan-phone"><i class="bi bi-telephone-fill me-1 text-muted"></i><?= $e($noHpPlg !== '' ? $noHpPlg : '-') ?></div>
        </div>
    </div>

    <!-- Quick Action WA & Telp -->
    <div class="quick-action-row">
        <a href="<?= $e($waUrl) ?>" target="_blank" class="btn-action-wa">
            <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
        </a>
        <a href="<?= $e($telUrl) ?>" class="btn-action-tel">
            <i class="bi bi-telephone-fill me-1"></i> Telepon
        </a>
    </div>

    <!-- Alamat Lengkap & Buka di Google Maps -->
    <div class="address-box">
        <div class="address-text">
            <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= $e($alamat) ?>
        </div>
        <a href="<?= $e($mapsUrl) ?>" target="_blank" class="btn-open-maps">
            <i class="bi bi-map-fill me-1"></i> Buka di Google Maps &gt;
        </a>
    </div>

    <!-- Catatan Khusus Pelanggan -->
    <?php if ($catatan !== ''): ?>
        <div class="note-box">
            <strong>Catatan Pelanggan:</strong><br>
            <?= $e($catatan) ?>
        </div>
    <?php endif; ?>
</div>

<!-- 2. Rincian Cucian & Pembayaran (Tanpa COD) -->
<div class="detail-card">
    <div class="card-sec-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <path d="M16 10a4 4 0 0 1-8 0"></path>
        </svg>
        <span>Rincian Cucian</span>
    </div>

    <div class="rincian-row">
        <span class="rincian-label">Layanan</span>
        <span class="rincian-val"><?= $e($layanan) ?></span>
    </div>
    <div class="rincian-row">
        <span class="rincian-label">Estimasi Berat</span>
        <span class="rincian-val"><?= $berat ?> KG</span>
    </div>
    <div class="rincian-row">
        <span class="rincian-label">Metode Pembayaran</span>
        <span class="rincian-val"><?= $e($metode) ?></span>
    </div>

    <!-- Status Bayar & Validasi Transaksi (Bebas COD) -->
    <?php if ($isLunas): ?>
        <div class="payment-lunas-box">
            <div>
                <div class="payment-lunas-title"><i class="bi bi-check-circle-fill me-1"></i> SUDAH LUNAS</div>
                <div class="payment-lunas-desc">Pembayaran diverifikasi Admin.</div>
            </div>
            <div class="fw-bold fs-6 text-success text-end">
                Rp <?= number_format($total, 0, ',', '.') ?>
            </div>
        </div>
    <?php elseif ($isDibatalkan): ?>
        <div class="payment-danger-box">
            <div>
                <div class="payment-danger-title"><i class="bi bi-x-circle-fill me-1"></i> PESANAN DIBATALKAN / DITOLAK</div>
                <div class="payment-danger-desc">Pesanan ini dibatalkan atau bukti transfer ditolak oleh Admin.</div>
            </div>
            <div class="fw-bold fs-6 text-danger text-end">
                Rp <?= number_format($total, 0, ',', '.') ?>
            </div>
        </div>
    <?php else: ?>
        <div class="payment-warning-box">
            <div>
                <div class="payment-warning-title"><i class="bi bi-hourglass-split me-1"></i> MENUNGGU KONFIRMASI BAYAR</div>
                <div class="payment-warning-desc">Pembayaran belum diverifikasi Admin. Tugas belum dapat dijalankan.</div>
            </div>
            <div class="fw-bold fs-6 text-warning text-end">
                Rp <?= number_format($total, 0, ',', '.') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- 3. Aksi Bertahap Sesuai Alur Tugas -->
<div class="action-box">
    <?php if (! $bisaDiproses): ?>
        <?php if ($isDibatalkan): ?>
            <div class="action-danger-banner">
                <i class="bi bi-slash-circle-fill me-1 text-danger"></i> <strong>Tugas Dibatalkan</strong><br>
                Pesanan ini telah dibatalkan atau pembayaran ditolak oleh Admin. Kurir dilarang melakukan penjemputan atau pengantaran.
            </div>
        <?php else: ?>
            <div class="action-warning-banner">
                <i class="bi bi-lock-fill me-1 text-warning"></i> <strong>Menunggu Konfirmasi Pembayaran Admin</strong><br>
                Kurir belum dapat memproses tugas ini karena status pembayaran belum diverifikasi Lunas oleh Admin. Silakan tunggu konfirmasi Admin.
            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php if ($isJemput): ?>
            <!-- ── Alur Penjemputan ── -->
            <?php if (in_array($stPesanan, ['menunggu', 'dikonfirmasi'], true)): ?>
                <?php if (($statusAntar ?? '') === 'proses_antar'): ?>
                    <!-- Sedang OTW Menjemput -->
                    <div class="alert alert-info py-2 px-3 mb-3 fw-bold small rounded-3" style="background: #E0F2FE; border: 1px solid #7DD3FC; color: #0369A1;">
                        <i class="bi bi-geo-alt-fill me-1"></i> Anda sedang dalam perjalanan ke lokasi pelanggan untuk menjemput cucian.
                    </div>

                    <!-- Tahap 2: Konfirmasi Cucian Sudah Diambil -->
                    <form action="<?= base_url('kurir/tugas/update-aksi/' . $idPesanan) ?>" method="post"
                          data-konfirmasi="Pastikan cucian kotor sudah Anda terima dari pelanggan. Konfirmasi pengambilan?"
                          data-judul="Konfirmasi Cucian Diambil">
                        <?= csrf_field() ?>
                        <input type="hidden" name="aksi" value="jemput_selesai">
                        <button type="submit" class="btn-action-primary btn-action-green">
                            <i class="bi bi-check-circle-fill me-1"></i> Konfirmasi Cucian Sudah Diambil
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Tahap 1: Mulai Menjemput (OTW) -->
                    <form action="<?= base_url('kurir/tugas/update-aksi/' . $idPesanan) ?>" method="post"
                          data-konfirmasi="Mulai perjalanan untuk menjemput cucian pelanggan sekarang?"
                          data-judul="Mulai Penjemputan">
                        <?= csrf_field() ?>
                        <input type="hidden" name="aksi" value="otw_jemput">
                        <button type="submit" class="btn-action-primary btn-action-blue">
                            <i class="bi bi-geo-alt-fill me-1"></i> Mulai Menjemput (Saya OTW ke Lokasi)
                        </button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <div class="action-success-banner">
                    <i class="bi bi-check-circle-fill me-1 text-success"></i> Cucian telah berhasil dijemput dan dibawa ke outlet laundry DigiWash.
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- ── Alur Pengantaran ── -->
            <?php if ($stPesanan === 'disetrika'): ?>
                <!-- Tahap 1: Mulai Mengantar Pakaian Bersih -->
                <form action="<?= base_url('kurir/tugas/update-aksi/' . $idPesanan) ?>" method="post"
                      data-konfirmasi="Mulai perjalanan mengantarkan pakaian bersih ke rumah pelanggan?"
                      data-judul="Mulai Pengantaran">
                    <?= csrf_field() ?>
                    <input type="hidden" name="aksi" value="otw_antar">
                    <button type="submit" class="btn-action-primary btn-action-blue">
                        <i class="bi bi-truck me-1"></i> Mulai Pengantaran (OTW ke Rumah Pelanggan)
                    </button>
                </form>
            <?php elseif ($stPesanan === 'diantar'): ?>
                <!-- Tahap 2: Selesaikan Pengantaran -->
                <form action="<?= base_url('kurir/tugas/update-aksi/' . $idPesanan) ?>" method="post"
                      data-konfirmasi="Pastikan cucian bersih telah diserahkan dan diterima oleh pelanggan. Selesaikan pesanan ini?"
                      data-judul="Selesaikan Pengantaran">
                    <?= csrf_field() ?>
                    <input type="hidden" name="aksi" value="antar_selesai">
                    <button type="submit" class="btn-action-primary btn-action-green">
                        <i class="bi bi-check2-circle me-1"></i> Selesaikan Pengantaran (Pakaian Diterima)
                    </button>
                </form>
            <?php elseif ($stPesanan === 'selesai'): ?>
                <div class="action-success-banner">
                    <i class="bi bi-check2-circle me-1 text-success"></i> Pesanan telah selesai diantar dan berhasil diterima oleh pelanggan.
                </div>
            <?php else: ?>
                <!-- Pesanan belum siap diantar (masih dicuci / disetrika) -->
                <form action="<?= base_url('kurir/tugas/update-aksi/' . $idPesanan) ?>" method="post"
                      data-konfirmasi="Mulai perjalanan mengantarkan pakaian ke rumah pelanggan?"
                      data-judul="Mulai Pengantaran">
                    <?= csrf_field() ?>
                    <input type="hidden" name="aksi" value="otw_antar">
                    <button type="submit" class="btn-action-primary btn-action-blue">
                        <i class="bi bi-truck me-1"></i> Mulai Pengantaran (OTW ke Rumah Pelanggan)
                    </button>
                </form>
            <?php endif; ?>

        <?php endif; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
