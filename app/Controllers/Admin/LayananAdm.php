<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LayananModel;

class LayananAdm extends BaseController
{
    protected LayananModel $layananModel;

    public function __construct()
    {
        $this->layananModel = new LayananModel();
    }

    /**
     * Tampilkan daftar layanan laundry untuk admin
     */
    public function index()
    {
        $status = (string) ($this->request->getGet('status') ?? 'semua');
        if (! in_array($status, ['semua', 'aktif', 'nonaktif'], true)) {
            $status = 'semua';
        }

        $q = trim((string) ($this->request->getGet('q') ?? ''));

        $daftarLayanan = $this->layananModel->semuaLayanan($status, $q);
        $ringkasan     = $this->layananModel->hitungStatus();

        // Cek keterkaitan dengan tabel pesanan
        foreach ($daftarLayanan as &$ly) {
            $ly['punya_pesanan'] = $this->layananModel->punyaPesanan((int) $ly['id_layanan']);
        }
        unset($ly);

        return view('admin/layanan_adm', [
            'aktif'         => 'layanan',
            'daftarLayanan' => $daftarLayanan,
            'statusFilter'  => $status,
            'keyword'       => $q,
            'ringkasan'     => $ringkasan,
        ]);
    }

    /**
     * Tambah atau Ubah data layanan laundry
     */
    public function simpan()
    {
        $idLayanan = (int) ($this->request->getPost('id_layanan') ?? 0);
        $isEdit    = $idLayanan > 0;

        $rules = [
            'nama_layanan'  => 'required|min_length[3]|max_length[100]',
            'jenis_layanan' => 'required|in_list[kiloan,satuan]',
            'harga_per_kg'  => 'required|numeric|greater_than_equal_to[0]',
            'harga_flat'    => 'required|numeric|greater_than_equal_to[0]',
            'estimasi_hari' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[30]',
            'ikon'          => 'required|max_length[50]',
        ];

        $messages = [
            'nama_layanan'  => [
                'required'   => 'Nama layanan wajib diisi.',
                'min_length' => 'Nama layanan minimal 3 karakter.',
                'max_length' => 'Nama layanan maksimal 100 karakter.',
            ],
            'jenis_layanan' => [
                'required' => 'Jenis layanan wajib dipilih (Kiloan / Satuan).',
                'in_list'  => 'Jenis layanan tidak valid.',
            ],
            'harga_per_kg'  => [
                'required'              => 'Harga per Kg wajib diisi (minimal 0).',
                'numeric'               => 'Harga per Kg harus berupa angka.',
                'greater_than_equal_to' => 'Harga per Kg tidak boleh bernilai negatif.',
            ],
            'harga_flat'    => [
                'required'              => 'Harga flat wajib diisi (minimal 0).',
                'numeric'               => 'Harga flat harus berupa angka.',
                'greater_than_equal_to' => 'Harga flat tidak boleh bernilai negatif.',
            ],
            'estimasi_hari' => [
                'required'              => 'Estimasi hari pengerjaan wajib diisi.',
                'integer'               => 'Estimasi hari harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Estimasi hari minimal 1 hari.',
                'less_than_equal_to'    => 'Estimasi hari maksimal 30 hari.',
            ],
            'ikon'          => [
                'required'   => 'Kode icon Bootstrap wajib diisi.',
                'max_length' => 'Kode icon maksimal 50 karakter.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Sanitasi kode icon agar seragam (format bi-nama-icon)
        $ikon = trim((string) $this->request->getPost('ikon'));
        $ikon = preg_replace('/^bi\s+/', '', $ikon);
        if (! str_starts_with($ikon, 'bi-')) {
            $ikon = 'bi-' . $ikon;
        }

        $namaLayanan  = trim((string) $this->request->getPost('nama_layanan'));
        $jenisLayanan = (string) $this->request->getPost('jenis_layanan');
        $hargaPerKg   = (float) $this->request->getPost('harga_per_kg');
        $hargaFlat    = (float) $this->request->getPost('harga_flat');
        $estimasiHari = (int) $this->request->getPost('estimasi_hari');
        $deskripsi    = trim((string) ($this->request->getPost('deskripsi') ?? ''));
        $isActive     = $this->request->getPost('is_active') !== null ? (int) $this->request->getPost('is_active') : 1;

        $dataSimpan = [
            'nama_layanan'  => $namaLayanan,
            'jenis_layanan' => $jenisLayanan,
            'harga_per_kg'  => $hargaPerKg,
            'harga_flat'    => $hargaFlat,
            'estimasi_hari' => $estimasiHari,
            'deskripsi'     => $deskripsi,
            'ikon'          => $ikon,
            'is_active'     => $isActive,
        ];

        try {
            if ($isEdit) {
                $target = $this->layananModel->find($idLayanan);
                if (! $target) {
                    return redirect()->to(base_url('admin/kelola-layanan'))
                        ->with('errors', ['Data layanan yang ingin diedit tidak ditemukan.']);
                }

                $this->layananModel->update($idLayanan, $dataSimpan);
                return redirect()->to(base_url('admin/kelola-layanan'))
                    ->with('success', 'Data layanan "' . $namaLayanan . '" berhasil diperbarui.');
            } else {
                $this->layananModel->insert($dataSimpan);
                return redirect()->to(base_url('admin/kelola-layanan'))
                    ->with('success', 'Layanan baru "' . $namaLayanan . '" berhasil ditambahkan.');
            }
        } catch (\Throwable $th) {
            log_message('error', 'Gagal simpan data layanan: ' . $th->getMessage());
            return redirect()->back()->withInput()->with('errors', ['Terjadi kesalahan saat menyimpan data: ' . $th->getMessage()]);
        }
    }

    /**
     * Aktifkan atau Nonaktifkan Layanan
     */
    public function toggleStatus(int $id)
    {
        $layanan = $this->layananModel->find($id);
        if (! $layanan) {
            return redirect()->to(base_url('admin/kelola-layanan'))
                ->with('errors', ['Data layanan tidak ditemukan.']);
        }

        $statusBaru = (int) ($layanan['is_active'] ?? 1) === 1 ? 0 : 1;
        $this->layananModel->update($id, ['is_active' => $statusBaru]);

        if ($statusBaru === 0) {
            $pesan = 'Layanan "' . $layanan['nama_layanan'] . '" berhasil dinonaktifkan. Layanan ini tidak akan tampil pada pilihan pelanggan, namun data pesanan lama tetap aman tersimpan.';
        } else {
            $pesan = 'Layanan "' . $layanan['nama_layanan'] . '" berhasil diaktifkan kembali dan kini dapat dipilih oleh pelanggan.';
        }

        return redirect()->to(base_url('admin/kelola-layanan'))->with('success', $pesan);
    }

    /**
     * Hapus Layanan
     * Jika layanan memiliki relasi riwayat pesanan, status dinonaktifkan demi integritas database.
     * Jika belum pernah digunakan pada pesanan, layanan dapat dihapus permanen.
     */
    public function hapus(int $id)
    {
        $layanan = $this->layananModel->find($id);
        if (! $layanan) {
            return redirect()->to(base_url('admin/kelola-layanan'))
                ->with('errors', ['Data layanan tidak ditemukan.']);
        }

        $namaLayanan = (string) $layanan['nama_layanan'];

        // Cek apakah ada pesanan yang menggunakan layanan ini
        if ($this->layananModel->punyaPesanan($id)) {
            $this->layananModel->update($id, ['is_active' => 0]);
            return redirect()->to(base_url('admin/kelola-layanan'))
                ->with('success', 'Layanan "' . $namaLayanan . '" memiliki riwayat pesanan pelanggan. Demi integritas riwayat pesanan, layanan otomatis dinonaktifkan alih-alih dihapus permanen.');
        }

        try {
            $this->layananModel->delete($id);
            return redirect()->to(base_url('admin/kelola-layanan'))
                ->with('success', 'Layanan "' . $namaLayanan . '" berhasil dihapus permanen dari sistem.');
        } catch (\Throwable $th) {
            log_message('error', 'Gagal hapus layanan: ' . $th->getMessage());
            return redirect()->to(base_url('admin/kelola-layanan'))
                ->with('errors', ['Gagal menghapus layanan: ' . $th->getMessage()]);
        }
    }
}
