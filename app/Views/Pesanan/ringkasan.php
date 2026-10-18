<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Pesanan - DigiWash</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --warna-utama: #033B59;
            --warna-latar: #F0EFEF;
            --warna-total: #019D13;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background: var(--warna-latar);
            font-family: 'Poppins', sans-serif;
        }

        .mobile-wrap {
            width: 100%;
            max-width: 402px;
            min-height: 100vh;
            padding-bottom: 32px;
            background: var(--warna-latar);
        }

        /* Header */
        .page-header {
            position: relative;
            display: flex;
            align-items: center;
            padding: 54px 18px 20px;
        }

        .btn-back {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            text-decoration: none;
            font-size: 22px;
            flex-shrink: 0;
        }

        .page-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            margin: 0;
            color: #000;
            font-size: 20px;
            font-weight: 600;
            line-height: 1.3;
            white-space: nowrap;
        }

        /* Ilustrasi */
        .illus-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .illus-icon {
            color: var(--warna-utama);
            font-size: 64px;
            line-height: 1;
        }

        /* Kartu detail */
        .detail-card {
            display: flex;
            flex-direction: column;
            margin: 0 20px 20px;
            padding: 18px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        }

        .detail-section {
            min-width: 0;
        }

        .detail-section + .detail-section {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #E7E7E7;
        }

        .detail-section.sec-detail {
            margin-top: 12px;
            padding-top: 12px;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            min-width: 0;
            padding: 7px 0;
        }

        .detail-key {
            flex: 0 0 40%;
            min-width: 0;
            color: #555;
            font-size: 12px;
            font-weight: 400;
            line-height: 1.5;
        }

        .detail-val {
            flex: 1;
            min-width: 0;
            color: #111;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .total-key {
            color: #333;
            font-size: 14px;
            font-weight: 700;
        }

        .total-val {
            color: var(--warna-total);
            font-size: 23px;
            font-weight: 800;
            line-height: 1.3;
            text-align: right;
            white-space: normal;
        }

        /* Tombol aksi */
        .btn-row {
            display: flex;
            gap: 10px;
            margin: 0 20px;
        }

        .btn-batalkan,
        .btn-konfirmasi {
            flex: 1;
            min-width: 0;
            min-height: 42px;
            padding: 9px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-radius: 10px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;
            text-align: center;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .btn-batalkan {
            color: #FF0000;
            background: var(--warna-latar);
            border: 1px solid #FF0000;
        }

        .btn-batalkan:hover {
            background: #FFF0F0;
        }

        .btn-konfirmasi {
            color: #fff;
            background: var(--warna-utama);
            border: 1px solid var(--warna-utama);
        }

        .btn-konfirmasi:hover {
            background: #022C44;
        }

        /* Ikon Bootstrap */
        .btn-back .bi,
        .illus-icon {
            color: var(--warna-utama);
        }

        .btn-batalkan .bi {
            color: #FF0000;
        }

        .btn-konfirmasi .bi {
            color: #fff;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 14px;
        }

        .modal-header {
            padding: 18px 22px 12px;
            border-bottom: 1px solid #F0EEEE;
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 700;
        }

        .modal-body {
            padding: 16px 22px;
            color: #444;
            font-size: 13px;
            line-height: 1.6;
        }

        .modal-footer {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 12px 22px;
            border-top: 1px solid #F0EEEE;
        }

        /* Tablet dan desktop */
        @media (min-width: 768px) {
            body {
                align-items: center;
                padding: 24px;
            }

            .mobile-wrap {
                max-width: 960px;
                min-height: auto;
                padding: 0 0 28px;
                border-radius: 16px;
            }

            .page-header {
                padding: 24px 32px 12px;
            }

            .page-title {
                font-size: 23px;
            }

            .illus-wrap {
                margin-bottom: 18px;
            }

            .illus-icon {
                font-size: 58px;
            }

            /* Grid utama */
            .detail-card {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                align-items: stretch;
                gap: 16px;
                margin: 0 32px 20px;
                padding: 0;
                background: transparent;
                border-radius: 0;
                box-shadow: none;
            }

            /* Setiap bagian menjadi kartu terpisah */
            .detail-section {
                min-width: 0;
                padding: 18px 20px;
                background: #fff;
                border: 1px solid rgba(0, 0, 0, 0.025);
                border-radius: 12px;
                box-shadow: 0 3px 12px rgba(0, 0, 0, 0.045);
            }

            .detail-section + .detail-section,
            .detail-section.sec-detail {
                margin-top: 0;
                padding-top: 18px;
                border-top: 1px solid rgba(0, 0, 0, 0.025);
            }

            .detail-row {
                gap: 14px;
                padding: 7px 0;
            }

            .detail-key {
                flex-basis: 39%;
                font-size: 13px;
            }

            .detail-val {
                font-size: 14px;
            }

            /* Total menjadi baris penuh agar tidak terasa mengambang */
            .detail-section.sec-total {
                grid-column: 1 / -1;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                min-height: 76px;
                padding: 18px 24px;
                background: #fff;
            }

            .sec-total .detail-row {
                width: 100%;
                align-items: center !important;
                justify-content: space-between;
                gap: 20px;
                padding: 0;
            }

            .total-key {
                font-size: 16px;
                color: #222;
            }

            .total-val {
                font-size: 26px;
                white-space: nowrap;
            }

            /* Tombol berada tepat di bawah kartu */
            .btn-row {
                justify-content: flex-end;
                gap: 14px;
                margin: 0 32px;
            }

            .btn-batalkan,
            .btn-konfirmasi {
                flex: 0 1 240px;
                min-height: 46px;
                font-size: 14px;
            }

            .modal-dialog {
                max-width: 480px;
            }
        }

        /* Laptop dengan tinggi layar pendek */
        @media (min-width: 768px) and (max-height: 600px) {
            body {
                padding: 12px;
            }

            .mobile-wrap {
                padding-bottom: 16px;
            }

            .page-header {
                padding: 12px 28px 8px;
            }

            .page-title {
                font-size: 20px;
            }

            .illus-wrap {
                margin-bottom: 10px;
            }

            .illus-icon {
                font-size: 42px;
            }

            .detail-card {
                gap: 10px;
                margin-bottom: 12px;
            }

            .detail-section {
                padding: 10px 16px;
            }

            .detail-section + .detail-section,
            .detail-section.sec-detail {
                padding-top: 10px;
            }

            .detail-row {
                padding: 4px 0;
            }

            .detail-key {
                font-size: 12px;
            }

            .detail-val {
                font-size: 13px;
            }

            .detail-section.sec-total {
                min-height: 58px;
                padding: 10px 18px;
            }

            .total-val {
                font-size: 22px;
            }

            .btn-batalkan,
            .btn-konfirmasi {
                min-height: 38px;
            }
        }

        /* HP kecil */
        @media (max-width: 360px) {
            .page-header {
                padding: 40px 14px 18px;
            }

            .page-title {
                font-size: 18px;
            }

            .detail-card {
                margin-right: 16px;
                margin-left: 16px;
                padding: 14px 16px;
            }

            .detail-row {
                gap: 8px;
            }

            .detail-key {
                flex-basis: 38%;
                font-size: 11px;
            }

            .detail-val {
                font-size: 12px;
            }

            .total-val {
                font-size: 20px;
            }

            .btn-row {
                margin: 0 16px;
                gap: 8px;
            }

            .btn-batalkan,
            .btn-konfirmasi {
                padding: 8px 5px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

<div class="mobile-wrap">

    <!-- Header -->
    <div class="page-header">
        <a href="<?= base_url('pesanan/buat-pesanan?layanan=' . $layanan) ?>"
           class="btn-back"
           aria-label="Kembali">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
        </a>

        <h1 class="page-title">Ringkasan Pesanan</h1>
    </div>

    <!-- Ilustrasi -->
    <div class="illus-wrap">
        <i class="bi bi-clipboard-check illus-icon" aria-hidden="true"></i>
    </div>

    <!-- Detail Pesanan -->
    <div class="detail-card">

        <!-- Informasi Pesanan -->
        <div class="detail-section sec-info">
            <div class="detail-row">
                <span class="detail-key">ID Pesanan</span>
                <span class="detail-val"><?= esc((string) $id_pesanan) ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-key">Tanggal</span>
                <span class="detail-val"><?= date('d M Y') ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-key">Layanan</span>
                <span class="detail-val">Laundry <?= esc((string) $layanan) ?></span>
            </div>
        </div>

        <!-- Detail Item atau Berat -->
        <div class="detail-section sec-detail">
            <?php if ($layanan === 'Satuan' && !empty($detail_satuan)): ?>
                <?php
                    $items = json_decode((string) $detail_satuan, true) ?? [];

                    $namaItem = [
                        'jas'     => 'Jas Formal',
                        'jaket'   => 'Jaket',
                        'gaun'    => 'Gaun Pesta',
                        'sepatu'  => 'Sepatu',
                        'selimut' => 'Selimut',
                        'tas'     => 'Tas Ransel',
                        'kaos'    => 'Kaos Oblong',
                        'lainnya' => 'Lainnya',
                    ];
                ?>

                <div class="detail-row">
                    <span class="detail-key">Detail Item</span>
                    <span class="detail-val">
                        <?php foreach ($items as $k => $v): ?>
                            <?php if ((int) $v > 0): ?>
                                <?= esc($namaItem[$k] ?? ucfirst($k)) ?>
                                x<?= (int) $v ?><br>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </span>
                </div>
            <?php else: ?>
                <div class="detail-row">
                    <span class="detail-key">Berat</span>
                    <span class="detail-val"><?= (int) $berat ?> Kg</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Informasi Penjemputan -->
        <div class="detail-section sec-jemput">
            <div class="detail-row">
                <span class="detail-key">Alamat Penjemputan</span>
                <span class="detail-val"><?= esc((string) $alamat) ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-key">Tanggal Penjemputan</span>
                <span class="detail-val"><?= esc((string) $tanggal) ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-key">Catatan</span>
                <span class="detail-val">
                    <?= !empty($catatan) ? esc((string) $catatan) : '–' ?>
                </span>
            </div>
        </div>

        <!-- Total Biaya -->
        <div class="detail-section sec-total">
            <div class="detail-row">
                <span class="total-key">Total Biaya</span>
                <span class="total-val">
                    Rp <?= number_format((int) $total, 0, ',', '.') ?>
                </span>
            </div>
        </div>

    </div>

    <!-- Tombol Aksi -->
    <div class="btn-row">
        <button type="button"
                class="btn-batalkan"
                data-bs-toggle="modal"
                data-bs-target="#modalBatalkan">
            Batalkan
        </button>

        <button type="button"
                class="btn-konfirmasi"
                data-bs-toggle="modal"
                data-bs-target="#modalKonfirmasi">
            Konfirmasi Pesanan
        </button>
    </div>

</div>

<!-- Modal Batalkan -->
<div class="modal fade" id="modalBatalkan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                    Batalkan Pesanan
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin <strong>membatalkan</strong> pesanan Anda?
                Data yang sudah diisi akan hilang.
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-outline-secondary rounded-3"
                        data-bs-dismiss="modal"
                        style="font-size:13px;">
                    Tidak, Kembali
                </button>

                <a href="<?= base_url('pesanan/pilih-layanan') ?>"
                   class="btn btn-danger rounded-3 fw-bold"
                   style="font-size:13px;">
                    Ya, Batalkan
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pesanan -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" style="color:#033B59;">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                    Konfirmasi Pesanan
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin <strong>mengonfirmasi</strong>
                pesanan ini dan melanjutkan ke pembayaran?
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-outline-secondary rounded-3"
                        data-bs-dismiss="modal"
                        style="font-size:13px;">
                    Cek Kembali
                </button>

                <form action="<?= base_url('pesanan/konfirmasi-pesanan') ?>"
                      method="post"
                      style="margin:0;">
                    <?= csrf_field() ?>

                    <input type="hidden" name="id_layanan"
                           value="<?= esc((string) ($id_layanan ?? '1')) ?>">

                    <input type="hidden" name="id_pesanan"
                           value="<?= esc((string) $id_pesanan) ?>">

                    <input type="hidden" name="layanan"
                           value="<?= esc((string) $layanan) ?>">

                    <input type="hidden" name="berat"
                           value="<?= (int) $berat ?>">

                    <input type="hidden" name="total"
                           value="<?= (int) $total ?>">

                    <input type="hidden" name="alamat"
                           value="<?= esc((string) $alamat) ?>">

                    <input type="hidden" name="tanggal"
                           value="<?= esc((string) $tanggal) ?>">

                    <input type="hidden" name="catatan"
                           value="<?= esc((string) $catatan) ?>">

                    <input type="hidden" name="detail_satuan"
                           value="<?= esc((string) $detail_satuan) ?>">

                    <button type="submit"
                            class="btn fw-bold text-white rounded-3"
                            style="background:#033B59; font-size:13px;">
                        Ya, Konfirmasi
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>