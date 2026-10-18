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
.detail-title {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size: 18px;
    font-weight: 800;
    color: #000;
    margin: 0;
    white-space: nowrap;
}

/* ── Kartu Header Tugas ── */
.task-header-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 16px 18px;
    margin-bottom: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 3px 10px rgba(0,0,0,0.04);
}
.task-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}
.badge-tipe-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #93C5FD;
    color: #000;
    font-size: 11px;
    font-weight: 800;
    padding: 5px 12px;
    border-radius: 999px;
    text-transform: uppercase;
}
.task-code-lg {
    font-size: 18px;
    font-weight: 900;
    color: #000;
}
.task-header-status {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}
.task-header-status strong {
    color: #0f172a;
}

/* ── Section Card Umum ── */
.detail-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 16px 18px;
    margin-bottom: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 3px 10px rgba(0,0,0,0.04);
}
.card-sec-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
}

/* Info Pelanggan */
.pelanggan-name {
    font-size: 16px;
    font-weight: 800;
    color: #000;
    margin-bottom: 4px;
}
.pelanggan-phone {
    font-size: 13px;
    color: #475569;
    margin-bottom: 12px;
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
    font-size: 12px;
    font-weight: 700;
    padding: 9px 10px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-action-wa:hover {
    background: #1eb956;
    color: #ffffff;
}
.btn-action-tel {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #033B59;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 9px 10px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-action-tel:hover {
    background: #022b42;
    color: #ffffff;
}

/* Alamat & Maps */
.address-box {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 12px 14px;
    border: 1px solid #E2E8F0;
    margin-bottom: 10px;
}
.address-text {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.4;
    margin-bottom: 8px;
}
.btn-open-maps {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-open-maps:hover {
    background: #DBEAFE;
    color: #1E40AF;
}

/* Catatan Pelanggan */
.note-box {
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 12px;
    color: #92400E;
    line-height: 1.4;
}

/* Rincian Cucian */
.rincian-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    font-size: 13px;
    border-bottom: 1px dashed #E2E8F0;
}
.rincian-row:last-child {
    border-bottom: none;
}
.rincian-label {
    color: #64748B;
}
.rincian-val {
    font-weight: 700;
    color: #0F172A;
}

/* Status Bayar Bebas COD */
.payment-lunas-box {
    background: #DCFCE7;
    border: 1px solid #86EFAC;
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.payment-lunas-title {
    font-size: 13px;
    font-weight: 800;
    color: #15803d;
}
.payment-lunas-desc {
    font-size: 11px;
    color: #166534;
    margin-top: 1px;
}

.payment-warning-box {
    background: #FEF3C7;
    border: 1px solid #FCD34D;
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.payment-warning-title {
    font-size: 13px;
    font-weight: 800;
    color: #B45309;
}
.payment-warning-desc {
    font-size: 11px;
    color: #92400E;
    margin-top: 1px;
}

.payment-danger-box {
    background: #FEE2E2;
    border: 1px solid #FCA5A5;
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.payment-danger-title {
    font-size: 13px;
    font-weight: 800;
    color: #B91C1C;
}
.payment-danger-desc {
    font-size: 11px;
    color: #991B1B;
    margin-top: 1px;
}

/* ── Action Section Bawah ── */
.action-box {
    margin-top: 8px;
    padding-bottom: 24px;
}
.btn-action-primary {
    display: block;
    width: 100%;
    padding: 13px 16px;
    font-size: 14px;
    font-weight: 800;
    border-radius: 12px;
    text-align: center;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    margin-bottom: 10px;
    transition: all 0.2s;
}
.btn-action-blue {
    background: #0047AB;
    color: #ffffff;
}
.btn-action-blue:hover {
    background: #003685;
}
.btn-action-green {
    background: #019D13;
    color: #ffffff;
}
.btn-action-green:hover {
    background: #018310;
}

.action-success-banner {
    background: #DCFCE7;
    border: 1.5px solid #22C55E;
    border-radius: 12px;
    padding: 14px 16px;
    text-align: center;
    color: #15803d;
    font-weight: 700;
    font-size: 13px;
}

.action-warning-banner {
    background: #FFFBEB;
    border: 1.5px solid #F59E0B;
    border-radius: 12px;
    padding: 14px 16px;
    text-align: center;
    color: #92400E;
    font-weight: 700;
    font-size: 13px;
    line-height: 1.4;
}

.action-danger-banner {
    background: #FEF2F2;
    border: 1.5px solid #EF4444;
    border-radius: 12px;
    padding: 14px 16px;
    text-align: center;
    color: #991B1B;
    font-weight: 700;
    font-size: 13px;
    line-height: 1.4;
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
        <span class="badge-tipe-pill">
            <?php if ($isJemput): ?>
                <i class="bi bi-box-seam-fill me-1"></i> PENJEMPUTAN
            <?php else: ?>
                <i class="bi bi-truck me-1"></i> PENGANTARAN
            <?php endif; ?>
        </span>
        <span class="task-code-lg"><?= $e($kode) ?></span>
    </div>
    <div class="task-header-status">
        Status Saat Ini: <strong><?= esc(ucwords(str_replace('_', ' ', $stPesanan))) ?></strong>
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

    <div class="pelanggan-name"><?= $e($namaPlg) ?></div>
    <div class="pelanggan-phone"><?= $e($noHpPlg !== '' ? $noHpPlg : '-') ?></div>

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
                <div class="payment-lunas-desc">Pembayaran diverifikasi Admin. Bebas biaya COD.</div>
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
