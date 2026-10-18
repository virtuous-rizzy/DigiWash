<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiWash – Ringkasan Pesanan</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        body { background-color: #f0f2f5; }

        /* ─── Header ─── */
        .dw-topbar {
            background: #0d1b4b;
            color: #fff;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .dw-topbar .back-btn {
            color: #fff;
            font-size: 1.3rem;
            text-decoration: none;
            line-height: 1;
        }
        .dw-topbar h6 {
            margin: 0;
            font-weight: 700;
            font-size: 1.05rem;
        }

        /* ─── Icon clipboard besar ─── */
        .hero-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: #e8eaf6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            margin: 0 auto 8px;
            box-shadow: 0 4px 16px rgba(13,27,75,.12);
        }

        /* ─── Card Detail Pesanan ─── */
        .detail-card {
            background: #fff;
            border-radius: 16px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }
        .detail-card .item-row {
            display: flex;
            align-items: flex-start;
            padding: 12px 16px;
            gap: 12px;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-card .item-row:last-child { border-bottom: none; }
        .detail-card .item-icon {
            font-size: 1.1rem;
            color: #0d1b4b;
            margin-top: 2px;
            flex-shrink: 0;
            width: 22px;
            text-align: center;
        }
        .detail-card .item-label {
            font-size: .75rem;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin: 0 0 2px;
        }
        .detail-card .item-value {
            font-size: .92rem;
            color: #1a1a2e;
            font-weight: 600;
            margin: 0;
            word-break: break-word;
        }

        /* ─── Box Total Biaya ─── */
        .total-card {
            background: #cfe2ff;
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .total-card .label {
            font-weight: 700;
            font-size: .95rem;
            color: #052c65;
            margin: 0 0 2px;
        }
        .total-card .sub-label {
            font-size: .8rem;
            color: #0a58ca;
            margin: 0;
        }
        .total-card .total-amount {
            font-size: 1.25rem;
            font-weight: 900;
            color: #198754;
            margin: 0;
        }

        /* ─── Tombol aksi ─── */
        .btn-dw-primary {
            background: #0d1b4b;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            padding: 14px;
            width: 100%;
            transition: background .2s, transform .1s;
        }
        .btn-dw-primary:hover {
            background: #162566;
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-dw-primary:active { transform: translateY(0); }

        .btn-dw-batal {
            border: 2px solid #dc3545;
            border-radius: 12px;
            color: #dc3545;
            background: transparent;
            font-weight: 700;
            font-size: 1rem;
            padding: 13px;
            width: 100%;
            transition: background .2s, color .2s;
        }
        .btn-dw-batal:hover {
            background: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>

<?php
// Escape helper
$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

// Pastikan variabel selalu tersedia, fallback aman
$namaLayanan = (string) ($namaLayanan ?? '');
$hargaLabel  = (string) ($hargaLabel  ?? '');
$berat       = (int)    ($berat       ?? 0);
$hargaPerKg  = (int)    ($hargaPerKg  ?? 0);
$alamat      = (string) ($alamat      ?? '');
$tanggal     = (string) ($tanggal     ?? '');
$catatan     = (string) ($catatan     ?? '');
$totalBiaya  = (int)    ($totalBiaya  ?? ($berat * $hargaPerKg));

// Format tanggal ke bahasa Indonesia
$tanggalFormatted = '';
if ($tanggal !== '') {
    $ts = strtotime($tanggal);
    if ($ts !== false) {
        $namaBulan = [
            1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
            4  => 'April',    5  => 'Mei',       6  => 'Juni',
            7  => 'Juli',     8  => 'Agustus',   9  => 'September',
            10 => 'Oktober',  11 => 'November',  12 => 'Desember',
        ];
        $tanggalFormatted = date('j', $ts) . ' ' . $namaBulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}
?>

<!-- Top Bar -->
<div class="dw-topbar">
    <a href="<?= base_url('layanan/buat-pesanan?layanan=' . $e((string) ($idLayanan ?? 'reguler'))) ?>"
       class="back-btn" aria-label="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h6>Ringkasan Pesanan</h6>
</div>

<!-- Konten utama -->
<div class="container my-4" style="max-width: 480px;">

    <!-- Hero Icon -->
    <div class="text-center mb-4">
        <div class="hero-icon">
            <i class="bi bi-clipboard2-check text-primary"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Ringkasan Pesanan</h5>
        <p class="text-muted small mb-0">Periksa detail pesananmu sebelum dikonfirmasi.</p>
    </div>

    <!-- Card Detail Pesanan -->
    <div class="detail-card mb-4">

        <!-- Layanan -->
        <div class="item-row">
            <i class="bi bi-bag-check item-icon"></i>
            <div>
                <p class="item-label">Layanan</p>
                <p class="item-value"><?= $e($namaLayanan) ?></p>
                <small class="text-muted" style="font-size:.8rem"><?= $e($hargaLabel) ?></small>
            </div>
        </div>

        <!-- Berat -->
        <div class="item-row">
            <i class="bi bi-speedometer2 item-icon"></i>
            <div>
                <p class="item-label">Berat Cucian</p>
                <p class="item-value"><?= $berat ?> Kg</p>
            </div>
        </div>

        <!-- Alamat Penjemputan -->
        <div class="item-row">
            <i class="bi bi-geo-alt-fill item-icon"></i>
            <div>
                <p class="item-label">Alamat Penjemputan</p>
                <p class="item-value"><?= $e($alamat) ?></p>
            </div>
        </div>

        <!-- Tanggal Penjemputan -->
        <div class="item-row">
            <i class="bi bi-calendar3 item-icon"></i>
            <div>
                <p class="item-label">Tanggal Penjemputan</p>
                <p class="item-value">
                    <?= $tanggalFormatted !== '' ? $e($tanggalFormatted) : $e($tanggal) ?>
                </p>
            </div>
        </div>

        <!-- Catatan -->
        <?php if ($catatan !== ''): ?>
        <div class="item-row">
            <i class="bi bi-pencil-square item-icon"></i>
            <div>
                <p class="item-label">Catatan</p>
                <p class="item-value"><?= $e($catatan) ?></p>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Box Total Biaya -->
    <div class="total-card mb-4">
        <div>
            <p class="label">Total Biaya</p>
            <p class="sub-label"><?= $berat ?> Kg × Rp<?= number_format($hargaPerKg, 0, ',', '.') ?></p>
        </div>
        <p class="total-amount">Rp<?= number_format($totalBiaya, 0, ',', '.') ?></p>
    </div>

    <!-- Tombol Aksi -->
    <div class="d-flex flex-column gap-3">

        <!-- Konfirmasi Pesanan -->
        <form action="<?= base_url('layanan/konfirmasi') ?>" method="post" id="formKonfirmasi">
            <?= csrf_field() ?>
            <input type="hidden" name="id_layanan"   value="<?= $e((string) ($idLayanan ?? '')) ?>">
            <input type="hidden" name="nama_layanan" value="<?= $e($namaLayanan) ?>">
            <input type="hidden" name="harga_label"  value="<?= $e($hargaLabel) ?>">
            <input type="hidden" name="harga_per_kg" value="<?= $hargaPerKg ?>">
            <input type="hidden" name="berat"        value="<?= $berat ?>">
            <input type="hidden" name="alamat"       value="<?= $e($alamat) ?>">
            <input type="hidden" name="tanggal"      value="<?= $e($tanggal) ?>">
            <input type="hidden" name="catatan"      value="<?= $e($catatan) ?>">
            <input type="hidden" name="total_biaya"  value="<?= $totalBiaya ?>">

            <button type="submit" class="btn-dw-primary">
                <i class="bi bi-check-circle me-2"></i>Konfirmasi Pesanan
            </button>
        </form>

        <!-- Batalkan -->
        <button type="button"
                class="btn-dw-batal"
                onclick="konfirmasiBatal()">
            <i class="bi bi-x-circle me-2"></i>Batalkan
        </button>

    </div>

</div><!-- /container -->

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmHRNi4a8g8CRs+y5VqBjiqRJ1r4"
        crossorigin="anonymous"></script>

<script>
function konfirmasiBatal() {
    if (confirm('Yakin ingin membatalkan pesanan ini?')) {
        window.location.href = '<?= base_url('layanan') ?>';
    }
}
</script>

</body>
</html>
