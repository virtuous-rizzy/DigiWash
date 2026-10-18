<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
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
</div>

<div class="panel">
    <?php if ($errors): ?>
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
