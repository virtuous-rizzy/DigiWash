<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateErdPesananTables extends Migration
{
    public function up()
    {
        // 1. Tabel layanan
        $this->forge->addField([
            'id_layanan' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_layanan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'jenis_layanan' => [
                'type'       => 'ENUM',
                'constraint' => ['kiloan', 'satuan'],
                'default'    => 'kiloan',
            ],
            'harga_per_kg' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],
            'harga_flat' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],
            'estimasi_hari' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 3,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ikon' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'bi-basket2-fill',
            ],
        ]);
        $this->forge->addKey('id_layanan', true);
        $this->forge->createTable('layanan', true);

        // Seed default layanan
        $db = \Config\Database::connect();
        $builder = $db->table('layanan');
        if ($builder->countAllResults() === 0) {
            $builder->insertBatch([
                [
                    'id_layanan'    => 1,
                    'nama_layanan'  => 'Laundry Reguler',
                    'jenis_layanan' => 'kiloan',
                    'harga_per_kg'  => 7000.00,
                    'harga_flat'    => 0.00,
                    'estimasi_hari' => 3,
                    'is_active'     => 1,
                    'deskripsi'     => 'Estimasi 3 hari kerja, bersih, wangi & rapi',
                    'ikon'          => 'bi-basket2-fill',
                ],
                [
                    'id_layanan'    => 2,
                    'nama_layanan'  => 'Laundry Express',
                    'jenis_layanan' => 'kiloan',
                    'harga_per_kg'  => 12000.00,
                    'harga_flat'    => 0.00,
                    'estimasi_hari' => 1,
                    'is_active'     => 1,
                    'deskripsi'     => 'Estimasi 1 hari kerja, prioritas kilat',
                    'ikon'          => 'bi-lightning-charge-fill',
                ],
                [
                    'id_layanan'    => 3,
                    'nama_layanan'  => 'Laundry Satuan',
                    'jenis_layanan' => 'satuan',
                    'harga_per_kg'  => 0.00,
                    'harga_flat'    => 5000.00,
                    'estimasi_hari' => 2,
                    'is_active'     => 1,
                    'deskripsi'     => 'Jasa cuci satuan seperti jas, selimut, boneka, sepatu',
                    'ikon'          => 'bi-tag-fill',
                ],
                [
                    'id_layanan'    => 4,
                    'nama_layanan'  => 'Laundry Bed Cover',
                    'jenis_layanan' => 'kiloan',
                    'harga_per_kg'  => 7000.00,
                    'harga_flat'    => 35000.00,
                    'estimasi_hari' => 3,
                    'is_active'     => 1,
                    'deskripsi'     => 'Cuci bed cover, sprei, selimut besar',
                    'ikon'          => 'bi-layers-fill',
                ],
            ]);
        }

        // 2. Tabel pesanan
        $this->forge->addField([
            'id_pesanan' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pelanggan' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'id_layanan' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'id_admin' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'tanggal' => [
                'type' => 'DATETIME',
            ],
            'berat' => [
                'type'       => 'DECIMAL',
                'constraint' => '6,2',
                'default'    => '1.00',
            ],
            'alamat_pengambilan' => [
                'type' => 'TEXT',
            ],
            'alamat_pengantaran' => [
                'type' => 'TEXT',
            ],
            'status_pesanan' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu', 'dikonfirmasi', 'dijemput', 'dicuci', 'disetrika', 'diantar', 'selesai', 'dibatalkan'],
                'default'    => 'menunggu',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'detail_satuan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id_pesanan', true);
        $this->forge->addForeignKey('id_pelanggan', 'pelanggan', 'id_pelanggan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_layanan', 'layanan', 'id_layanan', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('id_admin', 'admin', 'id_admin', 'SET NULL', 'SET NULL');
        $this->forge->createTable('pesanan', true);

        // 3. Tabel pembayaran
        $this->forge->addField([
            'id_pembayaran' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pesanan' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'id_admin' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'metode' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'status_bayar' => [
                'type'       => 'ENUM',
                'constraint' => ['belum_dibayar', 'menunggu_verifikasi', 'lunas', 'gagal'],
                'default'    => 'menunggu_verifikasi',
            ],
            'bukti_bayar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'total_bayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
            ],
            'tanggal_bayar datetime default current_timestamp',
        ]);
        $this->forge->addKey('id_pembayaran', true);
        $this->forge->addForeignKey('id_pesanan', 'pesanan', 'id_pesanan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_admin', 'admin', 'id_admin', 'SET NULL', 'SET NULL');
        $this->forge->createTable('pembayaran', true);

        // 4. Tabel distribusi
        $this->forge->addField([
            'id_distribusi' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pesanan' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'id_kurir' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'jenis_kendaraan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'biaya' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],
            'status_antar' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu_kurir', 'proses_antar', 'terkirim'],
                'default'    => 'menunggu_kurir',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id_distribusi', true);
        $this->forge->addForeignKey('id_pesanan', 'pesanan', 'id_pesanan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_kurir', 'kurir', 'id_kurir', 'SET NULL', 'SET NULL');
        $this->forge->createTable('distribusi', true);

        // 5. Tabel kritik_saran
        $this->forge->addField([
            'id_kritik' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pelanggan' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'pesan' => [
                'type' => 'TEXT',
            ],
            'tanggal datetime default current_timestamp',
        ]);
        $this->forge->addKey('id_kritik', true);
        $this->forge->addForeignKey('id_pelanggan', 'pelanggan', 'id_pelanggan', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kritik_saran', true);
    }

    public function down()
    {
        $this->forge->dropTable('kritik_saran', true);
        $this->forge->dropTable('distribusi', true);
        $this->forge->dropTable('pembayaran', true);
        $this->forge->dropTable('pesanan', true);
        $this->forge->dropTable('layanan', true);
    }
}
