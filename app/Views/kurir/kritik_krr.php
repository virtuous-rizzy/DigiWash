<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
$e      = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$sukses = (string) (session()->getFlashdata('success') ?? '');
?>

<style>
    .star-rating {
        display: flex; flex-direction: row-reverse;
        justify-content: center; gap: 5px;
    }
    .star-rating input { display: none; }
    .star-rating label {
        font-size: 2.2rem; color: #ddd; cursor: pointer;
        transition: color .15s;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label { color: #FFD700; }

    .badge-keluhan { background-color: #ffe3e3; color: #aa0000; font-size: 11px; padding: 4px 10px; }
    .badge-saran   { background-color: #c9a0f5; color: #1b2a3a; font-size: 11px; padding: 4px 10px; }

    .riwayat-card {
        border: 1px solid #eee; border-radius: 12px;
        padding: 14px; margin-bottom: 12px;
        background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
</style>

<div class="judul-halaman">
    <a href="<?= base_url('kurir/dashboard') ?>" class="kembali" aria-label="Kembali"><i class="bi bi-arrow-left"></i></a>
    <h3>Kritik &amp; Saran</h3>
</div>

<?php if ($sukses !== ''): ?>
    <div class="alert ok"><?= $e($sukses) ?></div>
<?php endif; ?>

<!-- Ilustrasi -->
<div style="text-align:center; margin-bottom:16px;">
    <div><i class="bi bi-chat-dots-fill" style="font-size:3.5rem; color:#033B59; line-height:1;"></i></div>
    <p style="font-weight:600; color:#033B59; margin:8px 0 0;">Berikan penilaianmu</p>
</div>

<!-- Form Kirim Kritik -->
<form action="<?= base_url('kritik-saran/simpan') ?>" method="post">

    <!-- Rating Bintang -->
    <div class="star-rating" style="margin-bottom:20px;">
        <input type="radio" id="star5" name="rating" value="5" checked>
        <label for="star5">★</label>
        <input type="radio" id="star4" name="rating" value="4">
        <label for="star4">★</label>
        <input type="radio" id="star3" name="rating" value="3">
        <label for="star3">★</label>
        <input type="radio" id="star2" name="rating" value="2">
        <label for="star2">★</label>
        <input type="radio" id="star1" name="rating" value="1">
        <label for="star1">★</label>
    </div>

    <!-- Jenis Ulasan -->
    <div style="margin-bottom:14px;">
        <label style="font-weight:600; display:block; margin-bottom:8px; color:#033B59;">Jenis Ulasan</label>
        <div style="display:flex; gap:24px;">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis" id="saran" value="Saran" checked>
                <label class="form-check-label" for="saran">Saran</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis" id="keluhan" value="Keluhan">
                <label class="form-check-label" for="keluhan">Keluhan</label>
            </div>
        </div>
    </div>

    <!-- Textarea -->
    <div style="margin-bottom:18px;">
        <label style="font-weight:600; display:block; margin-bottom:8px; color:#033B59;">Kritik &amp; Saran</label>
        <textarea name="pesan" class="form-control" rows="4" placeholder="Tuliskan kritik dan saran Anda di sini..." style="border-color:#99aaaa; border-radius:10px;" required></textarea>
    </div>

    <!-- Tombol Kirim -->
    <button type="submit" class="btn w-100 py-2 fw-bold" style="background:#033B59; color:#fff; border-radius:10px;">Kirim</button>
</form>

<!-- Riwayat Kritik Saya -->
<?php if (!empty($riwayat)): ?>
<p class="subjudul" style="margin-top:28px;">Riwayat Kritik &amp; Saran Saya</p>
<?php foreach ($riwayat as $row): ?>
<div class="riwayat-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
        <div style="color:#FFD700; font-size:13px;">
            <?= str_repeat('★', (int)$row['rating']) ?><?= str_repeat('☆', 5 - (int)$row['rating']) ?>
        </div>
        <?php if ($row['jenis'] == 'Keluhan'): ?>
            <span class="badge badge-keluhan rounded-pill">Keluhan</span>
        <?php else: ?>
            <span class="badge badge-saran rounded-pill">Saran</span>
        <?php endif; ?>
    </div>
    <p style="font-size:13px; color:#1b2a3a; margin:0 0 6px; line-height:1.4;"><?= $e($row['isi_pesan'] ?? $row['pesan'] ?? '') ?></p>
    <div style="font-size:11px; color:#99aaaa;">
        <span><i class="bi bi-calendar-event me-1"></i><?= date('d M Y', strtotime($row['tanggal'] ?? $row['created_at'] ?? 'now')) ?></span>
    </div>
    <div style="margin-top:6px;">
        <?php
        $statusColor = match($row['status'] ?? '') {
            'Selesai'       => '#176b3a',
            'Dalam Proses'  => '#e6a800',
            default         => '#99aaaa',
        };
        ?>
        <span style="font-size:11px; padding:3px 8px; border-radius:10px; background:<?= $statusColor ?>22; color:<?= $statusColor ?>; font-weight:600;">
            <?= $e($row['status'] ?? 'Terkirim') ?>
        </span>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>

