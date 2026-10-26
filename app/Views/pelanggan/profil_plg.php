<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
helper('foto');

$user = (array) ($user ?? []);
$nama = (string) ($user['nama'] ?? '');
$noHp = (string) ($user['no_hp'] ?? '');
$sukses = (string) (session()->getFlashdata('success') ?? '');
$errors = (array) (session()->getFlashdata('errors') ?? []);

$e = static fn(string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : '?';
$foto = foto_profil();
?>

<!-- Bootstrap Icons (aman dobel, browser cuma load sekali) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --warna-utama: #033B59;
    }

    /* Tombol kembali */
    .judul-halaman .kembali {
        display: inline-flex;
        align-items: center;
        font-size: 22px;
        line-height: 1;
    }

    /* ── Hero profil: foto, nama, no HP ── */
    .profil-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profil-hero form {
        margin: 0;
    }

    .profil-hero .foto-wrap {
        position: relative;
        display: block;
        width: 96px;
        height: 96px;
        margin: 0 auto 16px;
        /* jarak foto → nama */
        cursor: pointer;
        flex-shrink: 0;
    }

    .profil-hero .hero-foto,
    .profil-hero .hero-avatar {
        width: 96px;
        height: 96px;
        margin: 0;
        border-radius: 50%;
        display: block;
    }

    .profil-hero .hero-foto {
        object-fit: cover;
    }

    .profil-hero .hero-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--warna-utama);
        color: #fff;
        font-size: 40px;
        font-weight: 700;
    }

    .profil-hero .foto-edit {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--warna-utama);
        border: 2px solid #fff;
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 13px;
    }

    .profil-hero h3 {
        margin: 0 0 4px;
        line-height: 1.3;
    }

    .profil-hero p {
        margin: 0;
    }

    .profil-hero .hapus-foto {
        margin-top: 8px;
    }

    /* ── Ikon menu ── */
    .daftar-baris .ikon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        font-size: 20px;
        line-height: 1;
        color: var(--warna-utama);
    }

    .daftar-baris .panah {
        display: inline-flex;
        align-items: center;
        font-size: 16px;
        line-height: 1;
    }
</style>

<div class="judul-halaman">
    <a href="<?= base_url('dashboard') ?>" class="kembali" aria-label="Kembali"><i class="bi bi-arrow-left"></i></a>
    <h3>Profil Saya</h3>
</div>

<?php if ($sukses !== ''): ?>
    <div class="alert ok"><?= $e($sukses) ?></div>
<?php endif; ?>
<?php if ($errors): ?>
    <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
<?php endif; ?>

<div class="profil-hero">
    <form action="<?= base_url('profil/foto') ?>" method="post" enctype="multipart/form-data" id="formFoto">
        <?= csrf_field() ?>
        <label class="foto-wrap" for="inputFoto" title="Ganti foto profil">
            <?php if ($foto): ?>
                <img src="<?= $e($foto) ?>" alt="Foto profil" class="hero-foto">
            <?php else: ?>
                <div class="hero-avatar"><?= $e($inisial) ?></div>
            <?php endif; ?>
            <span class="foto-edit"><i class="bi bi-camera-fill"></i></span>
        </label>
        <input type="file" name="foto" id="inputFoto" accept="image/png,image/jpeg,image/webp" hidden>
    </form>

    <h3><?= $e($nama) ?></h3>
    <p><?= $e($noHp) ?></p>

    <?php if ($foto): ?>
        <form action="<?= base_url('profil/foto/hapus') ?>" method="post" data-konfirmasi="Hapus foto profil?">
            <?= csrf_field() ?>
            <button type="submit" class="hapus-foto">Hapus foto</button>
        </form>
    <?php endif; ?>
</div>

<div class="daftar-baris">
    <a class="baris" href="<?= base_url('profil/data') ?>"><span class="ikon"><i
                class="bi bi-person-fill"></i></span><span class="teks">Data Pribadi</span><span class="panah"><i
                class="bi bi-chevron-right"></i></span></a>
    <a class="baris" href="<?= base_url('profil/password') ?>"><span class="ikon"><i
                class="bi bi-lock-fill"></i></span><span class="teks">Ganti Kata Sandi</span><span class="panah"><i
                class="bi bi-chevron-right"></i></span></a>
    <a class="baris" href="<?= base_url('profil/bantuan') ?>"><span class="ikon"><i
                class="bi bi-question-circle-fill"></i></span><span class="teks">Bantuan</span><span class="panah"><i
                class="bi bi-chevron-right"></i></span></a>
    <a class="baris" href="<?= base_url('profil/tentang') ?>"><span class="ikon"><i
                class="bi bi-info-circle-fill"></i></span><span class="teks">Tentang Aplikasi</span><span
            class="panah"><i class="bi bi-chevron-right"></i></span></a>
</div>

<a href="<?= base_url('keluar') ?>" class="btn-logout" data-konfirmasi="Yakin ingin keluar?">Keluar</a>

<script>
    (function () {
        var input = document.getElementById('inputFoto');
        if (input) {
            input.addEventListener('change', function () {
                var f = input.files[0];
                if (!f) return;
                if (f.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 2 MB.');
                    input.value = '';
                    return;
                }
                document.getElementById('formFoto').submit();
            });
        }
    })();
</script>
<?= $this->endSection() ?>