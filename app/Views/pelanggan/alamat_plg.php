<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
$user   = (array) ($user ?? []);
$sukses = (string) (session()->getFlashdata('success') ?? '');
$e      = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');

$namaUser        = $e((string) ($user['nama']              ?? '-'));
$noHp            = $e((string) ($user['no_hp']             ?? '-'));
$alamat          = (string) ($user['alamat']               ?? '');
$alamatAlternatif = (string) ($user['alamat_alternatif']   ?? '');
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

    /* ===== ALAMAT GRID ===== */
    .alamat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 14px;
        margin-bottom: 16px;
    }
    .alamat-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        background: #fff;
        display: flex;
        flex-direction: column;
        gap: 5px;
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
        padding: 2px 9px;
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
    .alamat-card-kosong {
        font-size: 13px;
        color: #94a3b8;
        font-style: italic;
    }
    .alamat-card-actions {
        display: flex;
        gap: 16px;
        margin-top: 10px;
        border-top: 1px solid #f1f5f9;
        padding-top: 10px;
    }
    .btn-ubah-alamat {
        background: none;
        border: none;
        padding: 0;
        font-size: 13px;
        font-weight: 600;
        color: #033B59;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-ubah-alamat:hover { text-decoration: underline; color: #033B59; }

    .btn-hapus-alamat {
        background: none;
        border: none;
        padding: 0;
        font-size: 13px;
        font-weight: 600;
        color: #ef4444;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-hapus-alamat:hover { text-decoration: underline; color: #dc2626; }

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
    }
    .btn-tambah-alamat:hover {
        background: #022d44;
        color: #fff;
        text-decoration: none;
    }

    /* Konfirmasi hapus modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.aktif { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        width: 90%;
        max-width: 340px;
        text-align: center;
    }
    .modal-box h5 { margin: 0 0 8px; font-size: 16px; color: #0f172a; }
    .modal-box p  { margin: 0 0 18px; font-size: 13px; color: #64748b; }
    .modal-actions { display: flex; gap: 10px; justify-content: center; }
    .btn-modal-batal {
        padding: 8px 20px; border-radius: 8px;
        border: 1.5px solid #e2e8f0; background: #fff;
        font-size: 13px; font-weight: 600; cursor: pointer; color: #0f172a;
    }
    .btn-modal-hapus {
        padding: 8px 20px; border-radius: 8px;
        border: none; background: #ef4444;
        font-size: 13px; font-weight: 600; cursor: pointer; color: #fff;
    }
    .btn-modal-hapus:hover { background: #dc2626; }
</style>

<div class="judul-halaman">
    <a href="<?= base_url('profil/data') ?>" class="kembali" aria-label="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h3>Alamat Saya</h3>
</div>

<?php if ($sukses !== ''): ?>
    <div class="alert ok"><?= $e($sukses) ?></div>
<?php endif; ?>

<div class="alamat-grid">

    <!-- ALAMAT UTAMA -->
    <div class="alamat-card">
        <div class="alamat-card-header">
            <span class="alamat-card-label">Rumah</span>
            <span class="badge-utama">Utama</span>
        </div>
        <p class="alamat-card-info"><?= $namaUser ?> &middot; <?= $noHp ?></p>
        <?php if ($alamat !== ''): ?>
            <p class="alamat-card-teks"><?= $e($alamat) ?></p>
        <?php else: ?>
            <p class="alamat-card-kosong">Belum diisi</p>
        <?php endif; ?>
        <div class="alamat-card-actions">
            <a href="<?= base_url('profil/alamat/ubah?tipe=utama') ?>" class="btn-ubah-alamat">
                <i class="bi bi-pencil-fill"></i> Ubah
            </a>
            <?php if ($alamat !== ''): ?>
            <button type="button" class="btn-hapus-alamat" onclick="bukaModal('utama')">
                <i class="bi bi-trash-fill"></i> Hapus
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ALAMAT ALTERNATIF -->
    <div class="alamat-card">
        <div class="alamat-card-header">
            <span class="alamat-card-label">Kost / Alternatif</span>
        </div>
        <p class="alamat-card-info"><?= $namaUser ?> &middot; <?= $noHp ?></p>
        <?php if ($alamatAlternatif !== ''): ?>
            <p class="alamat-card-teks"><?= $e($alamatAlternatif) ?></p>
        <?php else: ?>
            <p class="alamat-card-kosong">Belum diisi</p>
        <?php endif; ?>
        <div class="alamat-card-actions">
            <a href="<?= base_url('profil/alamat/ubah?tipe=alternatif') ?>" class="btn-ubah-alamat">
                <i class="bi bi-pencil-fill"></i> Ubah
            </a>
            <?php if ($alamatAlternatif !== ''): ?>
            <button type="button" class="btn-hapus-alamat" onclick="bukaModal('alternatif')">
                <i class="bi bi-trash-fill"></i> Hapus
            </button>
            <?php endif; ?>
        </div>
    </div>

</div>

<a href="<?= base_url('profil/alamat/ubah') ?>" class="btn-tambah-alamat">
    <i class="bi bi-plus-lg"></i> Tambah / Ubah Alamat
</a>

<!-- Modal konfirmasi hapus -->
<div class="modal-overlay" id="modalHapus">
    <div class="modal-box">
        <h5><i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;"></i> Hapus Alamat</h5>
        <p>Alamat ini akan dihapus. Lanjutkan?</p>
        <div class="modal-actions">
            <button class="btn-modal-batal" onclick="tutupModal()">Batal</button>
            <form id="formHapus" method="post" action="<?= base_url('profil/alamat/hapus') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="tipe" id="inputTipeHapus" value="">
                <button type="submit" class="btn-modal-hapus">
                    <i class="bi bi-trash-fill"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function bukaModal(tipe) {
    document.getElementById('inputTipeHapus').value = tipe;
    document.getElementById('modalHapus').classList.add('aktif');
}
function tutupModal() {
    document.getElementById('modalHapus').classList.remove('aktif');
}
</script>

<?= $this->endSection() ?>
