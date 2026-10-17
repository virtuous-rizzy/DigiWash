<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PesananAdm extends BaseController
{
    private const STATUS_VALID = [
        'menunggu',
        'dikonfirmasi',
        'dijemput',
        'dicuci',
        'disetrika',
        'diantar',
        'selesai',
        'dibatalkan',
    ];

    /**
     * Halaman Mengupdate Status Laundry
     */
    public function index()
    {
        $tab = (string) ($this->request->getGet('tab') ?? 'semua');
        if (! in_array($tab, ['semua', 'diproses', 'diantar', 'selesai'], true)) {
            $tab = 'semua';
        }

        $q = trim((string) ($this->request->getGet('q') ?? ''));

        $model = new PesananModel();
        $pesanan = [];
        $counts  = [
            'semua'    => 0,
            'diproses' => 0,
            'diantar'  => 0,
            'selesai'  => 0,
        ];

        try {
            $pesanan = $model->semuaPesananAdmin($tab, $q);
            $counts  = $model->hitungStatusAdmin();
        } catch (\Throwable $th) {
            log_message('error', 'Gagal memuat pesanan admin: ' . $th->getMessage());
        }

        // Jika data pesanan di DB masih kosong untuk testing, siapkan dummy seperti di mockup (Akbar)
        if (empty($pesanan) && $q === '' && $tab === 'semua' && $counts['semua'] === 0) {
            $pesanan = [
                [
                    'id_pesanan'         => 2,
                    'id_pelanggan'       => 1,
                    'id_layanan'         => 1,
                    'tanggal'            => date('Y-m-d H:i:s'),
                    'berat'              => 5.00,
                    'status_pesanan'     => 'dikonfirmasi',
                    'alamat_pengambilan' => 'Jl. Melati No. 4',
                    'alamat_pengantaran' => 'Jl. Melati No. 4',
                    'catatan'            => '',
                    'nama_pelanggan'     => 'Akbar',
                    'no_hp_pelanggan'    => '085678901234',
                    'foto_pelanggan'     => null,
                    'nama_layanan'       => 'Laundry Reguler',
                    'jenis_layanan'      => 'kiloan',
                    'estimasi_hari'      => 3,
                    'status_bayar'       => 'lunas',
                    'total_bayar'        => 35000,
                ],
            ];
            $counts = [
                'semua'    => 1,
                'diproses' => 1,
                'diantar'  => 0,
                'selesai'  => 0,
            ];
        }

        return view('admin/pesanan_adm', [
            'pesanan' => $pesanan,
            'tab'     => $tab,
            'q'       => $q,
            'counts'  => $counts,
            'aktif'   => 'pesanan',
        ]);
    }

    /**
     * Update Status Laundry
     */
    public function updateStatus(int $idPesanan)
    {
        $status = (string) $this->request->getPost('status_pesanan');

        if (! in_array($status, self::STATUS_VALID, true)) {
            return redirect()->back()->with('errors', ['Status laundry yang dipilih tidak valid.']);
        }

        $idAdmin = (int) (session()->get('id_admin') ?? session()->get('user_id') ?? 1);
        $model   = new PesananModel();

        try {
            $pesanan = $model->find($idPesanan);
            if (! $pesanan) {
                throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
            }

            $model->update($idPesanan, [
                'status_pesanan' => $status,
                'id_admin'       => $idAdmin,
            ]);

            // Sinkronisasi status pengantaran jika ada data distribusi
            $db = \Config\Database::connect();
            if ($status === 'diantar') {
                $db->table('distribusi')
                    ->where('id_pesanan', $idPesanan)
                    ->update(['status_antar' => 'proses_antar']);
            } elseif ($status === 'selesai') {
                $db->table('distribusi')
                    ->where('id_pesanan', $idPesanan)
                    ->update(['status_antar' => 'terkirim']);
            }

            return redirect()->to(base_url('admin/pesanan'))
                ->with('success', 'Status pesanan PSN-' . str_pad((string)$idPesanan, 4, '0', STR_PAD_LEFT) . ' berhasil diubah menjadi "' . ucwords(str_replace('_', ' ', $status)) . '".');
        } catch (\Throwable $th) {
            log_message('error', 'Gagal update status pesanan: ' . $th->getMessage());
            return redirect()->back()->with('errors', ['Terjadi kesalahan saat memperbarui status.']);
        }
    }
}
