<?php /** @var array $ulasan */ ?>
<?php /** 
 * @var array $ulasan 
 * @var string $status_aktif 
 * @var string|null $keyword 
 * @var int $jml_belum 
 * @var int $jml_proses 
 * @var int $jml_selesai 
 */ ?>
 
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kritik & Saran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e5e5e5; }
        .mobile-container {
            max-width: 400px; background-color: #ffffff;
            min-height: 100vh; margin: 0 auto;
        }
        .text-dark-custom { color: #1b2a3a; }
        .text-muted-custom { color: #99aaaa; }
        
        /* Mengatur agar tab bisa digeser dan memunculkan scrollbar */
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            overflow-x: auto; /* Kunci agar bisa digeser */
            white-space: nowrap;
            padding-bottom: 10px; /* Jarak agar scrollbar tidak menempel pada teks */
            -webkit-overflow-scrolling: touch;
        }

        /* Kustomisasi Scrollbar agar tampil tipis dan elegan di laptop/PC */
        .filter-tabs::-webkit-scrollbar {
            height: 6px; /* Ketebalan scrollbar horizontal */
        }
        .filter-tabs::-webkit-scrollbar-track {
            background: #f1f1f1; 
            border-radius: 10px;
        }
        .filter-tabs::-webkit-scrollbar-thumb {
            background: #99aaaa; /* Warna scrollbar sesuai palet pendukung */
            border-radius: 10px;
        }
        .filter-tabs::-webkit-scrollbar-thumb:hover {
            background: #7a8fb0; /* Warna saat scrollbar disentuh mouse */
        }
        
        .tab-pill {
            background-color: #eeeeee; color: #1b2a3a;
            border-radius: 20px; padding: 8px 14px;
            font-size: 13px; font-weight: bold;
            display: inline-flex; align-items: center;
            gap: 6px; text-decoration: none;
            transition: all 0.2s ease;
        }
        /* Style jika tab sedang aktif */
        .tab-pill.active { background-color: #8fe0e8; color: #0b3d5c; }
        
        /* Style angka notifikasi */
        .badge-count {
            background-color: #176b3a; color: white;
            border-radius: 50%; padding: 2px 7px; font-size: 11px;
        }
        .badge-inactive {
            background-color: #99aaaa; color: white;
            border-radius: 50%; padding: 2px 7px; font-size: 11px;
        }
        
        /* Label Keluhan & Saran */
        .badge-keluhan { background-color: #ffe3e3; color: #aa0000; font-size: 10px; padding: 5px 10px; }
        .badge-saran { background-color: #c9a0f5; color: #1b2a3a; font-size: 10px; padding: 5px 10px; }
        
        .avatar {
            width: 40px; height: 40px; background-color: #7a8fb0; color: white;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-weight: bold; object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="mobile-container shadow p-3">
        
        <!-- Header -->
        <div class="d-flex align-items-center mb-3 text-dark-custom">
            <span class="fs-4 me-3">&#8592;</span>
            <span class="fw-bold fs-6">Menindaklanjuti Kritik & Saran</span>
        </div>

        <!-- Filter Tabs yang bisa di-scroll / swipe (flex-nowrap) -->
        <div class="filter-tabs mb-3 d-flex gap-2 flex-nowrap">
            <a href="?status=Belum ditindaklanjuti<?= $keyword ? '&keyword='.$keyword : '' ?>" 
               class="tab-pill <?= $status_aktif == 'Belum ditindaklanjuti' ? 'active' : '' ?>">
                Belum ditindaklanjuti 
                <span class="<?= $status_aktif == 'Belum ditindaklanjuti' ? 'badge-count' : 'badge-inactive' ?>">
                    <?= isset($jml_belum) ? $jml_belum : 0 ?>
                </span>
            </a>
            
            <a href="?status=Dalam Proses<?= $keyword ? '&keyword='.$keyword : '' ?>" 
               class="tab-pill <?= $status_aktif == 'Dalam Proses' ? 'active' : '' ?>">
                Dalam Proses 
                <span class="<?= $status_aktif == 'Dalam Proses' ? 'badge-count' : 'badge-inactive' ?>">
                    <?= isset($jml_proses) ? $jml_proses : 0 ?>
                </span>
            </a>
            
            <a href="?status=Selesai<?= $keyword ? '&keyword='.$keyword : '' ?>" 
               class="tab-pill <?= $status_aktif == 'Selesai' ? 'active' : '' ?>">
                Selesai 
                <span class="<?= $status_aktif == 'Selesai' ? 'badge-count' : 'badge-inactive' ?>">
                    <?= isset($jml_selesai) ? $jml_selesai : 0 ?>
                </span>
            </a>
        </div>

        <!-- Search Bar Berfungsi -->
        <form method="GET" action="/kelola-kritik">
            <!-- Simpan status yang aktif agar tidak reset saat mencari -->
            <input type="hidden" name="status" value="<?= $status_aktif ?>">
            
            <div class="input-group mb-4">
                <input type="text" name="keyword" value="<?= esc($keyword) ?>" class="form-control text-muted-custom" placeholder="Cari kritik / saran / pelanggan..." style="border-color: #99aaaa; font-size: 13px;">
                <button type="submit" class="btn btn-outline-secondary text-dark-custom fw-bold" style="border-color: #99aaaa; background-color: white;">Filter</button>
            </div>
        </form>

        <!-- Looping Card Data -->
        <?php if (!empty($ulasan)): ?>
            <?php foreach ($ulasan as $row) : ?>
            
            <div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px; border: 1px solid #e5e5e5 !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <!-- User Info -->
                        <div class="d-flex align-items-center gap-2">
                            <?php if($row['foto']): ?>
                                <img src="<?= base_url('uploads/'.$row['foto']) ?>" class="avatar">
                            <?php else: ?>
                                <div class="avatar bg-secondary">
                                    <?= strtoupper(substr($row['nama'], 0, 2)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="fw-bold text-dark-custom" style="font-size: 14px;"><?= esc($row['nama']) ?></div>
                                <div class="text-muted-custom" style="font-size: 12px;"><?= date('d M Y - H:i', strtotime($row['tanggal'] ?? $row['created_at'] ?? 'now')) ?></div>
                            </div>
                        </div>
                        
                        <!-- Badge Dinamis (Keluhan/Saran) -->
                        <?php if(($row['jenis'] ?? '') == 'Keluhan'): ?>
                            <span class="badge badge-keluhan rounded-pill">Keluhan</span>
                        <?php else: ?>
                            <span class="badge badge-saran rounded-pill">Saran</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Rating Bintang -->
                    <div class="mb-1" style="color: #FFD700; font-size: 14px;">
                        <?= str_repeat('★', (int)($row['rating'] ?? 5)) ?><?= str_repeat('☆', 5 - (int)($row['rating'] ?? 5)) ?>
                    </div>
                    
                    <!-- Isi Pesan -->
                    <p class="card-text text-dark-custom mb-2" style="font-size: 13px; line-height: 1.4;">
                        <?= esc($row['isi_pesan'] ?? $row['pesan'] ?? '') ?>
                    </p>
                    
                    <div class="text-muted-custom" style="font-size: 11px;">
                        Pesanan : <?= esc($row['no_pesanan'] ?? '-') ?>
                    </div>
                </div>
            </div>
            
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-muted mt-5 mb-5 p-4">
                <div style="font-size: 40px; margin-bottom: 10px;">🔍</div>
                <p>Tidak ada data <?= $status_aktif ?> yang ditemukan.</p>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>