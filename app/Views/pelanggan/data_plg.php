<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
<<<<<<< HEAD
$user  = (array) ($user ?? []);
$utama = isset($utama) && is_array($utama) ? $utama : null;
=======
$user   = (array) ($user ?? []);
>>>>>>> origin/digiwash-semoga-final
$sukses = (string) (session()->getFlashdata('success') ?? '');
$e      = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');

$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tgl   = '-';
if (! empty($user['tanggal_lahir'])) {
    $t = strtotime((string) $user['tanggal_lahir']);
    if ($t !== false) {
        $tgl = date('j', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
    }
}

<<<<<<< HEAD
$alamatTeks = $utama ? (string) $utama['alamat_lengkap'] : 'Belum diatur';
?>

<div class="judul-halaman">
    <a href="<?= base_url('profil') ?>" class="kembali">&larr;</a>
=======
$alamatTeks = ! empty($user['alamat']) ? (string) $user['alamat'] : '';
$namaUser   = $e((string) ($user['nama'] ?? '-'));
$noHp       = $e((string) ($user['no_hp'] ?? '-'));
?>

<style>
    .daftar-baris .ikon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        font-size: 20px;
        line-height: 1;
        color: #033B59;
    }
    .daftar-baris .panah {
        display: inline-flex;
        align-items: center;
        font-size: 16px;
        line-height: 1;
        color: #64748b;
    }
    .judul-halaman .kembali {
        display: inline-flex;
        align-items: center;
        font-size: 22px;
        line-height: 1;
        text-decoration: none;
        color: #033B59;
    }

    /* ===== ALAMAT CARD STYLE ===== */
    .alamat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 14px;
        margin-bottom: 14px;
    }
    .alamat-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        background: #fff;
        display: flex;
        flex-direction: column;
        gap: 6px;
        position: relative;
    }
    .alamat-card-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2px;
    }
    .alamat-card-label {
        font-weight: 700;
        font-size: 15px;
        color: #0f172a;
    }
    .badge-utama {
        background: #033B59;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .alamat-card-info {
        font-size: 13px;
        color: #475569;
        margin: 0;
    }
    .alamat-card-teks {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin: 2px 0;
        line-height: 1.5;
    }
    .alamat-card-catatan {
        font-size: 12px;
        color: #64748b;
    }
    .alamat-card-actions {
        display: flex;
        gap: 14px;
        margin-top: 8px;
        border-top: 1px solid #f1f5f9;
        padding-top: 10px;
    }
    .btn-alamat-ubah {
        background: none;
        border: none;
        padding: 0;
        font-size: 13px;
        font-weight: 600;
        color: #033B59;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-alamat-ubah:hover { text-decoration: underline; color: #033B59; }
    .btn-alamat-hapus {
        background: none;
        border: none;
        padding: 0;
        font-size: 13px;
        font-weight: 600;
        color: #ef4444;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-alamat-hapus:hover { text-decoration: underline; color: #dc2626; }

    .btn-tambah-alamat {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        background: #033B59;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
        margin-top: 4px;
    }
    .btn-tambah-alamat:hover {
        background: #022d44;
        color: #fff;
        text-decoration: none;
    }

    .alamat-kosong {
        border: 1.5px dashed #cbd5e1;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        color: #94a3b8;
        font-size: 14px;
        margin-bottom: 14px;
    }
    .alamat-kosong i {
        font-size: 32px;
        display: block;
        margin-bottom: 8px;
        color: #cbd5e1;
    }
</style>

<div class="judul-halaman">
    <a href="<?= base_url('profil') ?>" class="kembali" aria-label="Kembali"><i class="bi bi-arrow-left"></i></a>
>>>>>>> origin/digiwash-semoga-final
    <h3>Data Pribadi</h3>
</div>

<?php if ($sukses !== ''): ?>
    <div class="alert ok"><?= $e($sukses) ?></div>
<?php endif; ?>

<p class="subjudul">Informasi Akun</p>
<div class="daftar-baris">
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
<<<<<<< HEAD
        <span class="ikon">👤</span>
        <span class="teks"><small>Nama Lengkap</small><strong><?= $e((string) ($user['nama'] ?? '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon">📞</span>
        <span class="teks"><small>Nomor Telepon</small><strong><?= $e((string) ($user['no_hp'] ?? '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <div class="baris">
        <span class="ikon">✉️</span>
        <span class="teks"><small>Email</small><strong><?= $e((string) ($user['email'] ?? '-')) ?></strong></span>
    </div>
    <a class="baris" href="<?= base_url('alamat') ?>">
        <span class="ikon">📍</span>
        <span class="teks"><small>Alamat</small><strong><?= $e($alamatTeks) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon">📅</span>
        <span class="teks"><small>Tanggal Lahir</small><strong><?= $e($tgl) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon">⚧</span>
        <span class="teks"><small>Jenis Kelamin</small><strong><?= $e((string) (($user['jenis_kelamin'] ?? '') ?: '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
</div>

<p class="subjudul">Keamanan Akun</p>
<div class="daftar-baris">
    <div class="baris">
        <span class="ikon">🛡️</span>
=======
        <span class="ikon"><i class="bi bi-person-fill"></i></span>
        <span class="teks"><small>Nama Lengkap</small><strong><?= $e((string) ($user['nama'] ?? '-')) ?></strong></span>
        <span class="panah"><i class="bi bi-chevron-right"></i></span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon"><i class="bi bi-telephone-fill"></i></span>
        <span class="teks"><small>Nomor Telepon</small><strong><?= $e((string) ($user['no_hp'] ?? '-')) ?></strong></span>
        <span class="panah"><i class="bi bi-chevron-right"></i></span>
    </a>
    <div class="baris">
        <span class="ikon"><i class="bi bi-envelope-fill"></i></span>
        <span class="teks"><small>Email</small><strong><?= $e((string) ($user['email'] ?? '-')) ?></strong></span>
    </div>
    <a class="baris" href="<?= base_url('profil/alamat') ?>">
        <span class="ikon"><i class="bi bi-geo-alt-fill"></i></span>
        <span class="teks"><small>Alamat</small><strong><?= $alamatTeks !== '' ? $e(mb_strimwidth($alamatTeks, 0, 40, '...')) : 'Belum diatur' ?></strong></span>
        <span class="panah"><i class="bi bi-chevron-right"></i></span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon"><i class="bi bi-calendar-event-fill"></i></span>
        <span class="teks"><small>Tanggal Lahir</small><strong><?= $e($tgl) ?></strong></span>
        <span class="panah"><i class="bi bi-chevron-right"></i></span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon"><i class="bi bi-gender-ambiguous"></i></span>
        <span class="teks"><small>Jenis Kelamin</small><strong><?= $e((string) (($user['jenis_kelamin'] ?? '') ?: '-')) ?></strong></span>
        <span class="panah"><i class="bi bi-chevron-right"></i></span>
    </a>
</div>



<p class="subjudul" style="margin-top:20px;">Keamanan Akun</p>
<div class="daftar-baris">
    <div class="baris">
        <span class="ikon"><i class="bi bi-shield-check"></i></span>
>>>>>>> origin/digiwash-semoga-final
        <span class="teks">Verifikasi Email</span>
        <span class="badge-ok">TERVERIFIKASI</span>
    </div>
    <div class="baris">
<<<<<<< HEAD
        <span class="ikon">🛡️</span>
=======
        <span class="ikon"><i class="bi bi-shield-check"></i></span>
>>>>>>> origin/digiwash-semoga-final
        <span class="teks">Verifikasi Nomor</span>
        <span class="badge-ok">TERVERIFIKASI</span>
    </div>
</div>
<?= $this->endSection() ?>