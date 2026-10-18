<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiWash – Buat Pesanan</title>

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

        /* ─── Section label ─── */
        .section-label {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        /* ─── Info Layanan terpilih ─── */
        .layanan-terpilih-card {
            background: #fff;
            border-radius: 14px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .layanan-terpilih-card .ikon {
            font-size: 1.6rem;
            width: 42px;
            text-align: center;
        }
        .layanan-terpilih-card .nama {
            font-weight: 700;
            font-size: .93rem;
            margin: 0 0 2px;
            color: #1a1a2e;
        }
        .layanan-terpilih-card .harga {
            font-size: .82rem;
            color: #6b7280;
            margin: 0;
        }
        .layanan-terpilih-card .ubah-btn {
            margin-left: auto;
            font-size: .82rem;
            font-weight: 600;
            color: #0d6efd;
            text-decoration: none;
            white-space: nowrap;
        }
        .layanan-terpilih-card .ubah-btn:hover { text-decoration: underline; }

        /* ─── Counter Berat ─── */
        .counter-wrap {
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .counter-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            max-width: 200px;
            margin: 0 auto;
        }
        .counter-btn {
            width: 44px;
            height: 44px;
            border: 2px solid #dee2e6;
            background: #fff;
            border-radius: 10px;
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a2e;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
            flex-shrink: 0;
        }
        .counter-btn:hover { background: #e9ecef; }
        .counter-val {
            flex: 1;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            color: #1a1a2e;
            border: none;
            outline: none;
            background: transparent;
            min-width: 60px;
        }
        /* Sembunyikan panah angka bawaan browser */
        .counter-val::-webkit-inner-spin-button,
        .counter-val::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .counter-val { -moz-appearance: textfield; }

        /* ─── Alamat card ─── */
        .alamat-card {
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .alamat-card .alamat-text {
            font-size: .88rem;
            color: #374151;
            margin: 0;
            line-height: 1.5;
        }
        .alamat-card .ubah-btn {
            font-size: .82rem;
            font-weight: 600;
            color: #0d6efd;
            text-decoration: none;
        }
        .alamat-card .ubah-btn:hover { text-decoration: underline; }

        /* ─── Form Card ─── */
        .form-card {
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }

        /* ─── Rincian Biaya ─── */
        .rincian-card {
            background: #cfe2ff;   /* Bootstrap bg-primary-subtle */
            border-radius: 14px;
            padding: 14px 18px;
        }
        .rincian-card .rincian-label {
            font-weight: 700;
            font-size: .92rem;
            color: #052c65;
            margin: 0;
        }
        .rincian-card .rincian-detail {
            font-size: .8rem;
            color: #0a58ca;
            margin: 2px 0 0;
        }
        .rincian-card .total-biaya {
            font-size: 1.15rem;
            font-weight: 800;
            color: #198754;
            margin: 0;
        }

        /* ─── Tombol utama ─── */
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
    </style>
</head>
<body>

<?php
// Escape helper
$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

$idLayanan   = $layanan['id']        ?? 'reguler';
$namaLayanan = $layanan['nama']      ?? '';
$hargaLabel  = $layanan['harga_label'] ?? '';
$hargaPerKg  = (int) ($layanan['harga'] ?? 0);
$ikon        = $layanan['ikon']      ?? '🧺';
?>

<!-- Top Bar -->
<div class="dw-topbar">
    <a href="<?= base_url('layanan') ?>" class="back-btn" aria-label="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h6>Buat Pesanan</h6>
</div>

<!-- Konten utama -->
<div class="container my-4" style="max-width: 480px;">

    <form action="<?= base_url('layanan/ringkasan') ?>" method="post" id="formPesanan" novalidate>
        <?= csrf_field() ?>

        <!-- Hidden fields: data layanan terpilih -->
        <input type="hidden" name="id_layanan"   value="<?= $e($idLayanan) ?>">
        <input type="hidden" name="nama_layanan" value="<?= $e($namaLayanan) ?>">
        <input type="hidden" name="harga_per_kg" value="<?= $hargaPerKg ?>">
        <input type="hidden" name="harga_label"  value="<?= $e($hargaLabel) ?>">

        <!-- ① Info Layanan Terpilih -->
        <p class="section-label px-1">Layanan</p>
        <div class="layanan-terpilih-card mb-4">
            <span class="ikon"><?= $ikon ?></span>
            <div>
                <p class="nama"><?= $e($namaLayanan) ?></p>
                <p class="harga"><?= $e($hargaLabel) ?></p>
            </div>
            <a href="<?= base_url('layanan') ?>" class="ubah-btn">Ubah</a>
        </div>

        <!-- ② Berat Cucian -->
        <p class="section-label px-1">Berat Cucian (Kg)</p>
        <div class="counter-wrap mb-4">
            <div class="counter-group">
                <button type="button" class="counter-btn" id="btnKurang" aria-label="Kurangi berat">−</button>
                <input  type="number"
                        class="counter-val"
                        id="beratInput"
                        name="berat"
                        value="5"
                        min="1"
                        max="100"
                        readonly
                        aria-label="Berat cucian dalam kilogram">
                <button type="button" class="counter-btn" id="btnTambah" aria-label="Tambah berat">+</button>
            </div>
        </div>

        <!-- ③ Alamat Penjemputan -->
        <p class="section-label px-1">Alamat Penjemputan</p>
        <div class="alamat-card mb-4">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                <div class="flex-grow-1">
                    <p class="alamat-text" id="teksAlamat"><?= $e($alamatUtama) ?></p>
                    <input type="hidden" name="alamat" id="hiddenAlamat" value="<?= $e($alamatUtama) ?>">
                </div>
                <a href="<?= base_url('alamat') ?>" class="ubah-btn flex-shrink-0">Ubah</a>
            </div>
        </div>

        <!-- ④ Tanggal Penjemputan -->
        <p class="section-label px-1">Tanggal Penjemputan</p>
        <div class="form-card mb-4">
            <div class="input-group">
                <input type="date"
                       class="form-control"
                       id="tanggalPenjemputan"
                       name="tanggal"
                       required
                       aria-label="Tanggal penjemputan">
                <span class="input-group-text bg-white">
                    <i class="bi bi-calendar3 text-secondary"></i>
                </span>
            </div>
            <div class="invalid-feedback d-block" id="errTanggal" style="display:none!important"></div>
        </div>

        <!-- ⑤ Catatan Tambahan (Opsional) -->
        <p class="section-label px-1">Catatan Tambahan (Opsional)</p>
        <div class="form-card mb-4">
            <textarea class="form-control border-0 p-0"
                      id="catatan"
                      name="catatan"
                      rows="2"
                      maxlength="300"
                      placeholder="Contoh : Rumah Putih Meja Aqua putih"
                      aria-label="Catatan tambahan"
                      style="resize:none;box-shadow:none;font-size:.9rem;"></textarea>
        </div>

        <!-- ⑥ Rincian Biaya -->
        <div class="rincian-card mb-4 d-flex align-items-center justify-content-between">
            <div>
                <p class="rincian-label">Rincian Biaya</p>
                <p class="rincian-detail" id="detailBiaya">
                    5 Kg × Rp<?= number_format($hargaPerKg, 0, ',', '.') ?> = 
                </p>
            </div>
            <p class="total-biaya" id="totalBiaya">
                Rp<?= number_format(5 * $hargaPerKg, 0, ',', '.') ?>
            </p>
        </div>

        <!-- ⑦ Tombol Lanjut -->
        <button type="submit" class="btn-dw-primary">
            Lanjut Ke Ringkasan
        </button>

    </form>

</div><!-- /container -->

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmHRNi4a8g8CRs+y5VqBjiqRJ1r4"
        crossorigin="anonymous"></script>

<script>
(function () {
    'use strict';

    var hargaPerKg  = <?= (int) $hargaPerKg ?>;
    var beratInput  = document.getElementById('beratInput');
    var totalEl     = document.getElementById('totalBiaya');
    var detailEl    = document.getElementById('detailBiaya');
    var btnKurang   = document.getElementById('btnKurang');
    var btnTambah   = document.getElementById('btnTambah');

    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    function updateBiaya() {
        var berat = parseInt(beratInput.value, 10) || 1;
        if (berat < 1)   { berat = 1;   beratInput.value = 1;   }
        if (berat > 100) { berat = 100; beratInput.value = 100; }

        var total = berat * hargaPerKg;
        detailEl.textContent = berat + ' Kg × ' + formatRupiah(hargaPerKg) + ' = ';
        totalEl.textContent  = formatRupiah(total);
    }

    btnKurang.addEventListener('click', function () {
        var v = parseInt(beratInput.value, 10) || 1;
        if (v > 1) { beratInput.value = v - 1; updateBiaya(); }
    });

    btnTambah.addEventListener('click', function () {
        var v = parseInt(beratInput.value, 10) || 1;
        if (v < 100) { beratInput.value = v + 1; updateBiaya(); }
    });

    // Validasi form sebelum submit
    document.getElementById('formPesanan').addEventListener('submit', function (ev) {
        var tanggal = document.getElementById('tanggalPenjemputan').value;
        var errEl   = document.getElementById('errTanggal');

        if (!tanggal) {
            ev.preventDefault();
            errEl.textContent = 'Tanggal penjemputan wajib diisi.';
            errEl.style.display = 'block';
            document.getElementById('tanggalPenjemputan').classList.add('is-invalid');
            return;
        }
        errEl.style.display = 'none';
        document.getElementById('tanggalPenjemputan').classList.remove('is-invalid');
    });

    // Set tanggal default = hari ini
    var today = new Date();
    var dd    = String(today.getDate()).padStart(2, '0');
    var mm    = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy  = today.getFullYear();
    document.getElementById('tanggalPenjemputan').value = yyyy + '-' + mm + '-' + dd;

    updateBiaya();
})();
</script>

</body>
</html>
