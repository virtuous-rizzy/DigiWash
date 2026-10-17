<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
$e      = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$status = (string) ($status ?? 'tersedia');

$badge = [
    'tersedia' => ['badge-ok', 'TERSEDIA'],
    'bertugas' => ['badge-warn', 'BERTUGAS'],
    'nonaktif' => ['badge-off', 'NONAKTIF'],
];
[$kelas, $teks] = $badge[$status] ?? ['badge-off', strtoupper($status)];
?>

<p class="hello">
    Halo, <?= $e((string) ($nama ?? 'Kurir')) ?>
    <small>Ini ringkasan tugasmu hari ini</small>
</p>

<div class="status-baris">
    <span>Status kamu</span>
    <span class="<?= $kelas ?>"><?= $teks ?></span>
</div>

<div class="metrik">
    <div class="metrik-item"><span>Jemput</span><strong><?= (int)($ringkasan['jemput'] ?? 1) ?></strong></div>
    <div class="metrik-item"><span>Antar</span><strong><?= (int)($ringkasan['antar'] ?? 1) ?></strong></div>
    <div class="metrik-item"><span>Selesai</span><strong><?= (int)($ringkasan['selesai'] ?? 1) ?></strong></div>
</div>

<p class="subjudul">Menu tugas</p>
<div class="admin-menu">
    <a class="admin-card" href="<?= base_url('kurir/tugas?tab=penjemputan') ?>">
        <span class="ikon-besar">📦</span>
        <span><strong>Tugas Jemput</strong><small>Ambil cucian dari rumah pelanggan</small></span>
    </a>
    <a class="admin-card" href="<?= base_url('kurir/tugas?tab=pengantaran') ?>">
        <span class="ikon-besar">🚚</span>
        <span><strong>Tugas Antar</strong><small>Antar cucian bersih ke pelanggan</small></span>
    </a>
    <a class="admin-card" href="<?= base_url('kurir/riwayat') ?>">
        <span class="ikon-besar">🕘</span>
        <span><strong>Riwayat Tugas</strong><small>Lihat tugas yang sudah selesai</small></span>
    </a>
</div>

<p class="subjudul">Tugas aktif</p>
<div class="panel kosong"><p>Belum ada tugas.</p></div>
<?= $this->endSection() ?>