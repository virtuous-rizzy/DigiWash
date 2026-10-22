<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
<<<<<<< HEAD
$e            = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$jmlAdmin     = (int) ($jmlAdmin ?? 0);
$jmlKurir     = (int) ($jmlKurir ?? 0);
=======
$e = static fn(string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$jmlAdmin = (int) ($jmlAdmin ?? 0);
$jmlKurir = (int) ($jmlKurir ?? 0);
>>>>>>> origin/digiwash-semoga-final
$jmlPelanggan = (int) ($jmlPelanggan ?? 0);
?>

<p class="hello">
    Selamat datang, <?= $e((string) ($nama ?? 'Admin')) ?>
    <small>Mau kelola apa hari ini?</small>
</p>

<p class="subjudul">Pekerjaan harian</p>
<div class="admin-menu">
<<<<<<< HEAD
    <a class="admin-card" href="#" data-soon>
        <span class="ikon-besar"><i class="bi bi-box-seam-fill" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Memproses Pesanan</strong><small>Lihat dan kelola pesanan baru</small></span>
    </a>
=======
>>>>>>> origin/digiwash-semoga-final
    <a class="admin-card" href="<?= base_url('admin/transaksi') ?>">
        <span class="ikon-besar"><i class="bi bi-cash-stack" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Memproses Transaksi</strong><small>Konfirmasi pembayaran dan kelola transaksi</small></span>
    </a>
<<<<<<< HEAD
    <a class="admin-card" href="#" data-soon>
        <span class="ikon-besar"><i class="bi bi-arrow-repeat" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Mengupdate Status Laundry</strong><small>Ubah status: diterima, dicuci, selesai, diantar</small></span>
    </a>
    <a class="admin-card" href="#" data-soon>
        <span class="ikon-besar"><i class="bi bi-chat-dots-fill" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Kritik &amp; Saran</strong><small>Baca dan tanggapi masukan pelanggan</small></span>
    </a>
=======
    <a class="admin-card" href="<?= base_url('admin/status-laundry') ?>">
        <span class="ikon-besar"><i class="bi bi-arrow-repeat" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Mengupdate Status Laundry</strong><small>Ubah status: diterima, dicuci, selesai,
                diantar</small></span>
    </a>
    <a class="admin-card" href="<?= base_url('admin/kelola-kritik') ?>">
        <span class="ikon-besar"><i class="bi bi-chat-dots-fill" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Kritik &amp; Saran</strong><small>Baca dan tanggapi masukan pelanggan</small></span>
    </a>
    <a class="admin-card" href="<?= base_url('admin/kelola-layanan') ?>">
        <span class="ikon-besar"><i class="bi bi-list-check" style="color: #033B59; font-size: 32px;"></i></span>
        <span><strong>Kelola Layanan</strong><small>Tambah, edit, atau hapus layanan laundry</small></span>
    </a>
>>>>>>> origin/digiwash-semoga-final
</div>

<p class="subjudul">Pengelolaan</p>
<div class="admin-menu">
    <a class="admin-card" href="<?= base_url('admin/kelola') ?>">
        <span class="ikon-besar"><i class="bi bi-people-fill" style="color: #033B59; font-size: 32px;"></i></span>
        <span>
            <strong>Kelola Akun</strong>
            <small><?= $jmlAdmin ?> admin, <?= $jmlKurir ?> kurir, <?= $jmlPelanggan ?> pelanggan</small>
        </span>
    </a>
</div>

<<<<<<< HEAD
<p class="subjudul">Pesanan terbaru</p>
<div class="panel kosong"><p>Belum ada pesanan.</p></div>
=======
<style>
    .pesanan-terbaru-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 28px 0 14px;
    }

    .pesanan-terbaru-title {
        font-size: 19px;
        font-weight: 800;
        color: #000000;
        margin: 0;
        font-family: inherit;
        letter-spacing: -0.2px;
    }

    .pesanan-terbaru-link {
        font-size: 14px;
        font-weight: 600;
        color: #033B59;
        text-decoration: none;
        transition: all 0.18s ease;
    }

    .pesanan-terbaru-link:hover {
        color: #02263a;
        text-decoration: underline;
    }

    .pesanan-terbaru-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }

    .latest-order-card {
        background: #ffffff;
        border: 1.5px solid #000000;
        border-radius: 18px;
        padding: 12px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .latest-order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        color: inherit;
    }

    .order-avatar-box {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        background: #d32f2f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        margin-top: 2px;
    }

    .order-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-fallback {
        color: #ffffff;
        font-weight: 700;
        font-size: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .order-card-body {
        display: flex;
        flex-direction: column;
        gap: 3px;
        flex: 1;
        min-width: 0;
    }

    /* Baris 1: Nama Pelanggan di kiri, Harga di kanan */
    .order-row-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: 100%;
    }

    .order-customer-name {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
        min-width: 0;
    }

    .order-price-val {
        font-size: 15px;
        font-weight: 800;
        color: #033B59;
        white-space: nowrap;
        text-align: right;
        flex-shrink: 0;
    }

    /* Baris 2: Nama Layanan */
    .order-row-service {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    /* Baris 3: Footer Status & Berat di kiri, Waktu di kanan */
    .order-row-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
        margin-top: 4px;
        padding-top: 6px;
        border-top: 1px dashed #e2e8f0;
    }

    .order-footer-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        min-width: 0;
    }

    .order-status-pill {
        display: inline-flex;
        align-items: center;
        padding: 3px 12px;
        border-radius: 9999px;
        font-size: 11.5px;
        font-weight: 700;
        line-height: 1.25;
        text-align: center;
        white-space: nowrap;
    }

    .pill-baru {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .pill-diterima {
        background: #dcfce7;
        color: #15803d;
    }

    .pill-diproses {
        background: #fef9c3;
        color: #854d0e;
    }

    .pill-diantar {
        background: #ede9fe;
        color: #7c3aed;
    }

    .pill-selesai {
        background: #d1fae5;
        color: #047857;
    }

    .pill-batal {
        background: #fee2e2;
        color: #b91c1c;
    }

    .order-weight-badge {
        display: inline-flex;
        align-items: center;
        background: #f0f9ff;
        color: #0284c7;
        border: 1px solid #bae6fd;
        font-weight: 700;
        font-size: 11.5px;
        padding: 2px 8px;
        border-radius: 6px;
        line-height: 1.25;
        white-space: nowrap;
    }

    .order-time-badge {
        font-size: 12px;
        color: #475569;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        flex-shrink: 0;
    }

    @media (max-width: 480px) {
        .latest-order-card {
            padding: 11px 13px;
            gap: 10px;
            border-radius: 16px;
        }

        .order-avatar-box {
            width: 42px;
            height: 42px;
        }

        .order-customer-name {
            font-size: 14px;
        }

        .order-price-val {
            font-size: 14px;
        }

        .order-row-service {
            font-size: 12px;
        }

        .order-status-pill {
            padding: 2px 9px;
            font-size: 11px;
        }

        .order-weight-badge {
            font-size: 11px;
            padding: 2px 7px;
        }

        .order-time-badge {
            font-size: 11.5px;
        }
    }
</style>

<!-- Section Pesanan Terbaru -->
<div class="pesanan-terbaru-header">
    <h3 class="pesanan-terbaru-title">Pesanan terbaru</h3>
    <a href="<?= base_url('admin/status-laundry') ?>" class="pesanan-terbaru-link">Lihat Semua</a>
</div>

<?php
$pesananTerbaru = (array) ($pesananTerbaru ?? []);
?>
<?php if (!empty($pesananTerbaru)): ?>
    <div class="pesanan-terbaru-list">
        <?php foreach ($pesananTerbaru as $p): ?>
            <?php
            $namaPlg = (string) ($p['nama_pelanggan'] ?? 'Pelanggan');
            $inisialPlg = $namaPlg !== '' ? strtoupper(substr($namaPlg, 0, 1)) : 'P';
            $fotoPlg = url_foto_profil($p['foto_pelanggan'] ?? null);
            $layananNama = (string) ($p['nama_layanan'] ?? 'Laundry Reguler');
            $isSatuan = (strtolower((string) ($p['jenis_layanan'] ?? '')) === 'satuan' || str_contains(strtolower($layananNama), 'satuan'));

            if ($isSatuan) {
                $itemCount = 0;
                if (!empty($p['detail_satuan'])) {
                    $decoded = json_decode((string) $p['detail_satuan'], true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $it) {
                            $itemCount += (int) ($it['qty'] ?? $it['jumlah'] ?? 1);
                        }
                    }
                }
                if ($itemCount <= 0 && (float) ($p['berat'] ?? 0) > 0) {
                    $itemCount = (int) round((float) $p['berat']);
                }
                $qtyText = ($itemCount > 0 ? $itemCount : 1) . ' item';
            } else {
                $beratNum = (float) ($p['berat'] ?? 1.0);
                $qtyText = (fmod($beratNum, 1.0) == 0.0 ? number_format($beratNum, 0) : number_format($beratNum, 1, ',', '.')) . ' Kg';
            }

            $st = strtolower((string) ($p['status_pesanan'] ?? 'menunggu'));
            $badgeClass = match ($st) {
                'menunggu' => 'pill-baru',
                'dikonfirmasi' => 'pill-diterima',
                'dijemput', 'dicuci', 'disetrika' => 'pill-diproses',
                'diantar' => 'pill-diantar',
                'selesai' => 'pill-selesai',
                'dibatalkan' => 'pill-batal',
                default => 'pill-baru',
            };
            $statusLabel = match ($st) {
                'menunggu' => 'Baru',
                'dikonfirmasi' => 'Diterima',
                'dijemput', 'dicuci', 'disetrika' => 'Diproses',
                'diantar' => 'Diantar',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Batal',
                default => ucfirst($st),
            };

            $tglRaw = $p['tanggal'] ?? $p['created_at'] ?? 'now';
            $jamText = date('H:i', strtotime((string) $tglRaw));
            $totalNom = (float) ($p['total_harga'] ?? 0);
            if ($totalNom <= 0) {
                $flat = (float) ($p['harga_flat'] ?? 0);
                $perKg = (float) ($p['harga_per_kg'] ?? 0);
                $beratVal = (float) ($p['berat'] ?? 1.0);
                $totalNom = $flat > 0 ? $flat : ($perKg * $beratVal);
            }
            $hargaText = 'Rp ' . number_format($totalNom, 0, ',', '.');
            ?>
            <a href="<?= base_url('admin/status-laundry') ?>" class="latest-order-card">
                <div class="order-avatar-box">
                    <?php if ($fotoPlg): ?>
                        <img src="<?= $e($fotoPlg) ?>" class="order-avatar-img" alt="<?= $e($namaPlg) ?>"
                            onerror="this.style.display='none'; if(this.nextElementSibling){ this.nextElementSibling.style.display='flex'; }">
                        <span class="avatar-fallback" style="display:none;"><?= $e($inisialPlg) ?></span>
                    <?php else: ?>
                        <span class="avatar-fallback"><?= $e($inisialPlg) ?></span>
                    <?php endif; ?>
                </div>

                <div class="order-card-body">
                    <div class="order-row-header">
                        <div class="order-customer-name" title="<?= $e($namaPlg) ?>"><?= $e($namaPlg) ?></div>
                        <div class="order-price-val"><?= $hargaText ?></div>
                    </div>

                    <div class="order-row-service">
                        <?= $e($layananNama) ?>
                    </div>

                    <div class="order-row-footer">
                        <div class="order-footer-left">
                            <span class="order-status-pill <?= $badgeClass ?>"><?= $e($statusLabel) ?></span>
                            <span class="order-weight-badge">
                                <i class="bi bi-box-seam me-1"></i><?= $e($qtyText) ?>
                            </span>
                        </div>

                        <span class="order-time-badge">
                            <i class="bi bi-clock me-1 text-secondary"></i><?= $jamText ?>
                        </span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="panel kosong"
        style="text-align: center; padding: 24px; background: #fff; border: 1.5px solid #000; border-radius: 18px; margin-bottom: 24px;">
        <i class="bi bi-inbox text-muted" style="font-size: 32px;"></i>
        <p class="text-muted small mt-2 mb-0">Belum ada pesanan terbaru.</p>
    </div>
<?php endif; ?>
>>>>>>> origin/digiwash-semoga-final
<?= $this->endSection() ?>