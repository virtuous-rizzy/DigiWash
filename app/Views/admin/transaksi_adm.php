<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memproses Transaksi - DigiWash Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --dw-primary: #033B59;
            --dw-primary-hover: #022b42;
            --dw-accent: #8fe0e8;
            --dw-bg: #EFEEEE;
            --dw-orange: #E65100;
            --dw-green: #009624;
            --dw-red: #E53935;
            --dw-blue: #3F8CFF;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--dw-bg);
            font-family: 'Roboto', sans-serif;
            color: #212529;
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }

        .admin-wrap {
            width: 100%;
            max-width: 430px;
            background: #F8F9FA;
            min-height: 100vh;
            padding-bottom: 84px;
            position: relative;
            box-shadow: 0 0 24px rgba(0,0,0,0.06);
        }

        /* ── Header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 42px 20px 16px;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 20;
            border-bottom: 1px solid #ECECEC;
        }

        .btn-header-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            text-decoration: none;
            color: #111;
            font-size: 22px;
            transition: background 0.15s;
        }
        .btn-header-back:hover {
            background: rgba(3, 59, 89, 0.08);
            color: var(--dw-primary);
        }

        .page-header-title {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            font-size: 19px;
            color: #111;
            margin: 0;
            text-align: center;
            flex: 1;
        }

        .header-spacer { width: 38px; }

        /* ── Flash message ── */
        .flash-msg {
            margin: 14px 18px 0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Search & Filter ── */
        .controls-area {
            padding: 16px 18px 10px;
        }

        .search-filter-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
            font-size: 16px;
        }

        .search-input {
            width: 100%;
            height: 42px;
            padding: 8px 12px 8px 40px;
            border-radius: 10px;
            border: 1px solid #D6D8DB;
            background: #fff;
            font-size: 13px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .search-input:focus {
            border-color: var(--dw-primary);
            box-shadow: 0 0 0 3px rgba(3, 59, 89, 0.12);
        }

        .btn-filter-toggle {
            height: 42px;
            padding: 0 16px;
            border-radius: 10px;
            border: 1px solid #D6D8DB;
            background: #fff;
            color: #222;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .btn-filter-toggle:hover, .btn-filter-toggle.active {
            border-color: var(--dw-primary);
            color: var(--dw-primary);
            background: rgba(3, 59, 89, 0.05);
        }
        .btn-filter-toggle i {
            font-size: 15px;
            color: var(--dw-primary);
        }

        /* ── Filter Pills / Chips ── */
        .filter-chips {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 8px 18px 12px;
            -webkit-overflow-scrolling: touch;
        }
        .filter-chips::-webkit-scrollbar { display: none; }

        .chip-btn {
            border: 1px solid #DCE0E5;
            background: #fff;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 500;
            color: #444;
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
        }
        .chip-btn:hover {
            border-color: var(--dw-primary);
            color: var(--dw-primary);
        }
        .chip-btn.active {
            background: var(--dw-primary);
            border-color: var(--dw-primary);
            color: #fff;
        }
        .chip-badge {
            background: rgba(0,0,0,0.08);
            border-radius: 10px;
            padding: 1px 6px;
            font-size: 10px;
            font-weight: 700;
        }
        .chip-btn.active .chip-badge {
            background: rgba(255,255,255,0.25);
            color: #fff;
        }

        /* ── Cards List ── */
        .transaksi-list {
            padding: 4px 18px 24px;
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
            color: var(--dw-primary);
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
            color: var(--dw-primary);
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
            color: var(--dw-orange);
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

        /* ── Bottom Nav ── */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 430px;
            background: #FFFFFF;
            border-top: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 8px 0;
            z-index: 30;
        }

        .bottom-nav-item {
            text-decoration: none;
            color: #4A5568;
            font-size: 10px;
            font-weight: 600;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            transition: color 0.15s;
        }
        .bottom-nav-item:hover {
            color: var(--dw-primary);
        }
        .bottom-nav-item span {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            transition: background 0.15s, color 0.15s;
        }
        .bottom-nav-item.active {
            color: var(--dw-primary);
            font-weight: 700;
        }
        .bottom-nav-item.active span {
            background: var(--dw-accent);
            color: #033B59;
        }

        /* ── Responsive Tablet & Desktop ── */
        @media (min-width: 768px) {
            body {
                background: #EAEFF2;
            }

            .admin-wrap {
                max-width: 960px;
                background: #F4F7F9;
                padding-bottom: 90px;
                border-radius: 16px;
                margin: 20px 0;
                min-height: calc(100vh - 40px);
            }

            .page-header {
                padding: 24px 32px;
                border-radius: 16px 16px 0 0;
            }
            .page-header-title { font-size: 22px; }

            .controls-area {
                padding: 20px 32px 10px;
            }
            .filter-chips {
                padding: 8px 32px 16px;
            }

            .transaksi-list {
                padding: 10px 32px 30px;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
            }

            .bottom-nav {
                max-width: 960px;
                border-radius: 0 0 16px 16px;
                padding: 10px 0;
            }
            .bottom-nav-item {
                font-size: 12px;
            }
            .bottom-nav-item span {
                font-size: 22px;
                width: 44px;
                height: 44px;
            }
        }

        @media (min-width: 1200px) {
            .admin-wrap {
                max-width: 1140px;
            }
            .transaksi-list {
                grid-template-columns: repeat(2, 1fr);
            }
            .bottom-nav {
                max-width: 1140px;
            }
        }
    </style>
</head>
<body>

<div class="admin-wrap">

    <!-- ══ HEADER ══ -->
    <header class="page-header">
        <a href="<?= base_url('admin/dashboard') ?>" class="btn-header-back" title="Kembali ke Dashboard">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="page-header-title">Memproses Transaksi</h1>
        <div class="header-spacer"></div>
    </header>

    <!-- ══ FLASH MESSAGE ══ -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-msg alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill text-success"></i>
            <div><?= esc((string) session()->getFlashdata('success')) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- ══ SEARCH & CONTROLS ══ -->
    <div class="controls-area">
        <form method="get" action="<?= base_url('admin/transaksi') ?>" class="search-filter-row" id="formSearch">
            <input type="hidden" name="status" value="<?= esc($statusFilter ?? 'semua') ?>">
            
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text"
                       name="q"
                       class="search-input"
                       placeholder="Cari no. transaksi / pelanggan..."
                       value="<?= esc($keyword ?? '') ?>">
            </div>

            <button type="button"
                    class="btn-filter-toggle <?= (!empty($statusFilter) && $statusFilter !== 'semua') ? 'active' : '' ?>"
                    id="btnToggleFilter"
                    onclick="toggleFilterChips()">
                <i class="bi bi-funnel"></i>
                Filter
            </button>
        </form>
    </div>

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

        <a href="<?= base_url('admin/transaksi?status=gagal' . ($keyword !== '' ? '&q=' . urlencode($keyword) : '')) ?>"
           class="chip-btn <?= ($statusFilter === 'gagal') ? 'active' : '' ?>">
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
                    <?= !empty($keyword) ? 'Tidak ditemukan transaksi dengan kata kunci tersebut.' : 'Belum ada pesanan yang perlu diverifikasi.' ?>
                </div>
            </div>
        <?php else: ?>

            <?php foreach ($transaksi as $t): 
                $idPesanan   = (int) ($t['id_pesanan'] ?? 1);
                $nomorPesanan = 'PSN-' . date('dmY', strtotime((string)($t['tanggal_pesanan'] ?? 'now'))) . '-' . str_pad((string)$idPesanan, 3, '0', STR_PAD_LEFT);
                $tglFormatted = date('j M Y - H:i', strtotime((string)($t['tanggal_pesanan'] ?? $t['tanggal_bayar'] ?? 'now')));
                $namaPelanggan = (string) ($t['nama_pelanggan'] ?? 'Pelanggan');
                $inisial     = strtoupper(substr($namaPelanggan, 0, 1));
                $fotoPelanggan = $t['foto_pelanggan'] ? base_url('uploads/foto/' . $t['foto_pelanggan']) : null;
                $layananNama = (string) ($t['nama_layanan'] ?? 'Laundry Reguler');
                $berat       = (float) ($t['berat'] ?? 1.0);
                $totalBayar  = (float) ($t['total_bayar'] ?? 0);
                $statusBayar = (string) ($t['status_bayar'] ?? 'menunggu_verifikasi');
                $metodeBayar = (string) ($t['metode'] ?? 'Bank BCA');
                $buktiBayar  = !empty($t['bukti_bayar']) ? base_url('uploads/bukti/' . $t['bukti_bayar']) : null;
            ?>
                <div class="transaksi-card" id="trx-card-<?= (int)$t['id_pembayaran'] ?>">

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
                            </div>
                        </div>

                        <div class="card-price-right">
                            <div class="trx-price">Rp <?= number_format($totalBayar, 0, ',', '.') ?></div>
                            <?php if ($statusBayar === 'menunggu_verifikasi'): ?>
                                <span class="status-badge status-menunggu">Menunggu Verifikasi</span>
                            <?php elseif ($statusBayar === 'lunas'): ?>
                                <span class="status-badge status-lunas"><i class="bi bi-check2-circle me-1"></i>Lunas</span>
                            <?php else: ?>
                                <span class="status-badge status-gagal">Ditolak</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Bottom Row: Action Buttons -->
                    <div class="card-actions-row">
                        <?php if ($statusBayar === 'menunggu_verifikasi'): ?>
                            <!-- Tolak Form -->
                            <form action="<?= base_url('admin/transaksi/tolak/' . (int) $t['id_pembayaran']) ?>"
                                  method="post"
                                  id="form-tolak-<?= (int) $t['id_pembayaran'] ?>"
                                  style="margin:0;">
                                <?= csrf_field() ?>
                                <button type="button"
                                        class="btn-tolak"
                                        onclick="bukaModalTolak(<?= (int) $t['id_pembayaran'] ?>, '<?= esc($nomorPesanan) ?>', '<?= esc($namaPelanggan) ?>')">
                                    Tolak
                                </button>
                            </form>

                            <!-- Verifikasi Form -->
                            <form action="<?= base_url('admin/transaksi/verifikasi/' . (int) $t['id_pembayaran']) ?>"
                                  method="post"
                                  id="form-verif-<?= (int) $t['id_pembayaran'] ?>"
                                  style="margin:0;">
                                <?= csrf_field() ?>
                                <button type="button"
                                        class="btn-verifikasi"
                                        onclick="bukaModalVerifikasi(<?= (int) $t['id_pembayaran'] ?>, '<?= esc($nomorPesanan) ?>', '<?= esc($namaPelanggan) ?>', 'Rp <?= number_format($totalBayar, 0, ',', '.') ?>')">
                                    Verifikasi
                                </button>
                            </form>

                        <?php elseif ($statusBayar === 'lunas'): ?>
                            <form action="<?= base_url('admin/transaksi/tolak/' . (int) $t['id_pembayaran']) ?>"
                                  method="post"
                                  id="form-tolak-<?= (int) $t['id_pembayaran'] ?>"
                                  style="margin:0;">
                                <?= csrf_field() ?>
                                <button type="button"
                                        class="btn-tolak"
                                        onclick="bukaModalTolak(<?= (int) $t['id_pembayaran'] ?>, '<?= esc($nomorPesanan) ?>', '<?= esc($namaPelanggan) ?>')">
                                    Tolak
                                </button>
                            </form>
                            <span class="btn-lunas-static"><i class="bi bi-check-circle-fill me-1"></i>Lunas</span>

                        <?php else: ?>
                            <span class="btn-ditolak-static"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                            <!-- Opsi verifikasi ulang jika salah tolak -->
                            <form action="<?= base_url('admin/transaksi/verifikasi/' . (int) $t['id_pembayaran']) ?>"
                                  method="post"
                                  id="form-verif-<?= (int) $t['id_pembayaran'] ?>"
                                  style="margin:0;">
                                <?= csrf_field() ?>
                                <button type="button"
                                        class="btn-verifikasi"
                                        onclick="bukaModalVerifikasi(<?= (int) $t['id_pembayaran'] ?>, '<?= esc($nomorPesanan) ?>', '<?= esc($namaPelanggan) ?>', 'Rp <?= number_format($totalBayar, 0, ',', '.') ?>')">
                                    Verifikasi
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <!-- ══ BOTTOM NAVIGATION ══ -->
    <nav class="bottom-nav">
        <a href="<?= base_url('admin/dashboard') ?>" class="bottom-nav-item">
            <span><i class="bi bi-house-door-fill"></i></span>
            Beranda
        </a>
        <a href="<?= base_url('admin/pesanan') ?>" class="bottom-nav-item">
            <span><i class="bi bi-box-seam-fill"></i></span>
            Pesanan
        </a>
        <a href="<?= base_url('admin/transaksi') ?>" class="bottom-nav-item active">
            <span><i class="bi bi-currency-dollar"></i></span>
            Transaksi
        </a>
        <a href="<?= base_url('admin/akun') ?>" class="bottom-nav-item">
            <span><i class="bi bi-person-fill"></i></span>
            Akun
        </a>
    </nav>

</div>

<!-- ══ MODAL PREVIEW BUKTI TRANSFER ══ -->
<div class="modal fade" id="modalBuktiBayar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
            <div class="modal-header" style="background: var(--dw-primary); color: #fff;">
                <h6 class="modal-title fw-bold" id="modalBuktiTitle">
                    <i class="bi bi-receipt me-2"></i>Bukti Transfer
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3" style="background: #f8f9fa;">
                <p class="text-muted small mb-2" id="modalBuktiSub"></p>
                <div style="max-height: 440px; overflow-y: auto; border-radius: 8px; background: #fff; padding: 6px; box-shadow: inset 0 0 6px rgba(0,0,0,0.06);">
                    <img id="imgBuktiPreview" src="" alt="Bukti Pembayaran" style="max-width: 100%; height: auto; border-radius: 6px; display: block; margin: 0 auto;">
                </div>
            </div>
            <div class="modal-footer" style="background: #fff;">
                <button type="button" class="btn btn-secondary rounded-3 btn-sm" data-bs-dismiss="modal">Tutup</button>
                <a id="btnDownloadBukti" href="#" target="_blank" class="btn btn-primary rounded-3 btn-sm" style="background: var(--dw-primary); border: none;">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka Gambar Penuh
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ══ MODAL KONFIRMASI VERIFIKASI ══ -->
<div class="modal fade" id="modalKonfirmasiVerifikasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
            <div class="modal-header" style="background: #033B59; color: #fff; padding: 16px 22px;">
                <h6 class="modal-title fw-bold" style="margin: 0; font-size: 16px;">
                    <i class="bi bi-check2-circle me-2"></i>Konfirmasi Verifikasi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3" style="width: 70px; height: 70px; border-radius: 50%; background: #E8F5E9; color: #009624; display: inline-flex; align-items: center; justify-content: center; font-size: 36px;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: #111;">Verifikasi Pembayaran</h5>
                <p class="text-muted small mb-3">
                    Pastikan dana transfer telah masuk ke rekening sebelum menandai lunas.
                </p>
                <div class="p-3 rounded-3 text-start mb-2" style="background: #F8F9FA; border: 1px solid #E9ECEF; font-size: 13px;">
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
            <div class="modal-footer" style="background: #fff; border-top: 1px solid #ECECEC; padding: 12px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm px-3" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="btnConfirmVerifAction" class="btn btn-success rounded-3 btn-sm px-3 fw-bold" style="background: #009624; border: none;">
                    <i class="bi bi-check-circle me-1"></i>Ya, Verifikasi Lunas
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══ MODAL KONFIRMASI TOLAK ══ -->
<div class="modal fade" id="modalKonfirmasiTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
            <div class="modal-header" style="background: #E53935; color: #fff; padding: 16px 22px;">
                <h6 class="modal-title fw-bold" style="margin: 0; font-size: 16px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Tolak
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3" style="width: 70px; height: 70px; border-radius: 50%; background: #FFEBEE; color: #E53935; display: inline-flex; align-items: center; justify-content: center; font-size: 34px;">
                    <i class="bi bi-x-lg"></i>
                </div>
                <h5 class="fw-bold mb-2 text-danger">Tolak Transaksi?</h5>
                <p class="text-muted small mb-3">
                    Transaksi ini akan ditandai <strong class="text-danger">Gagal</strong> dan pesanan pelanggan akan otomatis <strong class="text-danger">Dibatalkan</strong>.
                </p>
                <div class="p-3 rounded-3 text-start mb-2" style="background: #FFF5F5; border: 1px solid #FFCDD2; font-size: 13px;">
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
            <div class="modal-footer" style="background: #fff; border-top: 1px solid #ECECEC; padding: 12px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm px-3" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="btnConfirmTolakAction" class="btn btn-danger rounded-3 btn-sm px-3 fw-bold" style="background: #E53935; border: none;">
                    <i class="bi bi-x-circle me-1"></i>Ya, Tolak Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var currentVerifId = null;
    var currentTolakId = null;
    var modalVerif = null;
    var modalTolak = null;

    document.addEventListener('DOMContentLoaded', function () {
        var elVerif = document.getElementById('modalKonfirmasiVerifikasi');
        if (elVerif) modalVerif = new bootstrap.Modal(elVerif);

        var elTolak = document.getElementById('modalKonfirmasiTolak');
        if (elTolak) modalTolak = new bootstrap.Modal(elTolak);

        document.getElementById('btnConfirmVerifAction').addEventListener('click', function () {
            if (currentVerifId) {
                var form = document.getElementById('form-verif-' + currentVerifId);
                if (form) form.submit();
            }
        });

        document.getElementById('btnConfirmTolakAction').addEventListener('click', function () {
            if (currentTolakId) {
                var form = document.getElementById('form-tolak-' + currentTolakId);
                if (form) form.submit();
            }
        });
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
        var modal = new bootstrap.Modal(document.getElementById('modalBuktiBayar'));
        modal.show();
    }

    function toggleFilterChips() {
        var row = document.getElementById('filterChipsRow');
        if (row.style.display === 'none') {
            row.style.display = 'flex';
        } else {
            row.style.display = 'none';
        }
    }

    document.querySelectorAll('[data-soon]').forEach(function (el) {
        el.addEventListener('click', function (ev) {
            ev.preventDefault();
            // Tampilkan toast atau alert Bootstrap
            alert('Fitur segera hadir');
        });
    });
</script>
</body>
</html>
