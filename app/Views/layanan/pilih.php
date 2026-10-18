<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiWash – Pilih Layanan</title>

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
            letter-spacing: .3px;
        }

        /* ─── Card Layanan ─── */
        .layanan-card {
            background: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07);
            cursor: pointer;
            transition: box-shadow .15s, transform .15s;
            text-decoration: none;
            color: inherit;
        }
        .layanan-card:hover {
            box-shadow: 0 4px 16px rgba(13,27,75,.12);
            transform: translateY(-2px);
        }
        .layanan-card .ikon-wrap {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            background: #e8eaf6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            flex-shrink: 0;
        }
        .layanan-card .nama {
            font-weight: 700;
            font-size: .95rem;
            color: #1a1a2e;
            margin: 0 0 2px;
        }
        .layanan-card .deskripsi {
            font-size: .8rem;
            color: #6b7280;
            margin: 0;
        }
        .layanan-card .arrow {
            margin-left: auto;
            color: #9ca3af;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="dw-topbar">
    <a href="<?= base_url('dashboard') ?>" class="back-btn" aria-label="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h6>Pilih Layanan</h6>
</div>

<!-- Konten utama – dibatasi lebar 480px agar presisi seperti tampilan mobile -->
<div class="container my-4" style="max-width: 480px;">

    <?php
    // Pesan flash jika ada
    $flash = session()->getFlashdata('pesan');
    if ($flash): ?>
        <div class="alert alert-info alert-dismissible fade show py-2 mb-3" role="alert">
            <?= esc($flash) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <!-- Label section -->
    <p class="text-muted small mb-3 px-1">Pilih jenis layanan laundry yang sesuai kebutuhanmu.</p>

    <!-- Daftar Layanan -->
    <div class="d-flex flex-column gap-3">
        <?php foreach ($daftarLayanan as $layanan): ?>
        <a href="<?= base_url('layanan/buat-pesanan?layanan=' . esc($layanan['id'])) ?>"
           class="layanan-card"
           aria-label="Pilih <?= esc($layanan['nama']) ?>">

            <div class="ikon-wrap">
                <?= $layanan['ikon'] ?>
            </div>

            <div class="flex-grow-1">
                <p class="nama"><?= esc($layanan['nama']) ?></p>
                <p class="deskripsi"><?= esc($layanan['deskripsi']) ?></p>
            </div>

            <i class="bi bi-chevron-right arrow"></i>
        </a>
        <?php endforeach; ?>
    </div>

</div>

<!-- Bootstrap JS Bundle (termasuk Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmHRNi4a8g8CRs+y5VqBjiqRJ1r4"
        crossorigin="anonymous"></script>
</body>
</html>
