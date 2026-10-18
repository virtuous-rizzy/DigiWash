<?php
// Cek apakah kolom alamat_alternatif ada di tabel pelanggan
$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'digiwash_db_ziya';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$res = $conn->query("DESCRIBE pelanggan");
echo "<pre>";
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . " | " . $row['Type'] . " | " . $row['Null'] . " | " . $row['Default'] . "\n";
}
echo "</pre>";

// Cek apakah kolom ada
$res2 = $conn->query("SHOW COLUMNS FROM pelanggan LIKE 'alamat_alternatif'");
if ($res2->num_rows > 0) {
    echo "<b style='color:green'>OK: kolom alamat_alternatif ADA</b>";
} else {
    echo "<b style='color:red'>PERLU DITAMBAH: kolom alamat_alternatif BELUM ADA</b><br>";
    // Tambahkan kolom
    $conn->query("ALTER TABLE pelanggan ADD COLUMN alamat_alternatif TEXT NULL AFTER alamat");
    echo "<b style='color:green'>Kolom alamat_alternatif berhasil ditambahkan!</b>";
}
$conn->close();
