<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - DigiWash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #E7E7E7;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        .mobile-wrap {
            width: 100%;
            max-width: 402px;
            background: #E7E7E7;
            min-height: 100vh;
            padding-bottom: 40px;
        }

        /* Header */
        .page-header {
            display: flex; align-items: center;
            padding: 54px 24px 24px; position: relative;
        }
        .btn-back {
            display: flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; text-decoration: none;
            color: #000; font-size: 20px; font-weight: bold;
        }
        .page-title {
            position: absolute; left: 50%; transform: translateX(-50%);
            font-weight: 600; font-size: 20px; line-height: 22px;
            color: #000; white-space: nowrap;
        }

        /* Order summary card */
        .order-summary-card {
            background: #fff; border-radius: 12px;
            margin: 0 24px 16px; padding: 16px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .order-summary-card .lbl { font-size: 11px; font-weight: 400; color: #888; margin-bottom: 2px; }
        .order-summary-card .val { font-size: 14px; font-weight: 700; color: #000; }
        .order-summary-card .total { font-size: 22px; font-weight: 800; color: #019D13; }

        /* Section title */
        .section-title {
            font-weight: 700; font-size: 14px; color: #333;
            margin: 0 24px 10px; display: block;
        }

        /* Accordion group */
        .method-group { margin: 0 24px 14px; }
        .method-group-header {
            background: #033B59; color: #fff; font-weight: 600; font-size: 13px;
            padding: 11px 16px; border-radius: 10px 10px 0 0;
            cursor: pointer; display: flex; align-items: center;
            justify-content: space-between; user-select: none;
            transition: background 0.2s;
        }
        .method-group-header.collapsed { border-radius: 10px; }
        .method-group-header .chevron { transition: transform 0.3s; font-size: 11px; }
        .method-group-header.collapsed .chevron { transform: rotate(-90deg); }
        .method-group-body {
            background: #fff; border: 1px solid #D2CBCB;
            border-top: none; border-radius: 0 0 10px 10px; overflow: hidden;
        }

        /* Payment option */
        .method-option {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 16px; border-bottom: 1px solid #f5f5f5;
            cursor: pointer; transition: background 0.15s;
        }
        .method-option:last-child { border-bottom: none; }
        .method-option:hover { background: #f8fafc; }
        .method-option.selected { background: #EBF5FB; }
        .method-option input[type="radio"] {
            accent-color: #033B59; width: 16px; height: 16px; flex-shrink: 0;
        }
        .method-logo {
            width: 44px; height: 26px; display: flex; align-items: center;
            justify-content: center; border-radius: 4px;
            font-size: 10px; font-weight: 800; flex-shrink: 0;
        }
        .logo-bca     { background: #003e7e; color: #fff; }
        .logo-bri     { background: #0056a6; color: #fff; }
        .logo-bni     { background: #f57c00; color: #fff; }
        .logo-mandiri { background: #002f6c; color: #ffd700; }
        .logo-dana    { background: #1092e8; color: #fff; }
        .logo-gopay   { background: #00aed6; color: #fff; }
        .logo-spay    { background: #ee4d2d; color: #fff; }
        .method-name  { font-weight: 600; font-size: 13px; color: #000; }
        .method-desc  { font-weight: 400; font-size: 11px; color: #888; margin-top: 1px; }

        /* Pay button */
        .btn-bayar {
            display: flex; align-items: center; justify-content: center;
            width: calc(100% - 48px); margin: 20px 24px 0;
            height: 48px; background: #033B59; border: none; border-radius: 10px;
            font-weight: 700; font-size: 16px; color: #fff;
            cursor: pointer; text-decoration: none; transition: background 0.2s;
        }
        .btn-bayar:hover { background: #022c44; color: #fff; }
        .btn-bayar:disabled { opacity: 0.6; cursor: not-allowed; }
    </style>
</head>
<body>
<div class="mobile-wrap">

    <!-- Header -->
    <div class="page-header">
        <?php
        $from       = (string) (request()->getGet('from') ?? '');
        $referer    = (string) ($_SERVER['HTTP_REFERER'] ?? '');
        if ($from === 'notifikasi' || str_contains($referer, 'notifikasi')) {
            $backUrl = base_url('pesanan/notifikasi');
        } elseif (str_contains($referer, 'riwayat') || str_contains($referer, 'pesanan/riwayat') || $from === 'riwayat') {
            $backUrl = base_url('pesanan/riwayat');
        } else {
            $backUrl = base_url('pesanan/ringkasan');
        }
        ?>
        <a href="<?= $backUrl ?>" class="btn-back">&#8592;</a>
        <span class="page-title">Pembayaran</span>
    </div>

    <!-- Order Summary -->
    <div class="order-summary-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="lbl">ID Pesanan</div>
                <div class="val"><?= esc((string) $id_pesanan) ?></div>
                <?php if (!empty($layanan)): ?>
                    <div class="lbl mt-1">Layanan</div>
                    <div class="val">Laundry <?= esc((string) $layanan) ?></div>
                <?php endif; ?>
            </div>
            <div class="text-end">
                <div class="lbl">Total Tagihan</div>
                <div class="total">Rp <?= number_format((int) $total, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>

    <span class="section-title">Pilih Metode Pembayaran</span>

    <!-- ── Form POST ke proses-pembayaran ── -->
    <form action="<?= base_url('pesanan/proses-pembayaran') ?>" method="post" id="formBayar">
        <?= csrf_field() ?>

        <!-- Transfer Bank -->
        <div class="method-group">
            <div class="method-group-header" id="hdr-bank" onclick="toggleGroup('bank')">
                <span>🏦 Transfer Bank</span>
                <span class="chevron">&#9660;</span>
            </div>
            <div class="method-group-body" id="body-bank">

                <label class="method-option" for="pay_bca">
                    <input type="radio" id="pay_bca" name="metode_bayar" value="Bank BCA"
                        onchange="highlightOption(this)">
                    <span class="method-logo logo-bca">BCA</span>
                    <div>
                        <div class="method-name">Bank BCA</div>
                        <div class="method-desc">No. Rek: 1234-5678-9012</div>
                    </div>
                </label>

                <label class="method-option" for="pay_bri">
                    <input type="radio" id="pay_bri" name="metode_bayar" value="Bank BRI"
                        onchange="highlightOption(this)">
                    <span class="method-logo logo-bri">BRI</span>
                    <div>
                        <div class="method-name">Bank BRI</div>
                        <div class="method-desc">No. Rek: 0987-6543-2101</div>
                    </div>
                </label>

                <label class="method-option" for="pay_bni">
                    <input type="radio" id="pay_bni" name="metode_bayar" value="Bank BNI"
                        onchange="highlightOption(this)">
                    <span class="method-logo logo-bni">BNI</span>
                    <div>
                        <div class="method-name">Bank BNI</div>
                        <div class="method-desc">No. Rek: 1122-3344-5566</div>
                    </div>
                </label>

                <label class="method-option" for="pay_mandiri">
                    <input type="radio" id="pay_mandiri" name="metode_bayar" value="Bank Mandiri"
                        onchange="highlightOption(this)">
                    <span class="method-logo logo-mandiri">MDR</span>
                    <div>
                        <div class="method-name">Bank Mandiri</div>
                        <div class="method-desc">No. Rek: 9988-7766-5544</div>
                    </div>
                </label>

            </div>
        </div>

        <!-- E-Wallet -->
        <div class="method-group">
            <div class="method-group-header" id="hdr-ewallet" onclick="toggleGroup('ewallet')">
                <span>💳 E-Wallet</span>
                <span class="chevron">&#9660;</span>
            </div>
            <div class="method-group-body" id="body-ewallet">

                <label class="method-option" for="pay_dana">
                    <input type="radio" id="pay_dana" name="metode_bayar" value="DANA"
                        onchange="highlightOption(this)">
                    <span class="method-logo logo-dana" style="font-size:9px;">DANA</span>
                    <div>
                        <div class="method-name">DANA</div>
                        <div class="method-desc">0812-xxxx-xxxx</div>
                    </div>
                </label>

                <label class="method-option" for="pay_gopay">
                    <input type="radio" id="pay_gopay" name="metode_bayar" value="GoPay"
                        onchange="highlightOption(this)">
                    <span class="method-logo logo-gopay">GP</span>
                    <div>
                        <div class="method-name">GoPay</div>
                        <div class="method-desc">0821-xxxx-xxxx</div>
                    </div>
                </label>

                <label class="method-option" for="pay_spay">
                    <input type="radio" id="pay_spay" name="metode_bayar" value="ShopeePay"
                        onchange="highlightOption(this)">
                    <span class="method-logo logo-spay" style="font-size:9px;">SPAY</span>
                    <div>
                        <div class="method-name">ShopeePay</div>
                        <div class="method-desc">0856-xxxx-xxxx</div>
                    </div>
                </label>

            </div>
        </div>

        <!-- Pay button -->
        <button type="submit" class="btn-bayar" id="btnBayar" onclick="return validasiMetode()">
            Bayar Sekarang →
        </button>

    </form>

</div><!-- /.mobile-wrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleGroup(id) {
        const body = document.getElementById('body-' + id);
        const hdr  = document.getElementById('hdr-' + id);
        const hidden = body.style.display === 'none';
        body.style.display = hidden ? '' : 'none';
        hdr.classList.toggle('collapsed', !hidden);
    }

    function highlightOption(radio) {
        document.querySelectorAll('.method-option').forEach(o => o.classList.remove('selected'));
        radio.closest('.method-option').classList.add('selected');
    }

    function validasiMetode() {
        const sel = document.querySelector('input[name="metode_bayar"]:checked');
        if (!sel) {
            alert('Pilih metode pembayaran terlebih dahulu!');
            return false;
        }
        return true;
    }
</script>
</body>
</html>
