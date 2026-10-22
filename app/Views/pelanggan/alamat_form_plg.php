<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
<<<<<<< HEAD
$alamat = (array) ($alamat ?? []);
$errors = (array) (session()->getFlashdata('errors') ?? []);
$e      = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');

$ubah  = ! empty($alamat['id_alamat']);
$aksi  = $ubah
    ? base_url('alamat/update/' . $alamat['id_alamat'])
    : base_url('alamat/simpan');
$nilai = static fn (string $kunci): string => (string) old($kunci, (string) ($alamat[$kunci] ?? ''));
$utama = (string) old('is_default', (string) ($alamat['is_default'] ?? '0')) === '1';
?>

<div class="judul-halaman">
    <a href="<?= base_url('alamat') ?>" class="kembali">&larr;</a>
    <h3><?= $ubah ? 'Ubah Alamat' : 'Tambah Alamat Baru' ?></h3>
=======
$user    = (array) ($user ?? []);
$errors  = (array) (session()->getFlashdata('errors') ?? []);
$e       = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$tipe    = (string) ($tipe ?? 'utama'); // 'utama' atau 'alternatif'
$isAlt   = $tipe === 'alternatif';
$labelJudul = $isAlt ? 'Kost / Alternatif' : 'Rumah (Utama)';
$fieldKey   = $isAlt ? 'alamat_alternatif' : 'alamat';
$nilaiLama  = (string) old($fieldKey, (string) ($user[$fieldKey] ?? ''));
?>

<style>
    .judul-halaman .kembali {
        display: inline-flex;
        align-items: center;
        font-size: 22px;
        line-height: 1;
        text-decoration: none;
        color: #033B59;
    }
</style>

<div class="judul-halaman">
    <a href="<?= base_url('profil/alamat') ?>" class="kembali" aria-label="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h3><?= $isAlt ? 'Ubah Alamat Alternatif' : 'Ubah Alamat Utama' ?></h3>
>>>>>>> origin/digiwash-semoga-final
</div>

<div class="panel">
    <?php if ($errors): ?>
<<<<<<< HEAD
        <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
    <?php endif; ?>

    <form action="<?= $e($aksi) ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label>Label Alamat</label>
        <input type="text" name="label" placeholder="Contoh: Rumah, Kantor, Kos" value="<?= $e($nilai('label')) ?>">

        <label>Nama Penerima</label>
        <input type="text" name="nama_penerima" placeholder="Nama penerima" value="<?= $e($nilai('nama_penerima')) ?>">

        <label>Nomor Telepon</label>
        <input type="text" name="no_hp" placeholder="08XXXXXXXXXX" value="<?= $e($nilai('no_hp')) ?>">

        <label>Alamat Lengkap</label>
        <textarea name="alamat_lengkap" rows="4" placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kota"><?= $e($nilai('alamat_lengkap')) ?></textarea>

        <label>Catatan untuk Kurir (opsional)</label>
        <textarea name="catatan_kurir" rows="2" placeholder="Contoh: Pagar warna hijau, titip ke satpam"><?= $e($nilai('catatan_kurir')) ?></textarea>

        <label class="check">
            <input type="checkbox" name="is_default" value="1" <?= $utama ? 'checked' : '' ?>>
            Jadikan alamat utama
        </label>

        <button type="submit" class="btn btn-dark btn-small">Simpan</button>
    </form>
</div>
<?= $this->endSection() ?>
=======
        <div class="alert">
            <?= implode('<br>', array_map($e, array_map('strval', $errors))) ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('profil/alamat/simpan') ?>" method="post" class="form">
        <?= csrf_field() ?>
        <input type="hidden" name="tipe" value="<?= $e($tipe) ?>">

        <label>
            <i class="bi bi-geo-alt-fill" style="color:#033B59;"></i>
            Alamat <?= $e($labelJudul) ?>
        </label>
        <textarea
            name="<?= $e($fieldKey) ?>"
            rows="4"
            placeholder="Contoh: Jl. A. Yani KM 4 No. 56 RT 31 RW 02, Banjarmasin"
            style="resize:vertical;"
        ><?= $e($nilaiLama) ?></textarea>

        <div style="display:flex; gap:10px; margin-top:14px; flex-wrap:wrap;">
            <a href="<?= base_url('profil/alamat') ?>"
               style="padding:10px 20px; border-radius:8px; border:1.5px solid #e2e8f0;
                      background:#fff; font-size:13px; font-weight:600; color:#0f172a; text-decoration:none;">
                Batal
            </a>
            <button type="submit"
                    style="padding:10px 24px; border-radius:8px; border:none;
                           background:#033B59; color:#fff; font-size:13px; font-weight:600; cursor:pointer;">
                <i class="bi bi-check-lg"></i> Simpan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
>>>>>>> origin/digiwash-semoga-final
