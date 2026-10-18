<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e      = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$daftar = (array) ($daftar ?? []);
$filter = (string) ($filter ?? 'semua');
$q      = (string) ($q ?? '');
$jumlah = (array) ($jumlah ?? []);
$idSaya = (int) ($idSaya ?? 0);

$tab        = ['semua' => 'Semua', 'admin' => 'Admin', 'kurir' => 'Kurir', 'pelanggan' => 'Pelanggan'];
$labelPeran = ['admin' => 'ADMIN', 'kurir' => 'KURIR', 'pelanggan' => 'PELANGGAN'];
$badgeSt    = [
    'aktif'    => ['badge-ok', 'AKTIF'],
    'tersedia' => ['badge-ok', 'TERSEDIA'],
    'bertugas' => ['badge-warn', 'BERTUGAS'],
    'nonaktif' => ['badge-off', 'NONAKTIF'],
];
?>

<div class="judul-halaman">
    <a href="<?= base_url('admin/dashboard') ?>" class="kembali">&larr;</a>
    <h3>Kelola Akun</h3>
</div>

<a href="<?= base_url('admin/kelola/tambah') ?>" class="btn btn-dark btn-tambah" style="margin-top:6px">+ Tambah Akun Admin atau Kurir</a>

<div class="chips">
    <?php foreach ($tab as $kunci => $label): ?>
        <?php
        $n   = $kunci === 'semua' ? array_sum($jumlah) : (int) ($jumlah[$kunci] ?? 0);
        $url = base_url('admin/kelola') . '?peran=' . $kunci . ($q !== '' ? '&q=' . rawurlencode($q) : '');
        ?>
        <a href="<?= $e($url) ?>" class="chip<?= $filter === $kunci ? ' on' : '' ?>"><?= $e($label) ?> (<?= $n ?>)</a>
    <?php endforeach; ?>
</div>

<form method="get" action="<?= base_url('admin/kelola') ?>" class="cari">
    <span>🔍</span>
    <input type="hidden" name="peran" value="<?= $e($filter) ?>">
    <input type="search" name="q" value="<?= $e($q) ?>" placeholder="Cari nama, telepon, atau email...">
</form>

<p class="subjudul"><?= count($daftar) ?> akun</p>

<?php if (! $daftar): ?>
    <div class="panel kosong"><p>Akun tidak ditemukan.</p></div>
<?php endif; ?>

<div class="alamat-list">
    <?php foreach ($daftar as $a): ?>
        <?php
        $st = (string) ($a['status'] ?? 'aktif');
        [$kelas, $teks] = $badgeSt[$st] ?? ['badge-off', strtoupper($st)];
        $saya     = $a['peran'] === 'admin' && (int) $a['id'] === $idSaya;
        $nonaktif = $st === 'nonaktif';
        ?>
        <div class="alamat-card<?= $nonaktif ? ' nonaktif' : '' ?>">
            <div class="alamat-top">
                <strong><?= $e((string) $a['nama']) ?><?= $saya ? ' (kamu)' : '' ?></strong>
                <span class="peran peran-<?= $e((string) $a['peran']) ?>"><?= $labelPeran[$a['peran']] ?></span>
                <span class="<?= $kelas ?>"><?= $teks ?></span>
            </div>
            <p class="penerima"><?= $e((string) $a['no_hp']) ?></p>
            <p class="detail"><?= $e((string) $a['email']) ?></p>

            <div class="alamat-aksi">
                <?php if ($saya): ?>
                    <span class="info-kecil">Akun yang sedang kamu pakai</span>
                <?php elseif ($st === 'bertugas'): ?>
                    <span class="info-kecil">Sedang bertugas</span>
                <?php else: ?>
                    <form action="<?= base_url('admin/kelola/status/' . $a['peran'] . '/' . $a['id']) ?>" method="post"
                          data-konfirmasi="<?= $nonaktif ? 'Aktifkan kembali akun ini?' : 'Nonaktifkan akun ini? Pemiliknya tidak bisa masuk.' ?>">
                        <?= csrf_field() ?>
                        <button type="submit" <?= $nonaktif ? '' : 'class="hapus"' ?>><?= $nonaktif ? 'Aktifkan' : 'Nonaktifkan' ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>