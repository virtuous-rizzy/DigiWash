<?php
helper('foto');

$e       = static fn (string $t): string => htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
$nama    = (string) session()->get('nama');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : 'A';
$foto    = foto_profil();
$aktif   = (string) ($aktif ?? '');
$sukses  = (string) (session()->getFlashdata('success') ?? '');
$errors  = (array) (session()->getFlashdata('errors') ?? []);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digiwash Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('css/digiwash.css') ?>">
    <style>
        .bi { line-height: 1; vertical-align: -0.125em; }
        .logo .bi { margin-right: 6px; }

        .bottom-nav a {
            color: #033B59;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .bottom-nav a span {
            color: #033B59;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            position: relative;
        }
        .bottom-nav a.active span {
            background: #8fe0e8;
            border-radius: 50%;
            padding: 4px 8px;
            color: #033B59;
        }
        @media (min-width: 900px) {
            .bottom-nav a {
                flex-direction: row;
            }
            .dash-wrap .bottom-nav a.active {
                background: #8fe0e8;
                color: #033B59;
            }
            .dash-wrap .bottom-nav a.active span {
                background: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
<div class="dash-wrap">
    <div class="dash">

        <div class="dash-head">
            <div class="logo"><i class="bi bi-basket2-fill" style="color: #033B59; margin-right: 6px;"></i>DIGIWASH</div>

            <nav class="bottom-nav">
                <a href="<?= base_url('admin/dashboard') ?>" class="<?= $aktif === 'beranda' ? 'active' : '' ?>"><span><i class="bi bi-house-door-fill"></i></span>Beranda</a>
                <a href="<?= base_url('admin/status-laundry') ?>" class="<?= $aktif === 'pesanan' ? 'active' : '' ?>"><span><i class="bi bi-box-seam-fill"></i></span>Pesanan</a>
                <a href="<?= base_url('admin/transaksi') ?>" class="<?= $aktif === 'transaksi' ? 'active' : '' ?>"><span><i class="bi bi-currency-dollar"></i></span>Transaksi</a>
                <a href="<?= base_url('admin/akun') ?>" class="<?= $aktif === 'akun' ? 'active' : '' ?>"><span><i class="bi bi-person-fill"></i></span>Akun</a>
            </nav>

            <div class="avatar">
    <?php if ($foto): ?>
        <img src="<?= $e($foto) ?>" alt="">
    <?php else: ?>
        <?= $e($inisial) ?>
    <?php endif; ?>
</div>
        </div>

        <?php if ($sukses !== ''): ?>
            <div class="alert ok"><?= $e($sukses) ?></div>
        <?php endif; ?>
        <?php if ($errors): ?>
            <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
        <?php endif; ?>

        <?= $this->renderSection('isi') ?>

    </div>
</div>

<div id="toast" class="toast" role="status" aria-live="polite"></div>

<!-- ══ MODAL KONFIRMASI BOOTSTRAP UNIVERSAL ADMIN ══ -->
<div class="modal fade" id="modalKonfirmasiAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #033B59; color: #fff; padding: 14px 20px;">
                <h6 class="modal-title fw-bold" id="modalKonfirmasiAdminTitle" style="margin: 0; font-size: 15px;">
                    <i class="bi bi-question-circle me-2"></i>Konfirmasi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="modalKonfirmasiAdminIcon" class="mb-3">
                    <i class="bi bi-exclamation-circle text-warning" style="font-size: 48px;"></i>
                </div>
                <p id="modalKonfirmasiAdminPesan" style="font-size: 14px; color: #333; line-height: 1.5; margin: 0; font-weight: 500;"></p>
            </div>
            <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 10px; padding: 12px 20px;">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="btnKonfirmasiAdminSubmit" class="btn btn-sm btn-primary rounded-3 px-3 fw-bold" style="background: #033B59; border-color: #033B59;">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var toast = document.getElementById('toast'), timer;
    function tampilkan(p) {
        toast.textContent = p; toast.classList.add('show');
        clearTimeout(timer);
        timer = setTimeout(function () { toast.classList.remove('show'); }, 2000);
    }
    document.querySelectorAll('[data-soon]').forEach(function (el) {
        el.addEventListener('click', function (ev) { ev.preventDefault(); tampilkan('Fitur segera hadir'); });
    });

    var targetAction = null;
    var modalEl = document.getElementById('modalKonfirmasiAdmin');
    var bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? new bootstrap.Modal(modalEl) : null;

    function bukaModalKonfirmasi(pesan, aksi, isDanger, judul) {
        document.getElementById('modalKonfirmasiAdminPesan').textContent = pesan;
        document.getElementById('modalKonfirmasiAdminTitle').innerHTML = '<i class="bi ' + (isDanger ? 'bi-exclamation-triangle-fill' : 'bi-question-circle-fill') + ' me-2"></i>' + (judul || 'Konfirmasi');
        
        var iconEl = document.getElementById('modalKonfirmasiAdminIcon');
        iconEl.innerHTML = '<i class="bi ' + (isDanger ? 'bi-exclamation-triangle-fill' : 'bi-question-circle-fill') + '" style="font-size: 46px; color: ' + (isDanger ? '#E53935' : '#033B59') + ';"></i>';
        
        var btnSubmit = document.getElementById('btnKonfirmasiAdminSubmit');
        var isLogout = (judul && judul.toLowerCase().includes('logout'));
        if (isDanger) {
            btnSubmit.className = 'btn btn-sm btn-danger rounded-3 px-3 fw-bold';
            btnSubmit.style.background = '#E53935';
            btnSubmit.style.borderColor = '#E53935';
            btnSubmit.textContent = isLogout ? 'Ya, Keluar' : 'Ya, Lanjutkan';
        } else {
            btnSubmit.className = 'btn btn-sm btn-primary rounded-3 px-3 fw-bold';
            btnSubmit.style.background = '#033B59';
            btnSubmit.style.borderColor = '#033B59';
            btnSubmit.textContent = 'Ya, Lanjutkan';
        }

        targetAction = aksi;
        if (bsModal) {
            bsModal.show();
        } else {
            if (confirm(pesan)) aksi();
        }
    }

    document.getElementById('btnKonfirmasiAdminSubmit').addEventListener('click', function () {
        if (bsModal) bsModal.hide();
        if (typeof targetAction === 'function') {
            targetAction();
        }
    });

    document.querySelectorAll('a[data-konfirmasi]').forEach(function (el) {
        el.addEventListener('click', function (ev) {
            ev.preventDefault();
            var pesan = el.getAttribute('data-konfirmasi');
            var isLogout = el.classList.contains('btn-logout') || el.href.includes('keluar');
            bukaModalKonfirmasi(pesan, function () {
                window.location.href = el.href;
            }, isLogout, isLogout ? 'Konfirmasi Logout' : 'Konfirmasi Tindakan');
        });
    });

    document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
        f.addEventListener('submit', function (ev) {
            if (f.dataset.confirmed === 'true') return;
            ev.preventDefault();
            var pesan = f.getAttribute('data-konfirmasi');
            var isDanger = pesan.toLowerCase().includes('nonaktifkan') || pesan.toLowerCase().includes('hapus');
            bukaModalKonfirmasi(pesan, function () {
                f.dataset.confirmed = 'true';
                f.submit();
            }, isDanger, isDanger ? 'Konfirmasi Nonaktifkan / Hapus' : 'Konfirmasi Tindakan');
        });
    });
})();
</script>
</body>
</html>