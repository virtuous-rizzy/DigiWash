<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
/** @var array $ulasan */
/** @var string $status_aktif */
/** @var string|null $keyword */
/** @var int $jml_semua */
/** @var int $jml_belum */
/** @var int $jml_proses */
/** @var int $jml_selesai */
$e      = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$sukses = (string) (session()->getFlashdata('success') ?? '');
?>

<style>
    /* ── Header ── */
    .page-title-box {
        margin-bottom: 16px;
    }

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: #1e293b;
        font-size: 20px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-back-link:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .page-heading {
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
        margin: 0;
    }

    .page-subheading {
        font-size: 12px;
        color: #64748b;
        margin: 0;
    }

    /* ── Search Bar ── */
    .search-filter-row {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 18px;
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
    }

    .search-input-wrap .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 15px;
        pointer-events: none;
    }

    .search-input-box {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #fff;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-input-box:focus {
        border-color: #033B59;
        box-shadow: 0 0 0 3px rgba(3, 59, 89, 0.12);
    }

    .btn-filter-submit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #fff;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .btn-filter-submit:hover {
        background: #f1f5f9;
        border-color: #033B59;
        color: #033B59;
    }

    /* ── Filter Tabs ── */
    .filter-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 10px;
        margin-bottom: 12px;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .filter-tabs::-webkit-scrollbar {
        display: none;
    }

    .tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 34px;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        color: #334155;
        background: #fff;
        border: 1px solid #cbd5e1;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .tab-pill.active {
        background: #0f172a;
        color: #fff;
        border-color: #0f172a;
    }

    .badge-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 999px;
        color: #fff;
        background: #2563eb;
    }

    .badge-inactive {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 999px;
        color: #fff;
        background: #64748b;
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

<!-- Header -->
<div class="page-title-box d-flex align-items-center gap-2">
    <a href="<?= base_url('admin/dashboard') ?>" class="btn-back-link" title="Kembali ke Dashboard">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 class="page-heading">Kelola Kritik &amp; Saran</h1>
        <p class="page-subheading">Baca dan tanggapi masukan pelanggan</p>
    </div>
</div>

<?php if ($sukses !== ''): ?>
    <div class="alert ok"><?= $e($sukses) ?></div>
<?php endif; ?>

<!-- Search Bar -->
<form method="GET" action="<?= base_url('admin/kelola-kritik') ?>" class="search-filter-row">
    <input type="hidden" name="status" value="<?= $e($status_aktif) ?>">
    <div class="search-input-wrap">
        <i class="bi bi-search search-icon"></i>
        <input type="text" name="keyword" value="<?= $e($keyword ?? '') ?>" class="search-input-box"
            placeholder="Cari kritik / saran / pelanggan...">
    </div>
    <button type="submit" class="btn-filter-submit">
        <i class="bi bi-funnel"></i> Filter
    </button>
</form>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="?status=Semua<?= $keyword ? '&keyword='.$e($keyword) : '' ?>"
       class="tab-pill <?= ($status_aktif == 'Semua' || $status_aktif == '') ? 'active' : '' ?>">
        Semua
        <span class="<?= ($status_aktif == 'Semua' || $status_aktif == '') ? 'badge-count' : 'badge-inactive' ?>">
            <?= $jml_semua ?? 0 ?>
        </span>
    </a>
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

<!-- Cards -->
<?php if (!empty($ulasan)): ?>
    <?php foreach ($ulasan as $row): ?>
    <div class="ulasan-card">
        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:8px;">
            <!-- User info -->
            <?php
            $fotoUrl       = url_foto_profil($row['foto'] ?? null);
            $namaPelanggan = (string)($row['nama'] ?? 'Pelanggan');
            $inisial       = strtoupper(substr(trim($namaPelanggan), 0, 2));
            ?>
            <div style="display:flex; align-items:center; gap:10px;">
                <?php if ($fotoUrl): ?>
                    <img src="<?= $e($fotoUrl) ?>" class="avatar-sm" alt="<?= $e($namaPelanggan) ?>"
                         onerror="this.style.display='none'; if(this.nextElementSibling){ this.nextElementSibling.style.display='flex'; }">
                    <div class="avatar-sm" style="display:none;"><?= $e($inisial) ?></div>
                <?php else: ?>
                    <div class="avatar-sm"><?= $e($inisial) ?></div>
                <?php endif; ?>
                <div>
                    <div style="font-weight:600; font-size:13px; color:#1b2a3a;"><?= $e($namaPelanggan) ?></div>
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

        <?php if (!empty($row['nama_admin'])): ?>
            <div style="font-size:11px; margin-bottom:10px; text-align:right;">
                <span style="color:#033B59; font-weight:600;"><i class="bi bi-person-check-fill me-1"></i>Ditangani: <?= $e($row['nama_admin']) ?></span>
            </div>
        <?php endif; ?>

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
        <p style="color:#99aaaa;">Tidak ada data kritik &amp; saran <?= ($status_aktif && strtolower($status_aktif) !== 'semua') ? 'dengan status ' . $e($status_aktif) : '' ?> yang ditemukan.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

