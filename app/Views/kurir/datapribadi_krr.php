<?= $this->extend('kurir/layout_krr') ?>

<?= $this->section('isi') ?>
<?php
$e     = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$kurir = (array) ($kurir ?? []);

$badge = [
    'tersedia' => ['badge-ok', 'TERSEDIA'],
    'bertugas' => ['badge-warn', 'BERTUGAS'],
    'nonaktif' => ['badge-off', 'NONAKTIF'],
];
$st = (string) ($kurir['status'] ?? 'tersedia');
[$kelas, $teks] = $badge[$st] ?? ['badge-off', strtoupper($st)];
?>

<div class="judul-halaman">
<<<<<<< HEAD
    <a href="<?= base_url('kurir/akun') ?>" class="kembali">&larr;</a>
=======
    <a href="<?= base_url('kurir/akun') ?>" class="kembali"><i class="bi bi-arrow-left"></i></a>
>>>>>>> origin/digiwash-semoga-final
    <h3>Data Pribadi</h3>
</div>

<p class="subjudul">Informasi akun</p>
<div class="daftar-baris">
    <a class="baris" href="<?= base_url('kurir/akun/data/ubah') ?>">
<<<<<<< HEAD
        <span class="ikon">👤</span>
=======
        <span class="ikon"><i class="bi bi-person-fill" style="color: #033B59;"></i></span>
>>>>>>> origin/digiwash-semoga-final
        <span class="teks"><small>Nama Lengkap</small><strong><?= $e((string) (($kurir['nama'] ?? '') ?: '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <a class="baris" href="<?= base_url('kurir/akun/data/ubah') ?>">
<<<<<<< HEAD
        <span class="ikon">📞</span>
=======
        <span class="ikon"><i class="bi bi-telephone-fill" style="color: #033B59;"></i></span>
>>>>>>> origin/digiwash-semoga-final
        <span class="teks"><small>Nomor Telepon</small><strong><?= $e((string) (($kurir['no_hp'] ?? '') ?: '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <div class="baris">
<<<<<<< HEAD
        <span class="ikon">✉️</span>
=======
        <span class="ikon"><i class="bi bi-envelope-fill" style="color: #033B59;"></i></span>
>>>>>>> origin/digiwash-semoga-final
        <span class="teks"><small>Email</small><strong><?= $e((string) (($kurir['email'] ?? '') ?: '-')) ?></strong></span>
    </div>
</div>

<p class="subjudul">Status akun</p>
<div class="daftar-baris">
    <div class="baris">
<<<<<<< HEAD
        <span class="ikon">🛡️</span>
=======
        <span class="ikon"><i class="bi bi-shield-check" style="color: #033B59;"></i></span>
>>>>>>> origin/digiwash-semoga-final
        <span class="teks">Peran</span>
        <span class="badge-ok">KURIR</span>
    </div>
    <div class="baris">
<<<<<<< HEAD
        <span class="ikon">🛵</span>
=======
        <span class="ikon"><i class="bi bi-bicycle" style="color: #033B59;"></i></span>
>>>>>>> origin/digiwash-semoga-final
        <span class="teks">Status tugas</span>
        <span class="<?= $kelas ?>"><?= $teks ?></span>
    </div>
</div>
<?= $this->endSection() ?>