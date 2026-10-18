<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
/** @var array $ulasan */
/** @var string $status_aktif */
/** @var string|null $keyword */
/** @var int $jml_belum */
/** @var int $jml_proses */
/** @var int $jml_selesai */
$e      = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$sukses = (string) (session()->getFlashdata('success') ?? '');
?>

<style>
    .filter-tabs {
        display: flex; gap: 8px; margin-bottom: 14px;
        overflow-x: auto; white-space: nowrap;
        padding-bottom: 8px;
        -webkit-overflow-scrolling: touch;
    }
    .filter-tabs::-webkit-scrollbar { height: 5px; }
    .filter-tabs::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .filter-tabs::-webkit-scrollbar-thumb { background: #99aaaa; border-radius: 10px; }

    .tab-pill {
        background: #eee; color: #1b2a3a;
        border-radius: 20px; padding: 7px 14px;
        font-size: 12px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px;
        text-decoration: none; transition: all .2s;
    }
    .tab-pill.active { background: #8fe0e8; color: #0b3d5c; }

    .badge-count {
        background: #176b3a; color: #fff;
        border-radius: 50%; padding: 2px 7px; font-size: 10px;
    }
    .badge-inactive {
        background: #99aaaa; color: #fff;
        border-radius: 50%; padding: 2px 7px; font-size: 10px;
    }

    .badge-keluhan { background: #ffe3e3; color: #aa0000; font-size: 10px; padding: 4px 10px; }
    .badge-saran   { background: #c9a0f5; color: #1b2a3a; font-size: 10px; padding: 4px 10px; }

    .avatar-sm {
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-weight: 700; font-size: 13px;
        background: #7a8fb0; color: #fff; object-fit: cover;
        flex-shrink: 0;
    }

    .ulasan-card {
        border: 1px solid #eee; border-radius: 12px;
        padding: 14px; margin-bottom: 12px;
        background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
</style>

<div class="judul-halaman">
    <a href="<?= base_url('admin/dashboard') ?>" class="kembali" aria-label="Kembali"><i class="bi bi-arrow-left"></i></a>
    <h3>Kelola Kritik &amp; Saran</h3>
</div>

<?php if ($sukses !== ''): ?>
    <div class="alert ok"><?= $e($sukses) ?></div>
<?php endif; ?>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="?status=Belum ditindaklanjuti<?= $keyword ? '&keyword='.$e($keyword) : '' ?>"
       class="tab-pill <?= $status_aktif == 'Belum ditindaklanjuti' ? 'active' : '' ?>">
        Belum ditindaklanjuti
        <span class="<?= $status_aktif == 'Belum ditindaklanjuti' ? 'badge-count' : 'badge-inactive' ?>">
            <?= $jml_belum ?? 0 ?>
        </span>
    </a>
    <a href="?status=Dalam Proses<?= $keyword ? '&keyword='.$e($keyword) : '' ?>"
       class="tab-pill <?= $status_aktif == 'Dalam Proses' ? 'active' : '' ?>">
        Dalam Proses
        <span class="<?= $status_aktif == 'Dalam Proses' ? 'badge-count' : 'badge-inactive' ?>">
            <?= $jml_proses ?? 0 ?>
        </span>
    </a>
    <a href="?status=Selesai<?= $keyword ? '&keyword='.$e($keyword) : '' ?>"
       class="tab-pill <?= $status_aktif == 'Selesai' ? 'active' : '' ?>">
        Selesai
        <span class="<?= $status_aktif == 'Selesai' ? 'badge-count' : 'badge-inactive' ?>">
            <?= $jml_selesai ?? 0 ?>
        </span>
    </a>
</div>

<!-- Search Bar -->
<form method="GET" action="<?= base_url('admin/kelola-kritik') ?>">
    <input type="hidden" name="status" value="<?= $e($status_aktif) ?>">
    <div class="input-group" style="margin-bottom:18px;">
        <input type="text" name="keyword" value="<?= $e($keyword ?? '') ?>" class="form-control" placeholder="Cari kritik / saran / pelanggan..." style="border-color:#99aaaa; font-size:13px;">
        <button type="submit" class="btn btn-outline-secondary fw-bold" style="border-color:#99aaaa; background:#fff; color:#1b2a3a;">Filter</button>
    </div>
</form>

<!-- Cards -->
<?php if (!empty($ulasan)): ?>
    <?php foreach ($ulasan as $row): ?>
    <div class="ulasan-card">
        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:8px;">
            <!-- User info -->
            <div style="display:flex; align-items:center; gap:10px;">
                <?php if (!empty($row['foto'])): ?>
                    <img src="<?= base_url('uploads/'.$row['foto']) ?>" class="avatar-sm" alt="">
                <?php else: ?>
                    <div class="avatar-sm"><?= strtoupper(substr($row['nama'], 0, 2)) ?></div>
                <?php endif; ?>
                <div>
                    <div style="font-weight:600; font-size:13px; color:#1b2a3a;"><?= $e($row['nama']) ?></div>
                    <div style="font-size:11px; color:#99aaaa;"><?= date('d M Y - H:i', strtotime($row['tanggal'] ?? $row['created_at'] ?? 'now')) ?></div>
                </div>
            </div>
            <!-- Badge Jenis -->
            <?php if (($row['jenis'] ?? '') == 'Keluhan'): ?>
                <span class="badge badge-keluhan rounded-pill">Keluhan</span>
            <?php else: ?>
                <span class="badge badge-saran rounded-pill">Saran</span>
            <?php endif; ?>
        </div>

        <!-- Rating -->
        <div style="color:#FFD700; font-size:13px; margin-bottom:4px;">
            <?= str_repeat('★', (int)($row['rating'] ?? 5)) ?><?= str_repeat('☆', 5 - (int)($row['rating'] ?? 5)) ?>
        </div>

        <!-- Pesan -->
        <p style="font-size:13px; color:#1b2a3a; margin:0 0 8px; line-height:1.4;"><?= $e($row['isi_pesan'] ?? $row['pesan'] ?? '') ?></p>

        <div style="font-size:11px; color:#99aaaa; margin-bottom:10px; display:flex; justify-content:space-between;">
            <span>Pesanan: <?= $e($row['no_pesanan'] ?? '-') ?></span>
            <?php if (!empty($row['nama_admin'])): ?>
                <span style="color:#033B59; font-weight:600;"><i class="bi bi-person-check-fill me-1"></i>Ditangani: <?= $e($row['nama_admin']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Ubah Status (Admin only) -->
        <?php $idKritik = $row['id_kritik_saran'] ?? $row['id_kritik'] ?? 0; ?>
        <form method="POST" action="<?= base_url('admin/kelola-kritik/update-status/'.$idKritik) ?>" style="display:flex; align-items:center; gap:8px;">
            <select name="status" class="form-select form-select-sm" style="font-size:12px; max-width:200px; border-color:#99aaaa;">
                <option value="Belum ditindaklanjuti" <?= ($row['status'] ?? '') == 'Belum ditindaklanjuti' ? 'selected' : '' ?>>Belum ditindaklanjuti</option>
                <option value="Dalam Proses" <?= ($row['status'] ?? '') == 'Dalam Proses' ? 'selected' : '' ?>>Dalam Proses</option>
                <option value="Selesai" <?= ($row['status'] ?? '') == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
            <button type="submit" class="btn btn-sm fw-bold" style="background:#033B59; color:#fff; border-radius:8px; font-size:12px; padding:5px 14px;">Ubah</button>
        </form>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="panel kosong" style="text-align:center; padding:30px;">
        <div style="font-size:36px; margin-bottom:8px;">🔍</div>
        <p style="color:#99aaaa;">Tidak ada data <?= $e($status_aktif) ?> yang ditemukan.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

