<?php

namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_pelanggan', 'id_layanan', 'id_admin',
        'tanggal', 'berat', 'alamat_pengambilan', 'alamat_pengantaran',
        'status_pesanan', 'catatan', 'detail_satuan',
    ];

    /**
     * Ambil semua pesanan milik pelanggan tertentu
     * beserta nama layanan dan status pembayaran.
     */
    public function milikPelanggan(int $idPelanggan): array
    {
        return $this->db->table('pesanan p')
            ->select([
                'p.id_pesanan',
                'p.tanggal',
                'p.berat',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'l.nama_layanan',
                'l.harga_per_kg',
                'l.harga_flat',
                'l.jenis_layanan',
                'pb.status_bayar',
<<<<<<< HEAD
=======
                'pb.bukti_bayar',
>>>>>>> origin/digiwash-semoga-final
                'pb.metode',
                // Hitung total: prioritaskan total_bayar di tabel pembayaran, jika tidak ada hitung dari harga layanan
                'IF(pb.total_bayar IS NOT NULL AND pb.total_bayar > 0, pb.total_bayar, IF(l.harga_flat > 0, l.harga_flat, l.harga_per_kg * p.berat)) AS total_harga',
            ])
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->where('p.id_pelanggan', $idPelanggan)
            ->orderBy('p.id_pesanan', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil pesanan berdasarkan status_pesanan (filter tab).
     * $status bisa berupa string tunggal atau array.
     */
    public function milikPelangganByStatus(int $idPelanggan, $status): array
    {
        $builder = $this->db->table('pesanan p')
            ->select([
                'p.id_pesanan',
                'p.tanggal',
                'p.berat',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'l.nama_layanan',
                'l.harga_per_kg',
                'l.harga_flat',
                'l.jenis_layanan',
                'pb.status_bayar',
<<<<<<< HEAD
=======
                'pb.bukti_bayar',
>>>>>>> origin/digiwash-semoga-final
                'pb.metode',
                'IF(pb.total_bayar IS NOT NULL AND pb.total_bayar > 0, pb.total_bayar, IF(l.harga_flat > 0, l.harga_flat, l.harga_per_kg * p.berat)) AS total_harga',
            ])
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->where('p.id_pelanggan', $idPelanggan);

        if (is_array($status)) {
            $builder->whereIn('p.status_pesanan', $status);
        } else {
            $builder->where('p.status_pesanan', $status);
        }

        return $builder->orderBy('p.id_pesanan', 'DESC')->get()->getResultArray();
    }

    /** Satu pesanan lengkap berdasarkan id (dan pastikan milik pelanggan). */
    public function detailPesanan(int $idPesanan, int $idPelanggan): ?array
    {
        $result = $this->db->table('pesanan p')
            ->select([
                'p.*',
                'l.nama_layanan', 'l.harga_per_kg', 'l.harga_flat', 'l.jenis_layanan', 'l.estimasi_hari',
<<<<<<< HEAD
                'pb.id_pembayaran', 'pb.metode', 'pb.status_bayar',
=======
                'pb.id_pembayaran', 'pb.metode', 'pb.status_bayar', 'pb.bukti_bayar',
>>>>>>> origin/digiwash-semoga-final
                'IF(pb.total_bayar IS NOT NULL AND pb.total_bayar > 0, pb.total_bayar, IF(l.harga_flat > 0, l.harga_flat, l.harga_per_kg * p.berat)) AS total_harga',
            ])
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->where('p.id_pesanan',   $idPesanan)
            ->where('p.id_pelanggan', $idPelanggan)
            ->get()
            ->getRowArray();

        return is_array($result) ? $result : null;
    }

    /** Hitung pesanan aktif (bukan selesai/dibatalkan) milik pelanggan. */
    public function jumlahAktif(int $idPelanggan): int
    {
        return (int) $this->db->table('pesanan')
            ->where('id_pelanggan', $idPelanggan)
            ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
            ->countAllResults();
    }
<<<<<<< HEAD
}
=======

    /**
     * Ambil semua pesanan untuk panel Admin (Mengupdate Status Laundry)
     * lengkap dengan data pelanggan, layanan, pembayaran, dan kurir (jika ada).
     */
    public function semuaPesananAdmin(?string $tab = 'semua', string $q = ''): array
    {
        $builder = $this->db->table('pesanan p')
            ->select([
                'p.id_pesanan',
                'p.id_pelanggan',
                'p.id_layanan',
                'p.tanggal',
                'p.berat',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'p.catatan',
                'p.detail_satuan',
                'p.created_at',
                'p.updated_at',
                'pl.nama AS nama_pelanggan',
                'pl.no_hp AS no_hp_pelanggan',
                'pl.foto AS foto_pelanggan',
                'l.nama_layanan',
                'l.jenis_layanan',
                'l.harga_per_kg',
                'l.harga_flat',
                'l.estimasi_hari',
                'p.id_admin',
                'pb.status_bayar',
                'pb.metode',
                'pb.total_bayar',
                'pb.id_admin AS id_admin_bayar',
                'd.id_kurir',
                'k.nama AS nama_kurir',
                'k.no_hp AS no_hp_kurir',
                'k.foto AS foto_kurir',
            ])
            ->join('pelanggan pl',  'pl.id_pelanggan = p.id_pelanggan', 'left')
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->join('distribusi d',  'd.id_pesanan    = p.id_pesanan', 'left')
            ->join('kurir k',       'k.id_kurir      = d.id_kurir', 'left');

        // Filter tab
        if ($tab === 'diproses') {
            $builder->whereIn('p.status_pesanan', ['menunggu', 'dikonfirmasi', 'dijemput', 'dicuci', 'disetrika'])
                ->groupStart()
                    ->where('pb.status_bayar !=', 'gagal')
                    ->orWhere('pb.status_bayar IS NULL')
                ->groupEnd();
        } elseif ($tab === 'diantar') {
            $builder->where('p.status_pesanan', 'diantar');
        } elseif ($tab === 'selesai') {
            $builder->where('p.status_pesanan', 'selesai');
        } elseif ($tab === 'dibatalkan') {
            $builder->groupStart()
                ->like('p.catatan', 'dibatalkan')
                ->orGroupStart()
                    ->where('p.status_pesanan', 'dibatalkan')
                    ->where('p.id_admin IS NULL')
                    ->groupStart()
                        ->where('pb.id_admin IS NULL')
                        ->orWhere('pb.id_admin', 0)
                    ->groupEnd()
                ->groupEnd()
            ->groupEnd()
            ->groupStart()
                ->notLike('p.catatan', 'ditolak')
                ->orWhere('p.catatan IS NULL')
            ->groupEnd();
        } elseif ($tab === 'ditolak') {
            $builder->groupStart()
                ->like('p.catatan', 'ditolak')
                ->orGroupStart()
                    ->where('pb.status_bayar', 'gagal')
                    ->groupStart()
                        ->where('p.id_admin IS NOT NULL')
                        ->orWhere('pb.id_admin IS NOT NULL')
                    ->groupEnd()
                    ->groupStart()
                        ->notLike('p.catatan', 'dibatalkan oleh pelanggan')
                        ->orWhere('p.catatan IS NULL')
                    ->groupEnd()
                ->groupEnd()
            ->groupEnd();
        }

        // Search query
        if ($q !== '') {
            $builder->groupStart()
                ->like('pl.nama', $q)
                ->orLike('p.id_pesanan', $q)
                ->orLike('l.nama_layanan', $q)
                ->groupEnd();
        }

        return $builder->orderBy('p.id_pesanan', 'DESC')->get()->getResultArray();
    }

    /**
     * Hitung jumlah pesanan untuk tab di halaman Admin
     */
    public function hitungStatusAdmin(): array
    {
        $db = $this->db;
        $totalSemua = (int) $db->table('pesanan')->countAllResults();
        $totalDiproses = (int) $db->table('pesanan p')
            ->join('pembayaran pb', 'pb.id_pesanan = p.id_pesanan', 'left')
            ->whereIn('p.status_pesanan', ['menunggu', 'dikonfirmasi', 'dijemput', 'dicuci', 'disetrika'])
            ->groupStart()
                ->where('pb.status_bayar !=', 'gagal')
                ->orWhere('pb.status_bayar IS NULL')
            ->groupEnd()
            ->countAllResults();
        $totalDiantar = (int) $db->table('pesanan')
            ->where('status_pesanan', 'diantar')
            ->countAllResults();
        $totalSelesai = (int) $db->table('pesanan')
            ->where('status_pesanan', 'selesai')
            ->countAllResults();
        $totalDibatalkan = (int) $db->table('pesanan p')
            ->join('pembayaran pb', 'pb.id_pesanan = p.id_pesanan', 'left')
            ->groupStart()
                ->like('p.catatan', 'dibatalkan')
                ->orGroupStart()
                    ->where('p.status_pesanan', 'dibatalkan')
                    ->where('p.id_admin IS NULL')
                    ->groupStart()
                        ->where('pb.id_admin IS NULL')
                        ->orWhere('pb.id_admin', 0)
                    ->groupEnd()
                ->groupEnd()
            ->groupEnd()
            ->groupStart()
                ->notLike('p.catatan', 'ditolak')
                ->orWhere('p.catatan IS NULL')
            ->groupEnd()
            ->countAllResults();
        $totalDitolak = (int) $db->table('pesanan p')
            ->join('pembayaran pb', 'pb.id_pesanan = p.id_pesanan', 'left')
            ->groupStart()
                ->like('p.catatan', 'ditolak')
                ->orGroupStart()
                    ->where('pb.status_bayar', 'gagal')
                    ->groupStart()
                        ->where('p.id_admin IS NOT NULL')
                        ->orWhere('pb.id_admin IS NOT NULL')
                    ->groupEnd()
                    ->groupStart()
                        ->notLike('p.catatan', 'dibatalkan oleh pelanggan')
                        ->orWhere('p.catatan IS NULL')
                    ->groupEnd()
                ->groupEnd()
            ->groupEnd()
            ->countAllResults();

        return [
            'semua'      => $totalSemua,
            'diproses'   => $totalDiproses,
            'diantar'    => $totalDiantar,
            'selesai'    => $totalSelesai,
            'dibatalkan' => $totalDibatalkan,
            'ditolak'    => $totalDitolak,
        ];
    }

    /**
     * Detail lengkap satu pesanan untuk halaman Tracking
     */
    public function detailTracking(int $idPesanan, ?int $idPelanggan = null): ?array
    {
        $builder = $this->db->table('pesanan p')
            ->select([
                'p.*',
                'pl.nama AS nama_pelanggan',
                'pl.no_hp AS no_hp_pelanggan',
                'pl.foto AS foto_pelanggan',
                'l.nama_layanan',
                'l.jenis_layanan',
                'l.harga_per_kg',
                'l.harga_flat',
                'l.estimasi_hari',
                'pb.status_bayar',
                'pb.bukti_bayar',
                'pb.metode',
                'pb.total_bayar',
                'd.id_kurir',
                'd.status_antar',
                'k.nama AS nama_kurir',
                'k.no_hp AS no_hp_kurir',
                'k.foto AS foto_kurir',
            ])
            ->join('pelanggan pl',  'pl.id_pelanggan = p.id_pelanggan', 'left')
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->join('distribusi d',  'd.id_pesanan    = p.id_pesanan', 'left')
            ->join('kurir k',       'k.id_kurir      = d.id_kurir', 'left')
            ->where('p.id_pesanan', $idPesanan);

        if ($idPelanggan !== null) {
            $builder->where('p.id_pelanggan', $idPelanggan);
        }

        $row = $builder->get()->getRowArray();
        return is_array($row) ? $row : null;
    }

    /**
     * Ambil sejumlah pesanan terbaru untuk dashboard admin
     */
    public function pesananTerbaruAdmin(int $limit = 3): array
    {
        return $this->db->table('pesanan p')
            ->select([
                'p.id_pesanan',
                'p.id_pelanggan',
                'p.id_layanan',
                'p.tanggal',
                'p.berat',
                'p.status_pesanan',
                'p.catatan',
                'p.detail_satuan',
                'p.created_at',
                'pl.nama AS nama_pelanggan',
                'pl.foto AS foto_pelanggan',
                'l.nama_layanan',
                'l.jenis_layanan',
                'l.harga_per_kg',
                'l.harga_flat',
                'pb.status_bayar',
                'pb.total_bayar',
                'IF(pb.total_bayar IS NOT NULL AND pb.total_bayar > 0, pb.total_bayar, IF(l.harga_flat > 0, l.harga_flat, l.harga_per_kg * p.berat)) AS total_harga',
            ])
            ->join('pelanggan pl',  'pl.id_pelanggan = p.id_pelanggan', 'left')
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->orderBy('p.id_pesanan', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}

>>>>>>> origin/digiwash-semoga-final
