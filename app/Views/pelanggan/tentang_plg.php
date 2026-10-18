<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<div class="judul-halaman">
    <a href="<?= base_url('profil') ?>" class="kembali">&larr;</a>
    <h3>Tentang Aplikasi</h3>
</div>

<div class="tentang-logo">🧺 DIGIWASH <small>v1.0.0</small></div>

<div class="panel">
    <h4>Tentang Aplikasi</h4>
    <p class="tentang-teks">DigiWash adalah platform layanan laundry modern yang memudahkan pelanggan melakukan pemesanan, pickup, tracking proses pencucian, pembayaran, dan pengantaran dalam satu aplikasi.</p>
</div>

<div class="daftar-baris" style="margin-top:14px">
    <a class="baris" href="#" data-soon><span class="ikon">📄</span><span class="teks">Syarat &amp; Ketentuan</span><span class="panah">&rsaquo;</span></a>
    <a class="baris" href="#" data-soon><span class="ikon">🛡️</span><span class="teks">Kebijakan Privasi</span><span class="panah">&rsaquo;</span></a>
    <a class="baris" href="#" data-soon><span class="ikon">🖥️</span><span class="teks">Lisensi</span><span class="panah">&rsaquo;</span></a>
    <a class="baris" href="#" data-soon><span class="ikon">&lt;/&gt;</span><span class="teks">Open Source Libraries</span><span class="panah">&rsaquo;</span></a>
</div>

<p class="hak-cipta">&copy; 2026 DigiWash Laundry. All Rights Reserved.</p>
<?= $this->endSection() ?>