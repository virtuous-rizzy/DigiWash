<?= $this->extend('admin/layout_adm') ?>

<?= $this->section('isi') ?>
<?php
$e = static fn(string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$daftar = (array) ($daftar ?? []);
$filter = (string) ($filter ?? 'semua');
$q = (string) ($q ?? '');
$jumlah = (array) ($jumlah ?? []);
$idSaya = (int) ($idSaya ?? 0);

$tab = ['semua' => 'Semua', 'admin' => 'Admin', 'kurir' => 'Kurir', 'pelanggan' => 'Pelanggan'];
$labelPeran = ['admin' => 'ADMIN', 'kurir' => 'KURIR', 'pelanggan' => 'PELANGGAN'];
$badgeSt = [
    'aktif' => ['badge-ok', 'AKTIF'],
    'tersedia' => ['badge-ok', 'TERSEDIA'],
    'bertugas' => ['badge-warn', 'BERTUGAS'],
    'nonaktif' => ['badge-off', 'NONAKTIF'],
];
?>

<div class="judul-halaman">
    <a href="<?= base_url('admin/dashboard') ?>" class="kembali">&larr;</a>
    <h3>Kelola Akun</h3>
</div>

<a href="<?= base_url('admin/kelola/tambah') ?>" class="btn btn-dark btn-tambah" style="margin-top:6px">+ Tambah Akun
    Admin atau Kurir</a>

<div class="chips">
    <?php foreach ($tab as $kunci => $label): ?>
        <?php
        $n = $kunci === 'semua' ? array_sum($jumlah) : (int) ($jumlah[$kunci] ?? 0);
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

<?php if (!$daftar): ?>
    <div class="panel kosong">
        <p>Akun tidak ditemukan.</p>
    </div>
<?php endif; ?>

<div class="alamat-list">
    <?php foreach ($daftar as $a): ?>
        <?php
        $st = (string) ($a['status'] ?? 'aktif');
        [$kelas, $teks] = $badgeSt[$st] ?? ['badge-off', strtoupper($st)];
        $saya = $a['peran'] === 'admin' && (int) $a['id'] === $idSaya;
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
                    <button type="button" class="btn-reset-pw" data-peran="<?= $e((string) $a['peran']) ?>"
                        data-id="<?= (int) $a['id'] ?>" data-nama="<?= $e((string) $a['nama']) ?>">
                        Reset Password
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- ══ MODAL RESET PASSWORD ══ -->
<div class="modal fade" id="modalResetPw" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
        <div class="modal-content"
            style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 10px 30px rgba(0,0,0,0.2)">
            <div class="modal-header" style="background:#033B59;color:#fff;padding:14px 20px">
                <h6 class="modal-title fw-bold" style="margin:0;font-size:15px">
                    <i class="bi bi-key-fill me-2"></i>Reset Kata Sandi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <form id="formResetPw" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <p id="namaResetPw" style="font-weight:600;margin-bottom:12px;font-size:14px;color:#033B59"></p>
                    <div class="mb-3">
                        <label style="font-size:13px;font-weight:500;display:block;margin-bottom:4px">Password
                            Baru</label>
                        <input type="password" name="password_baru" class="form-control form-control-sm"
                            placeholder="Minimal 6 karakter" required minlength="6">
                    </div>
                    <div>
                        <label style="font-size:13px;font-weight:500;display:block;margin-bottom:4px">Ulangi Password
                            Baru</label>
                        <input type="password" name="password_ulang" class="form-control form-control-sm"
                            placeholder="Ketik ulang password baru" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer"
                    style="background:#f8f9fa;border-top:1px solid #eee;display:flex;justify-content:flex-end;gap:10px;padding:12px 20px">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-3 px-3 fw-bold"
                        style="background:#033B59;border-color:#033B59">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Pakai event delegation — Bootstrap belum load saat section ini di-render,
    // jadi inisialisasi modal dilakukan secara lazy saat tombol diklik.
    document.addEventListener('click', function (ev) {
        var btn = ev.target.closest('.btn-reset-pw');
        if (!btn) return;

        var peran = btn.dataset.peran;
        var id = btn.dataset.id;
        var nama = btn.dataset.nama;

        document.getElementById('namaResetPw').textContent = 'Reset password untuk: ' + nama;
        document.getElementById('formResetPw').action =
            '<?= base_url('admin/kelola/reset-password') ?>/' + peran + '/' + id;
        document.querySelectorAll('#modalResetPw input[type=password]')
            .forEach(function (i) { i.value = ''; });

        // Bootstrap sudah pasti load di sini karena user baru bisa klik setelah halaman selesai
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalResetPw')).show();
    });
</script>

<?= $this->endSection() ?>