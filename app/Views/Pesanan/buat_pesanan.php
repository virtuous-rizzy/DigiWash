<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan - DigiWash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Roboto:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
=======
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
>>>>>>> origin/digiwash-semoga-final
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #F0EFEF;
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif;
=======
            font-family: 'Poppins', sans-serif;
>>>>>>> origin/digiwash-semoga-final
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }
        .mobile-wrap {
            width: 100%;
            max-width: 402px;
            background: #F0EFEF;
            min-height: 100vh;
            padding-bottom: 40px;
        }

        /* ── Header ── */
        .page-header {
            display: flex;
            align-items: center;
            padding: 54px 24px 20px;
            position: relative;
        }
        .btn-back {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; color: #000;
            font-size: 20px; font-weight: 700; flex-shrink: 0;
        }
        .page-title {
            position: absolute; left: 50%; transform: translateX(-50%);
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 600;
=======
            font-family: 'Poppins', sans-serif; font-weight: 600;
>>>>>>> origin/digiwash-semoga-final
            font-size: 20px; line-height: 22px; color: #000; white-space: nowrap;
        }

        /* ── Form area ── */
        .form-area { padding: 0 23px; }

        .section-label {
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 600;
=======
            font-family: 'Poppins', sans-serif; font-weight: 600;
>>>>>>> origin/digiwash-semoga-final
            font-size: 13px; line-height: 22px; color: #000;
            display: block; margin-bottom: 6px;
        }

        /* ── Service box ── */
        .service-box {
            background: #fff; border-radius: 10px; padding: 14px 17px;
            margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .service-box-label {
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 600;
=======
            font-family: 'Poppins', sans-serif; font-weight: 600;
>>>>>>> origin/digiwash-semoga-final
            font-size: 13px; line-height: 22px; color: #000; margin-bottom: 4px;
        }
        .service-box-inner {
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
        }
        .service-box-icon { font-size: 32px; flex-shrink: 0; line-height: 1; }
        .service-box-icon i { color: #033B59; }
        .service-box-info { flex: 1; }
        .service-box-name {
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 600;
            font-size: 13px; line-height: 22px; color: #000;
        }
        .service-box-price {
            font-family: 'Roboto', sans-serif; font-weight: 400;
            font-size: 13px; line-height: 22px; color: #555;
        }
        .btn-ubah {
            font-family: 'Roboto', sans-serif; font-weight: 600;
=======
            font-family: 'Poppins', sans-serif; font-weight: 600;
            font-size: 13px; line-height: 22px; color: #000;
        }
        .service-box-price {
            font-family: 'Poppins', sans-serif; font-weight: 400;
            font-size: 13px; line-height: 22px; color: #555;
        }
        .btn-ubah {
            font-family: 'Poppins', sans-serif; font-weight: 600;
>>>>>>> origin/digiwash-semoga-final
            font-size: 15px; line-height: 22px; color: #033B59;
            text-decoration: none; white-space: nowrap;
            background: none; border: none; cursor: pointer; padding: 0;
        }

        /* ── Weight counter (Kiloan) ── */
        .weight-group {
            background: #fff; border: 1px solid #D2CBCB; border-radius: 10px;
            height: 46px; display: flex; align-items: center;
            justify-content: space-between; overflow: hidden; margin-bottom: 20px;
        }
        .counter-btn {
            width: 67px; height: 46px; background: transparent; border: none;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 24px;
            color: #000; cursor: pointer; display: flex; align-items: center;
            justify-content: center; transition: background 0.15s;
        }
        .counter-btn:hover { background: #f0eeee; }
        .counter-divider { width: 1px; height: 46px; background: #000; }
        .counter-value {
            flex: 1; text-align: center;
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 300; font-size: 20px;
=======
            font-family: 'Poppins', sans-serif; font-weight: 300; font-size: 20px;
>>>>>>> origin/digiwash-semoga-final
            color: #000; border: none; background: transparent; outline: none;
        }

        /* ── Alamat info card ── */
        .info-card {
            background: #fff; border: 1px solid #D2CBCB; border-radius: 10px;
            padding: 10px 17px; margin-bottom: 20px;
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 8px; min-height: 55px;
        }
        .info-card-addr {
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 400;
=======
            font-family: 'Poppins', sans-serif; font-weight: 400;
>>>>>>> origin/digiwash-semoga-final
            font-size: 12px; line-height: 22px; color: #000; flex: 1;
        }

        /* ── Input / Textarea ── */
        .input-box {
            background: #fff; border: 1px solid #D2CBCB; border-radius: 10px;
            height: 41px; width: 100%; padding: 8px 17px;
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 400;
=======
            font-family: 'Poppins', sans-serif; font-weight: 400;
>>>>>>> origin/digiwash-semoga-final
            font-size: 13px; color: #000; outline: none; margin-bottom: 20px;
        }
        .input-box:focus { border-color: #033B59; }
        .textarea-box {
            background: #fff; border: 1px solid #D2CBCB; border-radius: 10px;
            width: 100%; padding: 10px 17px;
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 200;
=======
            font-family: 'Poppins', sans-serif; font-weight: 200;
>>>>>>> origin/digiwash-semoga-final
            font-size: 13px; color: #000; outline: none;
            resize: vertical; min-height: 41px; margin-bottom: 20px;
        }
        .textarea-box::placeholder { color: #aaa; }
        .input-icon-group { position: relative; margin-bottom: 20px; }
        .input-icon-group .input-box { padding-right: 44px; margin-bottom: 0; }
        .input-icon {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%); font-size: 16px; color: #033B59; opacity: 0.6;
            pointer-events: none; line-height: 1;
        }
<<<<<<< HEAD
=======
        .input-date-box {
            cursor: pointer;
            position: relative;
        }
        .input-date-box::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }
>>>>>>> origin/digiwash-semoga-final

        /* ── Cost box ── */
        .cost-box {
            background: #CEE4F0; border-radius: 15px; padding: 14px 18px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .cost-label { font-weight: 700; font-size: 16px; line-height: 22px; color: #000; }
        .cost-detail { font-weight: 700; font-size: 16px; line-height: 22px; color: #000; margin-top: 2px; }
        .cost-total { font-weight: 700; font-size: 24px; line-height: 22px; color: #019D13; }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%; height: 41px; background: #033B59;
            border: 1px solid #D2CBCB; border-radius: 10px;
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 700;
=======
            font-family: 'Poppins', sans-serif; font-weight: 700;
>>>>>>> origin/digiwash-semoga-final
            font-size: 16px; color: #fff; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s;
        }
        .btn-submit:hover { background: #022c44; }

        /* ── Satuan grid ── */
        .satuan-banner {
            background: #E8F4FD; border-radius: 10px; padding: 12px 17px;
            margin-bottom: 16px; text-align: center;
<<<<<<< HEAD
            font-family: 'Roboto', sans-serif; font-weight: 600;
=======
            font-family: 'Poppins', sans-serif; font-weight: 600;
>>>>>>> origin/digiwash-semoga-final
            font-size: 13px; line-height: 22px; color: #033B59;
        }
        .satuan-banner i { margin-right: 6px; }
        .satuan-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 12px; margin-bottom: 20px;
        }
        .satuan-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 12px 10px 10px; text-align: center;
            transition: border-color 0.2s, background 0.2s;
        }
        .satuan-card:hover { border-color: #033B59; }
        .satuan-card.active { border-color: #033B59; background: #F3F9FD; }
        .satuan-icon { font-size: 28px; margin-bottom: 4px; line-height: 1.2; }
        .satuan-icon i { color: #033B59; }
        .satuan-name { font-weight: 600; font-size: 12px; color: #1e293b; }
        .satuan-price { font-weight: 400; font-size: 11px; color: #64748b; margin: 2px 0 8px; }
        .satuan-counter { display: flex; align-items: center; justify-content: center; gap: 8px; }
        .satuan-btn {
            width: 28px; height: 28px; border-radius: 6px;
            border: 1px solid #D2CBCB; background: #fff;
            font-weight: 700; font-size: 16px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s;
        }
        .satuan-btn:hover { background: #f0eeee; }
        .satuan-qty { font-weight: 700; font-size: 14px; min-width: 20px; text-align: center; }
        .subtotal-bar {
            background: #CEE4F0; border-radius: 15px; padding: 10px 18px;
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;
        }
        .subtotal-label { font-weight: 700; font-size: 13px; color: #033B59; }
        .subtotal-val { font-weight: 700; font-size: 14px; color: #019D13; }

<<<<<<< HEAD
        /* ── BS5 Modal overrides ── */
        .modal-content { border-radius: 14px; }
        .modal-header { border-bottom: 1px solid #f0eeee; padding: 16px 20px 12px; }
        .modal-title { font-family: 'Roboto', sans-serif; font-weight: 700; font-size: 16px; color: #033B59; }
=======
        /* ── Kartu pilih alamat di modal ── */
        .alamat-pilih-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            background: #fff;
            transition: border-color 0.15s, background 0.15s;
            user-select: none;
        }
        .alamat-pilih-card:hover {
            border-color: #033B59;
            background: #f0f7ff;
        }


        /* ── BS5 Modal overrides ── */
        .modal-content { border-radius: 14px; }
        .modal-header { border-bottom: 1px solid #f0eeee; padding: 16px 20px 12px; }
        .modal-title { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 16px; color: #033B59; }
>>>>>>> origin/digiwash-semoga-final
        .modal-title i { margin-right: 6px; }
        .modal-body { padding: 16px 20px; font-size: 13px; color: #333; }
        .modal-footer { border-top: 1px solid #f0eeee; padding: 12px 20px; gap: 10px; }

        /* =====================================================
           RESPONSIVE
           HP (< 600px)   : persis seperti mockup
           Tablet portrait: kolom tunggal, sedikit lebih lebar
           Desktop (>=900): 2 kolom — form kiri, ringkasan biaya
                            menempel (sticky) di kanan
           ===================================================== */
        @media (min-width: 600px) and (max-width: 899px) {
            .mobile-wrap { max-width: 560px; }
        }

        @media (min-width: 900px) {
            .mobile-wrap { max-width: 1080px; min-height: auto; padding-bottom: 48px; }

            .page-header { padding: 32px 40px 24px; }
            .form-area   { padding: 0 40px; }

            #formPesanan {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 340px;
                gap: 32px;
                align-items: start;
            }

            /* panel ringkasan ikut turun saat halaman di-scroll */
            .col-side { position: sticky; top: 24px; }

            /* alamat & tanggal sejajar */
            .meta-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                column-gap: 20px;
            }

            .satuan-grid { grid-template-columns: repeat(4, 1fr); }
            .satuan-icon { font-size: 30px; }

            /* total sudah tampil di panel kanan */
            .subtotal-bar { display: none; }

            .service-box-icon {
                width: 56px; height: 56px; border-radius: 12px;
                background: #E8F4FD;
                display: flex; align-items: center; justify-content: center;
                font-size: 28px;
            }

            /* panel ringkasan */
            .cost-box {
                flex-direction: column;
                align-items: flex-start;
                gap: 18px;
                background: #033B59;
                border-radius: 16px;
                padding: 26px 24px;
                margin-bottom: 14px;
            }
            .cost-label  { color: #CEE4F0; font-size: 14px; font-weight: 500; }
            .cost-detail { color: #fff; font-size: 16px; font-weight: 500; }
            .cost-total  { color: #8BE79A; font-size: 38px; line-height: 1.1; }

            .btn-submit {
                height: 50px;
                background: #CEE4F0;
                color: #033B59;
                border-color: transparent;
            }
            .btn-submit:hover { background: #b8d8ea; }
        }
    </style>
</head>
<body>
<div class="mobile-wrap">

    <!-- Header -->
    <div class="page-header">
        <a href="<?= base_url('pesanan/pilih-layanan') ?>" class="btn-back">&#8592;</a>
        <span class="page-title">Buat Pesanan</span>
    </div>

    <div class="form-area">
<<<<<<< HEAD
=======
        <?php
            $alamatUtama = (string) ($alamat ?? '');
            $alamatAlt   = (string) ($alamat_alternatif ?? '');
            // Pilih default: utama jika ada, alternatif jika utama kosong
            $alamatAwal  = $alamatUtama !== '' ? $alamatUtama : $alamatAlt;
        ?>
>>>>>>> origin/digiwash-semoga-final
        <form action="<?= base_url('pesanan/ringkasan') ?>" method="post" id="formPesanan">
            <?= csrf_field() ?>
            <!-- Hidden inputs – JANGAN DIHAPUS -->
            <input type="hidden" name="id_layanan" value="<?= (int) ($id_layanan ?? 1) ?>">
            <input type="hidden" name="layanan" value="<?= (string) $layanan ?>">
<<<<<<< HEAD
            <input type="hidden" name="alamat" id="inputAlamat" value="<?= (string) $alamat ?>">
=======
            <input type="hidden" name="alamat" id="inputAlamat" value="<?= htmlspecialchars($alamatAwal, ENT_QUOTES) ?>">
>>>>>>> origin/digiwash-semoga-final
            <input type="hidden" name="tanggal_penjemputan" value="<?= (string) $tanggal ?>">
            <input type="hidden" id="harga_per_kg" value="<?= (int) $harga_per_kg ?>">
            <input type="hidden" id="detail_satuan" name="detail_satuan" value="">

            <!-- ══ KOLOM KIRI: FORM ══ -->
            <div class="col-main">

                <!-- ── SERVICE BOX ── -->
                <div class="service-box">
                    <div class="service-box-label">Layanan</div>
                    <div class="service-box-inner">
                        <?php
                            if ($layanan === 'Express')      $ikon = 'bi-lightning-charge-fill';
                            elseif ($layanan === 'BedCover') $ikon = 'bi-layers-fill';
                            elseif ($layanan === 'Satuan')   $ikon = 'bi-tag-fill';
                            else                             $ikon = 'bi-basket2-fill';
                        ?>
                        <span class="service-box-icon"><i class="bi <?= $ikon ?>"></i></span>
                        <div class="service-box-info">
                            <div class="service-box-name">Laundry <?= (string) $layanan ?></div>
                            <div class="service-box-price">
                                <?= ($layanan === 'Satuan')
                                    ? 'Pencucian per item'
                                    : 'Rp ' . number_format((int) $harga_per_kg, 0, ',', '.') . ' / Kg' ?>
                            </div>
                        </div>
                        <a href="<?= base_url('pesanan/pilih-layanan') ?>" class="btn-ubah">Ubah</a>
                    </div>
                </div>

                <?php if ($layanan === 'Satuan'): ?>
                <!-- ══ SATUAN MODE ══ -->
                <div class="satuan-banner">
                    <i class="bi bi-lightbulb-fill"></i>Harga per item — pilih item yang ingin dicuci
                </div>

                <span class="section-label">Pilih Item Satuan</span>
                <div class="satuan-grid">

                    <div class="satuan-card">
                        <div class="satuan-icon"><i class="bi bi-briefcase-fill"></i></div>
                        <div class="satuan-name">Jas Formal</div>
                        <div class="satuan-price">Rp 25.000</div>
                        <div class="satuan-counter">
                            <button type="button" class="satuan-btn" onclick="updateSatuan('jas',-1)">−</button>
                            <span id="qty_jas" class="satuan-qty">0</span>
                            <button type="button" class="satuan-btn" onclick="updateSatuan('jas',1)">+</button>
                        </div>
                    </div>

                    <div class="satuan-card">
                        <div class="satuan-icon"><i class="bi bi-snow2"></i></div>
                        <div class="satuan-name">Jaket</div>
                        <div class="satuan-price">Rp 15.000</div>
                        <div class="satuan-counter">
                            <button type="button" class="satuan-btn" onclick="updateSatuan('jaket',-1)">−</button>
                            <span id="qty_jaket" class="satuan-qty">0</span>
                            <button type="button" class="satuan-btn" onclick="updateSatuan('jaket',1)">+</button>
                        </div>
                    </div>

                    <div class="satuan-card">
                        <div class="satuan-icon"><i class="bi bi-gem"></i></div>
                        <div class="satuan-name">Gaun Pesta</div>
                        <div class="satuan-price">Rp 35.000</div>
                        <div class="satuan-counter">
                            <button type="button" class="satuan-btn" onclick="updateSatuan('gaun',-1)">−</button>
                            <span id="qty_gaun" class="satuan-qty">1</span>
                            <button type="button" class="satuan-btn" onclick="updateSatuan('gaun',1)">+</button>
                        </div>
                    </div>

                    <div class="satuan-card">
                        <div class="satuan-icon"><i class="bi bi-person-walking"></i></div>
                        <div class="satuan-name">Sepatu</div>
                        <div class="satuan-price">Rp 10.000</div>
                        <div class="satuan-counter">
                            <button type="button" class="satuan-btn" onclick="updateSatuan('sepatu',-1)">−</button>
                            <span id="qty_sepatu" class="satuan-qty">0</span>
                            <button type="button" class="satuan-btn" onclick="updateSatuan('sepatu',1)">+</button>
                        </div>
                    </div>

                    <div class="satuan-card">
                        <div class="satuan-icon"><i class="bi bi-moon-stars-fill"></i></div>
                        <div class="satuan-name">Selimut</div>
                        <div class="satuan-price">Rp 20.000</div>
                        <div class="satuan-counter">
                            <button type="button" class="satuan-btn" onclick="updateSatuan('selimut',-1)">−</button>
                            <span id="qty_selimut" class="satuan-qty">0</span>
                            <button type="button" class="satuan-btn" onclick="updateSatuan('selimut',1)">+</button>
                        </div>
                    </div>

                    <div class="satuan-card">
                        <div class="satuan-icon"><i class="bi bi-backpack2-fill"></i></div>
                        <div class="satuan-name">Tas Ransel</div>
                        <div class="satuan-price">Rp 18.000</div>
                        <div class="satuan-counter">
                            <button type="button" class="satuan-btn" onclick="updateSatuan('tas',-1)">−</button>
                            <span id="qty_tas" class="satuan-qty">0</span>
                            <button type="button" class="satuan-btn" onclick="updateSatuan('tas',1)">+</button>
                        </div>
                    </div>

                    <div class="satuan-card">
                        <div class="satuan-icon"><i class="bi bi-person-standing"></i></div>
                        <div class="satuan-name">Kaos Oblong</div>
                        <div class="satuan-price">Rp 8.000</div>
                        <div class="satuan-counter">
                            <button type="button" class="satuan-btn" onclick="updateSatuan('kaos',-1)">−</button>
                            <span id="qty_kaos" class="satuan-qty">0</span>
                            <button type="button" class="satuan-btn" onclick="updateSatuan('kaos',1)">+</button>
                        </div>
                    </div>

                    <div class="satuan-card">
                        <div class="satuan-icon"><i class="bi bi-plus-circle-fill"></i></div>
                        <div class="satuan-name">Lainnya</div>
                        <div class="satuan-price">Rp 12.000</div>
                        <div class="satuan-counter">
                            <button type="button" class="satuan-btn" onclick="updateSatuan('lainnya',-1)">−</button>
                            <span id="qty_lainnya" class="satuan-qty">0</span>
                            <button type="button" class="satuan-btn" onclick="updateSatuan('lainnya',1)">+</button>
                        </div>
                    </div>

                </div>

                <div class="subtotal-bar">
                    <span class="subtotal-label">Subtotal Item</span>
                    <span class="subtotal-val" id="subtotal_items">Rp 35.000</span>
                </div>

                <input type="hidden" name="berat" value="1">

                <?php else: ?>
                <!-- ══ KILOAN MODE ══ -->
                <span class="section-label">Berat Cucian (Kg)</span>
                <div class="weight-group">
                    <button type="button" class="counter-btn" onclick="ubahBerat(-1)">−</button>
                    <div class="counter-divider"></div>
                    <input type="number" id="berat" name="berat" class="counter-value" value="5" min="1" readonly>
                    <div class="counter-divider"></div>
                    <button type="button" class="counter-btn" onclick="ubahBerat(1)">+</button>
                </div>
                <?php endif; ?>

                <div class="meta-grid">
                    <!-- ── ALAMAT PENJEMPUTAN ── -->
                    <div>
                        <span class="section-label">Alamat Penjemputan</span>
                        <div class="info-card">
<<<<<<< HEAD
                            <div class="info-card-addr" id="displayAlamat"><?= (string) $alamat ?></div>
                            <button type="button" class="btn-ubah"
                                data-bs-toggle="modal" data-bs-target="#modalUbahAlamat">Ubah</button>
=======
                            <div class="info-card-addr" id="displayAlamat"><?= htmlspecialchars($alamatAwal, ENT_QUOTES) ?></div>
                            <button type="button" class="btn-ubah"
                                data-bs-toggle="modal" data-bs-target="#modalPilihAlamat">Ubah</button>
>>>>>>> origin/digiwash-semoga-final
                        </div>
                    </div>

                    <!-- ── TANGGAL PENJEMPUTAN ── -->
                    <div>
                        <span class="section-label">Tanggal Penjemputan</span>
                        <div class="input-icon-group">
<<<<<<< HEAD
                            <input type="text" class="input-box" value="<?= (string) $tanggal ?>" readonly>
                            <span class="input-icon"><i class="bi bi-calendar-event-fill"></i></span>
=======
                            <input type="date"
                                   id="tanggal_penjemputan"
                                   name="tanggal_penjemputan"
                                   class="input-box input-date-box"
                                   value="<?= esc((string) ($tanggalYmd ?? date('Y-m-d'))) ?>"
                                   min="<?= date('Y-m-d') ?>"
                                   onclick="if (this.showPicker) this.showPicker()"
                                   required>
                            <span class="input-icon" onclick="var el = document.getElementById('tanggal_penjemputan'); if (el.showPicker) { el.showPicker(); } else { el.focus(); }">
                                <i class="bi bi-calendar-event-fill"></i>
                            </span>
>>>>>>> origin/digiwash-semoga-final
                        </div>
                    </div>
                </div>

                <!-- ── CATATAN ── -->
                <span class="section-label">Catatan Tambahan (Opsional)</span>
                <textarea name="catatan" class="textarea-box"
                    placeholder="Contoh : Rumah Putih Mobil Agya putih"></textarea>

            </div><!-- /.col-main -->

            <!-- ══ KOLOM KANAN: RINGKASAN BIAYA + SUBMIT ══ -->
            <div class="col-side">
                <!-- ── RINCIAN BIAYA ── -->
                <div class="cost-box">
                    <div>
                        <div class="cost-label">Rincian Biaya</div>
                        <div class="cost-detail" id="text_calc">
                            5 Kg x Rp <?= number_format((int) $harga_per_kg, 0, ',', '.') ?>
                        </div>
                    </div>
                    <div class="cost-total" id="text_total">Rp 35.000</div>
                </div>
                <input type="hidden" id="total_harga" name="total_harga" value="35000">

                <!-- ── SUBMIT ── -->
                <button type="submit" class="btn-submit">Lanjut Ke Ringkasan</button>
            </div><!-- /.col-side -->

        </form>
    </div><!-- /.form-area -->
</div><!-- /.mobile-wrap -->

<<<<<<< HEAD
<!-- ══ Modal Ubah Alamat (Bootstrap 5) ══ -->
<div class="modal fade" id="modalUbahAlamat" tabindex="-1" aria-labelledby="modalUbahAlamatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUbahAlamatLabel"><i class="bi bi-geo-alt-fill"></i>Ubah Alamat Penjemputan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="inputAlamatBaru" class="form-label fw-semibold" style="font-size:13px; color:#333;">
                    Masukkan alamat baru:
                </label>
                <textarea id="inputAlamatBaru" class="form-control" rows="3"
                    style="font-size:13px; border-radius:10px; border-color:#D2CBCB;"
                    placeholder="Contoh: Jl. Mawar No. 5, RT 01/RW 03, Depok"></textarea>
                <div id="alamatError" class="text-danger mt-1" style="font-size:12px; display:none;">
                    Alamat tidak boleh kosong.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary rounded-3"
                    data-bs-dismiss="modal" style="font-size:13px;">Batal</button>
                <button type="button" class="btn rounded-3 fw-bold text-white"
                    style="background:#033B59; font-size:13px;" onclick="simpanAlamat()">
                    Simpan Alamat
=======
<!-- ══ Modal Pilih Alamat ══ -->
<div class="modal fade" id="modalPilihAlamat" tabindex="-1" aria-labelledby="modalPilihAlamatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9; padding:16px 20px;">
                <h5 class="modal-title fw-bold" id="modalPilihAlamatLabel" style="font-size:15px; color:#0f172a;">
                    <i class="bi bi-geo-alt-fill" style="color:#033B59; margin-right:6px;"></i>
                    Pilih Alamat Penjemputan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:16px 20px;">

                <?php if ($alamatUtama !== ''): ?>
                <div class="alamat-pilih-card" id="cardUtama" onclick="pilihAlamat('utama')" style="cursor:pointer;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                        <span style="font-weight:700; font-size:14px; color:#0f172a;">Rumah</span>
                        <span style="background:#033B59; color:#fff; font-size:10px; font-weight:700;
                               padding:2px 9px; border-radius:20px; letter-spacing:.5px;">UTAMA</span>
                        <i class="bi bi-check-circle-fill ms-auto" id="iconUtama"
                           style="color:#033B59; font-size:18px; display:none;"></i>
                    </div>
                    <div style="font-size:12px; color:#64748b; margin-bottom:4px;">
                        <?= htmlspecialchars((string)($nama_user ?? ''), ENT_QUOTES) ?>
                    </div>
                    <div style="font-size:13px; font-weight:600; color:#1e293b; line-height:1.5;">
                        <?= htmlspecialchars($alamatUtama, ENT_QUOTES) ?>
                    </div>
                </div>
                <?php else: ?>
                <div style="padding:14px; background:#f8fafc; border-radius:12px; text-align:center;
                            border:1.5px dashed #cbd5e1; color:#94a3b8; font-size:13px; margin-bottom:12px;">
                    <i class="bi bi-geo-alt" style="display:block; font-size:24px; margin-bottom:6px;"></i>
                    Alamat utama belum diisi.
                    <a href="<?= base_url('profil/alamat') ?>" style="display:block; margin-top:8px;
                       color:#033B59; font-weight:600; font-size:13px;">+ Tambah Sekarang</a>
                </div>
                <?php endif; ?>

                <?php if ($alamatAlt !== ''): ?>
                <div class="alamat-pilih-card" id="cardAlternatif" onclick="pilihAlamat('alternatif')" style="cursor:pointer; margin-top:10px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                        <span style="font-weight:700; font-size:14px; color:#0f172a;">Kost / Alternatif</span>
                        <i class="bi bi-check-circle-fill ms-auto" id="iconAlternatif"
                           style="color:#033B59; font-size:18px; display:none;"></i>
                    </div>
                    <div style="font-size:13px; font-weight:600; color:#1e293b; line-height:1.5;">
                        <?= htmlspecialchars($alamatAlt, ENT_QUOTES) ?>
                    </div>
                </div>
                <?php else: ?>
                <div style="padding:14px; background:#f8fafc; border-radius:12px; text-align:center;
                            border:1.5px dashed #cbd5e1; color:#94a3b8; font-size:13px; margin-top:10px;">
                    <i class="bi bi-geo-alt" style="display:block; font-size:24px; margin-bottom:6px;"></i>
                    Alamat alternatif belum diisi.
                    <a href="<?= base_url('profil/alamat') ?>" style="display:block; margin-top:8px;
                       color:#033B59; font-weight:600; font-size:13px;">+ Tambah Sekarang</a>
                </div>
                <?php endif; ?>

            </div>
            <div class="modal-footer" style="border-top:1px solid #f1f5f9; padding:12px 20px;">
                <button type="button" class="btn btn-outline-secondary rounded-3"
                    data-bs-dismiss="modal" style="font-size:13px;">Batal</button>
                <button type="button" class="btn rounded-3 fw-bold text-white"
                    style="background:#033B59; font-size:13px;" onclick="konfirmasiPilihAlamat()">
                    <i class="bi bi-check-lg"></i> Pilih Alamat Ini
>>>>>>> origin/digiwash-semoga-final
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let isSatuan = <?= ($layanan === 'Satuan') ? 'true' : 'false' ?>;
    let itemsSatuan = {
        jas: 0, jaket: 0, gaun: 1, sepatu: 0,
        selimut: 0, tas: 0, kaos: 0, lainnya: 0
    };
    let hargaSatuan = {
        jas: 25000, jaket: 15000, gaun: 35000, sepatu: 10000,
        selimut: 20000, tas: 18000, kaos: 8000, lainnya: 12000
    };

    /* ── Kiloan counter ── */
    function ubahBerat(val) {
        let inputBerat = document.getElementById('berat');
        let newVal = Math.max(1, (parseInt(inputBerat.value) || 1) + val);
        inputBerat.value = newVal;
        hitungTotal();
    }

    /* ── Satuan counter ── */
    function updateSatuan(item, val) {
        if (itemsSatuan[item] + val >= 0) {
            itemsSatuan[item] += val;
            let qtyEl = document.getElementById('qty_' + item);
            qtyEl.innerText = itemsSatuan[item];
            qtyEl.closest('.satuan-card').classList.toggle('active', itemsSatuan[item] > 0);
            hitungTotal();
        }
    }

    /* ── Hitung total ── */
    function hitungTotal() {
        if (isSatuan) {
            let total = 0;
            for (let k in itemsSatuan) total += itemsSatuan[k] * hargaSatuan[k];

            document.getElementById('text_calc').innerText = 'Laundry Satuan';
            document.getElementById('text_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total_harga').value = total;
            document.getElementById('detail_satuan').value = JSON.stringify(itemsSatuan);

            let sub = document.getElementById('subtotal_items');
            if (sub) sub.innerText = 'Rp ' + total.toLocaleString('id-ID');
        } else {
            let berat = parseInt(document.getElementById('berat').value) || 1;
            let harga = parseInt(document.getElementById('harga_per_kg').value) || 0;
            let total = berat * harga;
            document.getElementById('text_calc').innerText = berat + ' Kg x Rp ' + harga.toLocaleString('id-ID');
            document.getElementById('text_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total_harga').value = total;
        }
    }

<<<<<<< HEAD
    /* ── Simpan alamat dari modal ── */
    function simpanAlamat() {
        let val = document.getElementById('inputAlamatBaru').value.trim();
        let errEl = document.getElementById('alamatError');
        if (!val) {
            errEl.style.display = 'block';
            return;
        }
        errEl.style.display = 'none';
        document.getElementById('inputAlamat').value = val;
        document.getElementById('displayAlamat').innerText = val;
        bootstrap.Modal.getInstance(document.getElementById('modalUbahAlamat')).hide();
    }

    /* Isi textarea modal saat dibuka */
    document.getElementById('modalUbahAlamat').addEventListener('show.bs.modal', function () {
        document.getElementById('inputAlamatBaru').value =
            document.getElementById('inputAlamat').value;
    });
=======
    let alamatData = {
        utama: <?= json_encode((string) ($alamat ?? '')) ?>,
        alternatif: <?= json_encode((string) ($alamat_alternatif ?? '')) ?>
    };
    let pilihanAlamat = null;

    /* ── Pilih kartu alamat di modal ── */
    function pilihAlamat(tipe) {
        pilihanAlamat = tipe;
        // Reset semua kartu
        ['utama','alternatif'].forEach(function(t) {
            let card = document.getElementById('card' + t.charAt(0).toUpperCase() + t.slice(1));
            let icon = document.getElementById('icon' + t.charAt(0).toUpperCase() + t.slice(1));
            if (card) {
                card.style.borderColor = '#e2e8f0';
                card.style.background = '#fff';
            }
            if (icon) icon.style.display = 'none';
        });
        // Aktifkan kartu yang dipilih
        let activeCard = document.getElementById('card' + tipe.charAt(0).toUpperCase() + tipe.slice(1));
        let activeIcon = document.getElementById('icon' + tipe.charAt(0).toUpperCase() + tipe.slice(1));
        if (activeCard) {
            activeCard.style.borderColor = '#033B59';
            activeCard.style.background = '#f0f7ff';
        }
        if (activeIcon) activeIcon.style.display = 'inline-block';
    }

    /* ── Konfirmasi pilihan ── */
    function konfirmasiPilihAlamat() {
        if (!pilihanAlamat) {
            alert('Silakan pilih salah satu alamat terlebih dahulu.');
            return;
        }
        let nilai = alamatData[pilihanAlamat] || '';
        document.getElementById('inputAlamat').value = nilai;
        document.getElementById('displayAlamat').innerText = nilai;
        bootstrap.Modal.getInstance(document.getElementById('modalPilihAlamat')).hide();
    }

    /* Tandai pilihan default saat modal dibuka */
    let modalEl = document.getElementById('modalPilihAlamat');
    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', function () {
            let current = document.getElementById('inputAlamat').value;
            if (current && current === alamatData.utama) {
                pilihAlamat('utama');
            } else if (current && current === alamatData.alternatif) {
                pilihAlamat('alternatif');
            } else {
                pilihanAlamat = null;
            }
        });
    }
>>>>>>> origin/digiwash-semoga-final

    /* Tandai kartu satuan yang sudah berisi (nilai awal) */
    if (isSatuan) {
        for (let k in itemsSatuan) {
            let el = document.getElementById('qty_' + k);
            if (el) el.closest('.satuan-card').classList.toggle('active', itemsSatuan[k] > 0);
        }
    }

    hitungTotal();
</script>
</body>
</html>