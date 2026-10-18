<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
$topik = [
    ['🛒', 'Cara Pemesanan', 'Panduan lengkap cara melakukan pesanan', 'Buka menu Buat Pesanan, pilih layanan, pilih alamat jemput, lalu konfirmasi. Kurir akan datang menjemput cucianmu.'],
    ['💳', 'Pembayaran', 'Informasi metode pembayaran', 'Pembayaran dilakukan setelah cucian ditimbang dan total harga ditentukan. Status pembayaran bisa dilihat di beranda.'],
    ['🚚', 'Pengiriman', 'Informasi seputar pengiriman pesanan', 'Pantau posisi cucianmu lewat menu Tracking Laundry. Status berubah dari dijemput, dicuci, diantar, sampai selesai.'],
    ['↩️', 'Pengembalian & Refund', 'Kebijakan pengembalian barang', 'Jika ada cucian yang bermasalah, hubungi kami maksimal 1x24 jam setelah cucian diterima.'],
    ['🔐', 'Akun & Keamanan', 'Masalah akun dan keamanan', 'Kata sandi bisa diganti di Profil, lalu Ganti Kata Sandi. Jangan bagikan kata sandimu kepada siapa pun.'],
];

$e = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');

// Ganti dengan nomor WhatsApp admin DigiWash (format 62...)
$nomorWa = '6281200000001';
?>

<div class="judul-halaman">
    <a href="<?= base_url('profil') ?>" class="kembali">&larr;</a>
    <h3>Bantuan</h3>
</div>

<div class="cari">
    <span>🔍</span>
    <input type="search" id="cari" placeholder="Cari bantuan...">
</div>

<p class="subjudul">Topik Bantuan</p>
<div class="topik-list">
    <?php foreach ($topik as [$ikon, $judulTopik, $sub, $isi]): ?>
        <details class="topik" data-cari="<?= $e(strtolower($judulTopik . ' ' . $sub . ' ' . $isi)) ?>">
            <summary>
                <span class="ikon-kotak"><?= $ikon ?></span>
                <span class="teks"><strong><?= $e($judulTopik) ?></strong><small><?= $e($sub) ?></small></span>
                <span class="panah">&rsaquo;</span>
            </summary>
            <p><?= $e($isi) ?></p>
        </details>
    <?php endforeach; ?>
    <p id="kosong" class="kosong-teks" hidden>Topik tidak ditemukan.</p>
</div>

<div class="butuh-bantuan">
    <strong>Butuh Bantuan Lain?</strong>
    <small>Hubungi tim support kami</small>
    <a class="btn btn-dark" href="https://wa.me/<?= $e($nomorWa) ?>" target="_blank" rel="noopener">💬 Hubungi Kami</a>
</div>

<script>
document.getElementById('cari').addEventListener('input', function () {
    var q = this.value.trim().toLowerCase(), ada = false;
    document.querySelectorAll('.topik').forEach(function (el) {
        var cocok = el.getAttribute('data-cari').indexOf(q) !== -1;
        el.hidden = !cocok;
        if (cocok) ada = true;
    });
    document.getElementById('kosong').hidden = ada;
});
</script>
<?= $this->endSection() ?>