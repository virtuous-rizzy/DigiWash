<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Layanan - DigiWash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: #EFEEEE;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            min-height: 100vh;
            padding: 0;
        }

        .mobile-wrap {
            width: 100%;
            max-width: 402px;
            background: #EFEEEE;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            align-items: center;
            padding: 54px 18px 24px;
            position: relative;
        }

        .btn-back {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            text-decoration: none;
            color: #000;
            font-size: 20px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .page-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 20px;
            line-height: 22px;
            color: #000;
            white-space: nowrap;
        }

        .card-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 0 28px 40px;
        }

        .service-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #FFFFFF;
            border-radius: 10px;
            padding: 16px;
            text-decoration: none;
            color: inherit;
            min-height: 134px;
            transition: box-shadow 0.2s, transform 0.15s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .service-card:hover {
            box-shadow: 0 4px 16px rgba(3,59,89,0.15);
            transform: translateY(-1px);
            color: inherit;
        }

        .service-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
        }

        .service-img-emoji {
            width: 82px;
            height: 82px;
            border-radius: 8px;
            background: #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            flex-shrink: 0;
        }

        .service-img-emoji i {
            color: #033B59;
            line-height: 1;
        }

        .service-text { flex: 1; }

        .service-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: #000;
            line-height: 1.3;
        }

        .service-desc {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 13px;
            color: #444;
            margin-top: 4px;
            line-height: 1.4;
        }

        .radio-dot {
            width: 13px;
            height: 13px;
            border-radius: 50%;
            border: 1px solid #999393;
            background: #fff;
            flex-shrink: 0;
            margin-left: 8px;
        }

        /* ===== Tablet & Desktop: melebar, kartu jadi grid 2 kolom ===== */
        @media (min-width: 768px) {
            .mobile-wrap { max-width: 960px; min-height: auto; }

            .page-header { padding: 32px 40px 24px; }

            .card-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 0 40px 40px;
            }

            .service-card { min-height: 120px; }
        }

        /* Layar pendek (laptop kecil): rapatkan sedikit supaya tidak scroll */
        @media (min-width: 768px) and (max-height: 600px) {
            .page-header { padding: 20px 40px 16px; }
            .card-list { gap: 14px; padding-bottom: 24px; }
            .service-card { min-height: 100px; }
            .service-img-emoji { width: 64px; height: 64px; font-size: 30px; }
        }
    </style>
</head>
<body>
    <div class="mobile-wrap">
        <div class="page-header">
            <a href="<?= base_url('dashboard') ?>" class="btn-back">&#8592;</a>
            <span class="page-title">Pilih Layanan</span>
        </div>

        <div class="card-list">
            <?php foreach (($daftarLayanan ?? []) as $row): 
                $namaRaw   = (string) ($row['nama_layanan'] ?? 'Reguler');
                $namaParam = str_ireplace(['Laundry ', ' '], ['', ''], $namaRaw);
                $deskripsi = (string) ($row['deskripsi'] ?? ('Estimasi ' . ($row['estimasi_hari'] ?? 3) . ' hari'));
                $ikon      = (string) ($row['ikon'] ?? 'bi-basket2-fill');
                $idLayanan = (int) ($row['id_layanan'] ?? 1);
            ?>
                <a href="<?= base_url('pesanan/buat-pesanan?layanan=' . urlencode($namaParam) . '&id=' . $idLayanan) ?>" class="service-card">
                    <div class="service-left">
                        <div class="service-img-emoji"><i class="bi <?= esc($ikon) ?>"></i></div>
                        <div class="service-text">
                            <div class="service-name"><?= esc($namaRaw) ?></div>
                            <div class="service-desc"><?= nl2br(esc($deskripsi)) ?></div>
                        </div>
                    </div>
                    <div class="radio-dot"></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>