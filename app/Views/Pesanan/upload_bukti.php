<?php
$errors = session()->getFlashdata('errors');
$errors = is_array($errors) ? $errors : [];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran - DigiWash</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --warna-utama: #033B59;
            --warna-latar: #F0EFEF;
            --warna-total: #019D13;
            --garis: #D2CBCB;
        }

        * { box-sizing: border-box; }

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
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            color: #000; text-decoration: none; font-size: 22px; flex-shrink: 0;
        }

        .page-title {
            position: absolute; left: 50%; transform: translateX(-50%);
            margin: 0; color: #000; font-size: 20px; font-weight: 600;
            line-height: 1.3; white-space: nowrap;
        }

        .content { padding: 0 24px; }

        /* Alert error */
        .alert-err {
            display: flex; gap: 10px; align-items: flex-start;
            background: #FDECEC; border: 1px solid #F3B8B8; color: #9B1C1C;
            border-radius: 10px; padding: 10px 14px; margin-bottom: 14px;
            font-size: 13px; line-height: 1.5;
        }
        .alert-err i { margin-top: 2px; }
        .alert-err ul { margin: 0; padding: 0; list-style: none; }

        /* Kartu */
        .card-box {
            background: #fff; border: 1px solid var(--garis); border-radius: 12px;
            padding: 16px 18px; margin-bottom: 16px;
        }

        .card-title {
            margin: 0 0 10px; font-size: 14px; font-weight: 700; color: #222;
        }

        .row-info {
            display: flex; align-items: center; justify-content: space-between;
            gap: 14px; padding: 8px 0; border-bottom: 1px solid #f3f3f3;
            font-size: 13px;
        }
        .row-info:last-child { border-bottom: none; padding-bottom: 0; }
        .row-info .k { color: #666; font-weight: 400; }
        .row-info .v { color: #000; font-weight: 600; text-align: right; }
        .row-info .v.total { color: var(--warna-total); font-size: 20px; font-weight: 800; }

        /* Rekening tujuan */
        .rek-box {
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
            background: #EBF5FB; border-radius: 10px; padding: 12px 14px; margin-top: 12px;
        }
        .rek-label { font-size: 11px; color: #5b7283; margin-bottom: 2px; }
        .rek-no { font-size: 18px; font-weight: 700; color: var(--warna-utama); letter-spacing: 0.5px; }
        .btn-salin {
            border: 1px solid var(--warna-utama); background: #fff; color: var(--warna-utama);
            border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 600;
            cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
            transition: background 0.15s; white-space: nowrap;
        }
        .btn-salin:hover { background: #f0f7ff; }
        .btn-salin.ok { background: var(--warna-utama); color: #fff; }

        .catatan {
            margin: 12px 0 0; font-size: 12px; color: #666; line-height: 1.5;
        }
        .catatan i { color: var(--warna-utama); margin-right: 4px; }

        /* Dropzone */
        .dropzone {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 6px; text-align: center; cursor: pointer;
            border: 2px dashed #9DB6C6; border-radius: 12px; background: #F8FBFD;
            padding: 26px 16px; transition: border-color 0.15s, background 0.15s;
        }
        .dropzone:hover, .dropzone.drag { border-color: var(--warna-utama); background: #EEF6FB; }
        .dropzone.invalid { border-color: #D64545; background: #FFF6F6; }
        .dropzone .ikon { font-size: 34px; color: var(--warna-utama); line-height: 1; }
        .dropzone .judul { font-size: 14px; font-weight: 600; color: #222; }
        .dropzone .sub { font-size: 12px; color: #777; }

        .dropzone:focus-within { outline: 2px solid var(--warna-utama); outline-offset: 2px; }
        .file-input { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }

        /* Preview */
        .preview { display: none; }
        .preview.tampil { display: block; }
        .preview-img-wrap {
            background: #f1f1f1; border-radius: 10px; overflow: hidden;
            display: flex; justify-content: center; max-height: 280px;
        }
        .preview-img-wrap img { max-width: 100%; max-height: 280px; object-fit: contain; display: block; }
        .preview-meta {
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
            margin-top: 10px; font-size: 12px; color: #444;
        }
        .preview-nama { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500; }
        .btn-ganti {
            border: none; background: none; color: var(--warna-utama); font-weight: 600;
            font-size: 12px; cursor: pointer; padding: 0; white-space: nowrap;
        }

        .pesan-file { display: none; margin: 8px 0 0; font-size: 12px; color: #C23030; }
        .pesan-file.tampil { display: block; }

        /* Tombol kirim */
        .btn-kirim {
            width: 100%; height: 48px; border: none; border-radius: 10px;
            background: var(--warna-utama); color: #fff; font-size: 16px; font-weight: 700;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            cursor: pointer; transition: background 0.2s, opacity 0.2s;
        }
        .btn-kirim:hover:not(:disabled) { background: #022c44; }
        .btn-kirim:disabled { opacity: 0.5; cursor: not-allowed; }

        .link-ganti-metode {
            display: block; text-align: center; margin-top: 12px;
            font-size: 13px; font-weight: 600; color: var(--warna-utama); text-decoration: none;
        }
        .link-ganti-metode:hover { text-decoration: underline; }

        .link-batal-transaksi {
            display: inline-block; margin-top: 10px;
            font-size: 13px; font-weight: 500; color: #dc2626;
            background: none; border: none; cursor: pointer; text-decoration: none;
            font-family: inherit;
        }
        .link-batal-transaksi:hover { text-decoration: underline; color: #b91c1c; }

        /* ===== Tablet & desktop ===== */
        @media (min-width: 768px) {
            body { padding: 0 24px 32px; }

            .mobile-wrap { max-width: 920px; min-height: auto; padding-bottom: 0; }

            .page-header { padding: 32px 8px 24px; }

            .content {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                column-gap: 24px;
                align-items: start;
                padding: 0 8px;
            }

            .alert-err { grid-column: 1 / -1; }

            .col-kanan { position: sticky; top: 24px; }

            .card-box { padding: 20px 22px; }
            .dropzone { padding: 36px 16px; }
        }

        /* Laptop layar pendek */
        @media (min-width: 768px) and (max-height: 600px) {
            .page-header { padding: 16px 8px 12px; }
            .dropzone { padding: 20px 16px; }
            .preview-img-wrap, .preview-img-wrap img { max-height: 180px; }
        }

        @media (max-width: 360px) {
            .page-header { padding: 40px 14px 18px; }
            .page-title { font-size: 18px; }
            .content { padding: 0 16px; }
            .rek-no { font-size: 16px; }
        }
    </style>
</head>

<body>

<div class="mobile-wrap">

    <!-- Header -->
    <div class="page-header">
        <a href="<?= base_url('dashboard') ?>" class="btn-back" title="Kembali ke Beranda" aria-label="Kembali">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
        </a>
        <h1 class="page-title">Upload Bukti Bayar</h1>
    </div>

    <div class="content">

        <?php if ($errors !== []): ?>
            <div class="alert-err" role="alert">
                <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
                <ul>
                    <?php foreach ($errors as $pesan): ?>
                        <li><?= esc((string) $pesan) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- ══ Kiri: tagihan & tujuan transfer ══ -->
        <div class="col-kiri">
            <div class="card-box">
                <h2 class="card-title">Tagihan</h2>

                <div class="row-info">
                    <span class="k">ID Pesanan</span>
                    <span class="v"><?= esc((string) $id_pesanan) ?></span>
                </div>
                <div class="row-info">
                    <span class="k">Layanan</span>
                    <span class="v">Laundry <?= esc((string) $layanan) ?></span>
                </div>
                <div class="row-info">
                    <span class="k">Metode</span>
                    <span class="v"><?= esc((string) $metode) ?></span>
                </div>
                <div class="row-info">
                    <span class="k">Total Bayar</span>
                    <span class="v total">Rp <?= number_format((int) $total, 0, ',', '.') ?></span>
                </div>

                <div class="rek-box">
                    <div>
                        <div class="rek-label">Transfer ke <?= esc((string) $metode) ?></div>
                        <div class="rek-no" id="noRek"><?= esc((string) $rekening) ?></div>
                    </div>
                    <button type="button" class="btn-salin" id="btnSalin">
                        <i class="bi bi-clipboard" aria-hidden="true"></i><span>Salin</span>
                    </button>
                </div>

                <p class="catatan">
                    <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                    Transfer sesuai total tagihan, lalu unggah foto atau screenshot bukti transfernya.
                </p>
            </div>
        </div>

        <!-- ══ Kanan: upload ══ -->
        <div class="col-kanan">
            <form action="<?= base_url('pesanan/simpan-bukti' . (!empty($id_pesanan_db) ? '/' . $id_pesanan_db : '')) ?>" method="post"
                  enctype="multipart/form-data" id="formBukti" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="id_pesanan" value="<?= esc((string) ($id_pesanan_db ?? '')) ?>">

                <div class="card-box">
                    <h2 class="card-title">Bukti Pembayaran</h2>

                    <input type="file" name="bukti" id="inputBukti" class="file-input"
                           accept="image/jpeg,image/png,image/webp">

                    <!-- Dropzone (tampil sebelum ada file) -->
                    <label for="inputBukti" class="dropzone" id="dropzone">
                        <i class="bi bi-cloud-arrow-up-fill ikon" aria-hidden="true"></i>
                        <span class="judul">Pilih foto bukti transfer</span>
                        <span class="sub">JPG, PNG, atau WEBP · maksimal 2 MB</span>
                    </label>

                    <!-- Preview (tampil setelah ada file) -->
                    <div class="preview" id="preview">
                        <div class="preview-img-wrap">
                            <img src="" alt="Pratinjau bukti pembayaran" id="previewImg">
                        </div>
                        <div class="preview-meta">
                            <span class="preview-nama" id="previewNama"></span>
                            <button type="button" class="btn-ganti" id="btnGanti">Ganti foto</button>
                        </div>
                    </div>

                    <p class="pesan-file" id="pesanFile" role="alert"></p>
                </div>

                <button type="submit" class="btn-kirim" id="btnKirim" disabled>
                    <i class="bi bi-send-fill" aria-hidden="true"></i>
                    <span>Kirim Bukti Pembayaran</span>
                </button>

                <a href="<?= base_url('pesanan/pembayaran' . (!empty($id_pesanan_db) ? '/' . $id_pesanan_db : '') . '?from=upload-bukti') ?>" class="link-ganti-metode">
                    Ganti metode pembayaran
                </a>

                <?php if (!empty($id_pesanan_db)): ?>
                    <div style="text-align: center;">
                        <button type="button" class="link-batal-transaksi" onclick="konfirmasiBatalUpload()">
                            <i class="bi bi-x-circle me-1"></i> Batalkan pesanan
                        </button>
                    </div>
                <?php endif; ?>
            </form>

            <?php if (!empty($id_pesanan_db)): ?>
                <form action="<?= base_url('pesanan/batalkan/' . $id_pesanan_db) ?>" method="post" id="formBatalUpload" style="display:none;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_pesanan" value="<?= $id_pesanan_db ?>">
                </form>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    (function () {
        var MAKS_BYTE = 2 * 1024 * 1024;
        var TIPE_OK   = ['image/jpeg', 'image/png', 'image/webp'];

        var input    = document.getElementById('inputBukti');
        var zone     = document.getElementById('dropzone');
        var preview  = document.getElementById('preview');
        var img      = document.getElementById('previewImg');
        var nama     = document.getElementById('previewNama');
        var pesan    = document.getElementById('pesanFile');
        var btnKirim = document.getElementById('btnKirim');
        var btnGanti = document.getElementById('btnGanti');
        var form     = document.getElementById('formBukti');
        var urlLama  = null;

        function tampilkanError(teks) {
            pesan.textContent = teks;
            pesan.classList.add('tampil');
            zone.classList.add('invalid');
        }

        function reset() {
            input.value = '';
            if (urlLama) { URL.revokeObjectURL(urlLama); urlLama = null; }
            img.removeAttribute('src');
            preview.classList.remove('tampil');
            zone.style.display = '';
            btnKirim.disabled = true;
        }

        function ukuranTeks(byte) {
            return byte >= 1024 * 1024
                ? (byte / 1024 / 1024).toFixed(1) + ' MB'
                : Math.max(1, Math.round(byte / 1024)) + ' KB';
        }

        function proses(file) {
            pesan.classList.remove('tampil');
            zone.classList.remove('invalid');

            if (!file) { reset(); return; }

            if (TIPE_OK.indexOf(file.type) === -1) {
                reset();
                tampilkanError('Format file harus JPG, PNG, atau WEBP.');
                return;
            }
            if (file.size > MAKS_BYTE) {
                reset();
                tampilkanError('Ukuran file ' + ukuranTeks(file.size) + ' — maksimal 2 MB.');
                return;
            }

            if (urlLama) { URL.revokeObjectURL(urlLama); }
            urlLama = URL.createObjectURL(file);
            img.src = urlLama;
            nama.textContent = file.name + ' (' + ukuranTeks(file.size) + ')';
            zone.style.display = 'none';
            preview.classList.add('tampil');
            btnKirim.disabled = false;
        }

        input.addEventListener('change', function () { proses(input.files[0]); });
        btnGanti.addEventListener('click', function () { input.click(); });

        /* Drag & drop (desktop) */
        ['dragenter', 'dragover'].forEach(function (ev) {
            zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('drag'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.remove('drag'); });
        });
        zone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                proses(input.files[0]);
            }
        });

        /* Cegah kirim ganda */
        form.addEventListener('submit', function (e) {
            if (!input.files.length) {
                e.preventDefault();
                tampilkanError('Pilih foto bukti pembayaran terlebih dahulu.');
                return;
            }
            btnKirim.disabled = true;
            btnKirim.querySelector('span').textContent = 'Mengirim...';
        });

        /* Salin nomor rekening */
        var btnSalin = document.getElementById('btnSalin');
        if (btnSalin) {
            btnSalin.addEventListener('click', function () {
                var teks = document.getElementById('noRek').textContent.trim();
                var label = btnSalin.querySelector('span');

                function berhasil() {
                    label.textContent = 'Tersalin';
                    btnSalin.classList.add('ok');
                    setTimeout(function () {
                        label.textContent = 'Salin';
                        btnSalin.classList.remove('ok');
                    }, 1800);
                }

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(teks).then(berhasil);
                } else {
                    var t = document.createElement('textarea');
                    t.value = teks; document.body.appendChild(t); t.select();
                    try { document.execCommand('copy'); berhasil(); } catch (err) {}
                    document.body.removeChild(t);
                }
            });
        }
    })();

    function konfirmasiBatalUpload() {
        if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini sebelum melakukan pembayaran?')) {
            var f = document.getElementById('formBatalUpload');
            if (f) f.submit();
        }
    }
</script>
</body>

</html>