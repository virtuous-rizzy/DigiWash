<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiModel extends Model
{
    protected $table         = 'distribusi';
    protected $primaryKey    = 'id_distribusi';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id_pesanan', 'id_kurir', 'jenis_kendaraan', 'biaya', 'status_antar',
    ];

    /** Ambil info distribusi dengan relasi kurir dan pesanan */
    public function cariByPesanan(int $idPesanan): ?array
    {
        return $this->db->table('distribusi d')
            ->select('d.*, k.nama as nama_kurir, k.no_hp as no_hp_kurir')
            ->join('kurir k', 'k.id_kurir = d.id_kurir', 'left')
            ->where('d.id_pesanan', $idPesanan)
            ->get()
            ->getRowArray();
    }

    /**
     * Ambil daftar tugas kurir lengkap dengan data pesanan dan pelanggan riil
     */
    public function tugasKurir(int $idKurir, string $tab = 'semua'): array
    {
        $builder = $this->db->table('pesanan p')
            ->select([
                'COALESCE(d.id_distribusi, 0) AS id_distribusi',
                'p.id_pesanan',
                'COALESCE(d.id_kurir, ' . $idKurir . ') AS id_kurir',
                'COALESCE(d.status_antar, "menunggu_kurir") AS status_antar',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'p.tanggal',
                'p.berat',
                'pl.nama AS nama_pelanggan',
                'pl.no_hp AS no_hp_pelanggan',
                'l.nama_layanan',
                'pb.status_bayar',
            ])
            ->join('distribusi d',  'd.id_pesanan = p.id_pesanan', 'left')
            ->join('pelanggan pl',  'pl.id_pelanggan = p.id_pelanggan', 'left')
            ->join('layanan l',     'l.id_layanan = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan = p.id_pesanan', 'left')
            // KURIR HANYA MENERIMA TUGAS JIKA PEMBAYARAN TELAH LUNAS & DIVERIFIKASI ADMIN
            ->where('pb.status_bayar', 'lunas')
            ->whereNotIn('p.status_pesanan', ['menunggu', 'dibatalkan'])
            ->groupStart()
                ->where('d.id_kurir', $idKurir)
                ->orWhere('d.id_kurir IS NULL')
            ->groupEnd();

        $daftar = $builder->orderBy('p.id_pesanan', 'DESC')->get()->getResultArray();

        // Tentukan tipe tugas (penjemputan atau pengantaran)
        $tugas = [];
        foreach ($daftar as $d) {
            $stPesanan = (string) ($d['status_pesanan'] ?? 'dikonfirmasi');
            $tipe = in_array($stPesanan, ['dikonfirmasi', 'dijemput'], true) ? 'penjemputan' : 'pengantaran';

            if ($tab === 'penjemputan' && $tipe !== 'penjemputan') {
                continue;
            }
            if ($tab === 'pengantaran' && $tipe !== 'pengantaran') {
                continue;
            }

            $d['tipe'] = $tipe;
            $tugas[] = $d;
        }

        return $tugas;
    }

    /**
     * Hitung ringkasan tugas aktif dan selesai milik kurir
     */
    public function hitungRingkasanKurir(int $idKurir): array
    {
        $semua = $this->db->table('pesanan p')
            ->select('COALESCE(d.status_antar, "menunggu_kurir") AS status_antar, p.status_pesanan')
            ->join('distribusi d',  'd.id_pesanan = p.id_pesanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan = p.id_pesanan', 'left')
            ->where('pb.status_bayar', 'lunas')
            ->whereNotIn('p.status_pesanan', ['menunggu', 'dibatalkan'])
            ->groupStart()
                ->where('d.id_kurir', $idKurir)
                ->orWhere('d.id_kurir IS NULL')
            ->groupEnd()
            ->get()->getResultArray();

        $aktif   = 0;
        $selesai = 0;
        $jemput  = 0;
        $antar   = 0;

        foreach ($semua as $s) {
            if (($s['status_pesanan'] ?? '') === 'selesai' || ($s['status_antar'] ?? '') === 'terkirim') {
                $selesai++;
            } else {
                $aktif++;
                if (in_array($s['status_pesanan'] ?? '', ['dikonfirmasi', 'dijemput'], true)) {
                    $jemput++;
                } else {
                    $antar++;
                }
            }
        }

        return [
            'aktif'   => $aktif,
            'selesai' => $selesai,
            'jemput'  => $jemput,
            'antar'   => $antar,
        ];
    }

    /**
     * Ambil detail satu tugas untuk kurir
     */
    public function detailTugas(int $idPesanan, ?int $idKurir = null): ?array
    {
        $builder = $this->db->table('pesanan p')
            ->select([
                'p.id_pesanan',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'p.catatan',
                'p.tanggal',
                'p.berat',
                'pl.nama AS nama_pelanggan',
                'pl.no_hp AS no_hp_pelanggan',
                'l.nama_layanan',
                'l.jenis_layanan',
                'l.estimasi_hari',
                'pb.status_bayar',
                'pb.metode',
                'pb.total_bayar',
                'd.id_distribusi',
                'd.id_kurir',
                'd.status_antar',
            ])
            ->join('pelanggan pl',  'pl.id_pelanggan = p.id_pelanggan', 'left')
            ->join('layanan l',     'l.id_layanan    = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan   = p.id_pesanan', 'left')
            ->join('distribusi d',  'd.id_pesanan    = p.id_pesanan', 'left')
            ->where('p.id_pesanan', $idPesanan);

        if ($idKurir !== null) {
            $builder->groupStart()
                ->where('d.id_kurir', $idKurir)
                ->orWhere('d.id_kurir IS NULL')
                ->groupEnd();
        }

        $row = $builder->get()->getRowArray();
        return is_array($row) ? $row : null;
    }

    /**
     * Ambil riwayat tugas pengantaran & penjemputan yang telah selesai dijalankan kurir
     */
    public function riwayatKurir(int $idKurir, string $tab = 'semua', string $q = ''): array
    {
        $builder = $this->db->table('pesanan p')
            ->select([
                'COALESCE(d.id_distribusi, 0) AS id_distribusi',
                'p.id_pesanan',
                'COALESCE(d.id_kurir, ' . $idKurir . ') AS id_kurir',
                'COALESCE(d.status_antar, "terkirim") AS status_antar',
                'p.status_pesanan',
                'p.alamat_pengambilan',
                'p.alamat_pengantaran',
                'p.tanggal',
                'COALESCE(d.updated_at, p.updated_at, p.tanggal) AS waktu_selesai',
                'p.berat',
                'pl.nama AS nama_pelanggan',
                'pl.no_hp AS no_hp_pelanggan',
                'l.nama_layanan',
                'l.jenis_layanan',
                'pb.status_bayar',
                'pb.metode',
                'pb.total_bayar',
            ])
            ->join('distribusi d',  'd.id_pesanan = p.id_pesanan', 'left')
            ->join('pelanggan pl',  'pl.id_pelanggan = p.id_pelanggan', 'left')
            ->join('layanan l',     'l.id_layanan = p.id_layanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan = p.id_pesanan', 'left')
            ->where('pb.status_bayar', 'lunas')
            ->groupStart()
                ->where('d.id_kurir', $idKurir)
                ->orWhere('d.id_kurir IS NULL')
            ->groupEnd();

        // Filter tab
        if ($tab === 'pengantaran') {
            $builder->where('p.status_pesanan', 'selesai');
        } elseif ($tab === 'penjemputan') {
            $builder->whereNotIn('p.status_pesanan', ['menunggu', 'dikonfirmasi', 'dibatalkan']);
        } else {
            // Semua riwayat (minimal telah dijemput / selesai)
            $builder->whereNotIn('p.status_pesanan', ['menunggu', 'dikonfirmasi', 'dibatalkan']);
        }

        if ($q !== '') {
            $builder->groupStart()
                ->like('pl.nama', $q)
                ->orLike('p.id_pesanan', $q)
                ->orLike('p.alamat_pengantaran', $q)
                ->orLike('p.alamat_pengambilan', $q)
                ->orLike('l.nama_layanan', $q)
                ->groupEnd();
        }

        $daftar = $builder->orderBy('p.id_pesanan', 'DESC')->get()->getResultArray();

        $riwayat = [];
        foreach ($daftar as $d) {
            $st = (string) ($d['status_pesanan'] ?? 'selesai');
            if ($tab === 'penjemputan') {
                $d['tipe'] = 'penjemputan';
            } elseif ($tab === 'pengantaran') {
                $d['tipe'] = 'pengantaran';
            } else {
                $d['tipe'] = ($st === 'selesai') ? 'pengantaran' : 'penjemputan';
            }
            $riwayat[] = $d;
        }

        return $riwayat;
    }

    /**
     * Hitung ringkasan riwayat tugas milik kurir
     */
    public function hitungRingkasanRiwayat(int $idKurir): array
    {
        $db = $this->db;

        // Pengantaran Selesai
        $antarSelesai = (int) $db->table('pesanan p')
            ->join('distribusi d',  'd.id_pesanan = p.id_pesanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan = p.id_pesanan', 'left')
            ->where('pb.status_bayar', 'lunas')
            ->where('p.status_pesanan', 'selesai')
            ->groupStart()
                ->where('d.id_kurir', $idKurir)
                ->orWhere('d.id_kurir IS NULL')
            ->groupEnd()
            ->countAllResults();

        // Penjemputan Selesai
        $jemputSelesai = (int) $db->table('pesanan p')
            ->join('distribusi d',  'd.id_pesanan = p.id_pesanan', 'left')
            ->join('pembayaran pb', 'pb.id_pesanan = p.id_pesanan', 'left')
            ->where('pb.status_bayar', 'lunas')
            ->whereNotIn('p.status_pesanan', ['menunggu', 'dikonfirmasi', 'dibatalkan'])
            ->groupStart()
                ->where('d.id_kurir', $idKurir)
                ->orWhere('d.id_kurir IS NULL')
            ->groupEnd()
            ->countAllResults();

        return [
            'total'       => $antarSelesai,
            'pengantaran' => $antarSelesai,
            'penjemputan' => $jemputSelesai,
        ];
    }
}

