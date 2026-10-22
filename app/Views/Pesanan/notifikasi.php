<?php
$judul = 'Notifikasi';
?>
<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<style>
    .judul-halaman {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 18px 0 16px;
    }
    .judul-halaman .kembali {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: #0b3d5c;
        font-size: 22px;
        text-decoration: none;
        background: transparent;
        transition: background 0.15s, transform 0.15s;
    }
    .judul-halaman .kembali:hover {
        background: rgba(11, 61, 92, 0.08);
        color: #0b3d5c;
        transform: translateX(-2px);
    }
    .judul-halaman h3 {
        margin: 0;
        font-weight: 700;
        color: #0b3d5c;
        font-size: 20px;
    }

    /* ── List Notifikasi ────────────────────────────────────── */
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 28px;
    }

    .notif-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: #ffffff;
        border-radius: 18px;
        padding: 16px 18px;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef0f2;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        position: relative;
    }

    .notif-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(3, 59, 89, 0.09);
        color: inherit;
    }

    .notif-icon-circle {
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .notif-body {
        flex: 1;
        min-width: 0;
    }

    .notif-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
    }

    .notif-heading {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        line-height: 1.3;
    }

    .notif-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .notif-time {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #3b82f6;
        display: inline-block;
        flex-shrink: 0;
    }

    .notif-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
        margin: 0 0 10px;
        word-break: break-word;
    }

    .notif-desc strong {
        color: #0f172a;
        font-weight: 600;
    }

    .notif-pill {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
        letter-spacing: 0.3px;
    }

    /* ── Empty State ────────────────────────────────────────── */
    .notif-empty {
        background: #ffffff;
        border-radius: 20px;
        padding: 48px 24px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        border: 1px solid #eef0f2;
        margin: 12px 0 28px;
    }

    .empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 16px;
    }

    .notif-empty h3 {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .notif-empty p {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.5;
        max-width: 320px;
        margin: 0 auto 22px;
    }

    .btn-order {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #0b3d5c;
        color: #ffffff;
        text-decoration: none;
        padding: 10px 22px;
        border-radius: 999px;
        font-size: 13.5px;
        font-weight: 600;
        transition: background 0.15s, transform 0.15s;
    }

    .btn-order:hover {
        background: #08293e;
        color: #ffffff;
        transform: translateY(-1px);
    }
</style>

<div class="judul-halaman">
    <a href="<?= base_url('dashboard') ?>" class="kembali" title="Kembali ke Beranda" aria-label="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h3>Notifikasi</h3>
</div>

<?php if (!empty($daftarNotifikasi)): ?>
    <div class="notif-list">
        <?php foreach ($daftarNotifikasi as $index => $notif): ?>
            <a href="<?= $notif['target_url'] ?>" class="notif-card" title="Klik untuk melihat detail pesanan">
                <div class="notif-icon-circle" style="background-color: <?= $notif['icon_bg'] ?>; color: <?= $notif['icon_color'] ?>;">
                    <i class="bi <?= $notif['icon'] ?>"></i>
                </div>
                <div class="notif-body">
                    <div class="notif-top">
                        <h2 class="notif-heading"><?= htmlspecialchars($notif['judul'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <div class="notif-meta">
                            <span class="notif-time"><?= htmlspecialchars($notif['waktu_label'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php if ($index === 0): ?>
                                <span class="unread-dot" title="Status Terbaru"></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="notif-desc"><?= $notif['pesan'] ?></p>
                    <?php if (!empty($notif['badge'])): ?>
                        <span class="notif-pill" style="background-color: <?= $notif['badge_bg'] ?>; color: <?= $notif['badge_color'] ?>;">
                            <?= htmlspecialchars($notif['badge'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="notif-empty">
        <div class="empty-icon">
            <i class="bi bi-bell-slash"></i>
        </div>
        <h3>Belum Ada Notifikasi</h3>
        <p>Notifikasi status dan perkembangan cucian Anda akan muncul secara otomatis di sini begitu pesanan dibuat.</p>
        <a href="<?= base_url('pesanan/pilih-layanan') ?>" class="btn-order">
            <i class="bi bi-clipboard-plus-fill me-2"></i>Buat Pesanan Baru
        </a>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
