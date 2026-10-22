<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
$user   = (array) ($user ?? []);
$errors = (array) (session()->getFlashdata('errors') ?? []);
$e      = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$jk     = (string) old('jenis_kelamin', (string) ($user['jenis_kelamin'] ?? ''));
<<<<<<< HEAD
?>

<div class="judul-halaman">
    <a href="<?= base_url('profil/data') ?>" class="kembali">&larr;</a>
=======

$alamatNilai = (string) old('alamat', (string) ($user['alamat'] ?? ''));
?>

<div class="judul-halaman">
    <a href="<?= base_url('profil/data') ?>" class="kembali" aria-label="Kembali"><i class="bi bi-arrow-left"></i></a>
>>>>>>> origin/digiwash-semoga-final
    <h3>Ubah Data Pribadi</h3>
</div>

<div class="panel">
    <?php if ($errors): ?>
        <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('profil/data/simpan') ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label>Nama Lengkap</label>
<<<<<<< HEAD
        <input type="text" name="nama" value="<?= $e((string) old('nama', (string) ($user['nama'] ?? ''))) ?>">

        <label>Nomor Telepon</label>
        <input type="text" name="no_hp" value="<?= $e((string) old('no_hp', (string) ($user['no_hp'] ?? ''))) ?>">
=======
        <input type="text" name="nama" value="<?= $e((string) old('nama', (string) ($user['nama'] ?? ''))) ?>" required>

        <label>Nomor Telepon</label>
        <input type="text" name="no_hp" value="<?= $e((string) old('no_hp', (string) ($user['no_hp'] ?? ''))) ?>" required>
>>>>>>> origin/digiwash-semoga-final

        <label>Email</label>
        <input type="email" value="<?= $e((string) ($user['email'] ?? '')) ?>" readonly class="readonly">

<<<<<<< HEAD
=======
        <label>Alamat Lengkap</label>
        <textarea name="alamat" rows="3" placeholder="Contoh: Jl. Lambung Mangkurat No. 12, Banjarmasin"><?= $e($alamatNilai) ?></textarea>

>>>>>>> origin/digiwash-semoga-final
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="<?= $e((string) old('tanggal_lahir', (string) ($user['tanggal_lahir'] ?? ''))) ?>">

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin">
            <option value="">- Pilih -</option>
            <option value="Laki-laki" <?= $jk === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Perempuan" <?= $jk === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
        </select>

<<<<<<< HEAD
        <button type="submit" class="btn btn-dark btn-small">Simpan</button>
=======
        <button type="submit" class="btn btn-dark btn-small" style="background:#033B59; color:#fff; border:none; padding:10px 24px; border-radius:8px; cursor:pointer; margin-top:10px;">Simpan</button>
>>>>>>> origin/digiwash-semoga-final
    </form>
</div>
<?= $this->endSection() ?>