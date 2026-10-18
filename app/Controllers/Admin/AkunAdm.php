<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AkunAdm extends BaseController
{
    private function idAdmin(): int
    {
        return (int) session()->get('user_id');
    }

    private function ambilAdmin(): array
    {
        return \Config\Database::connect()->table('admin')
            ->where('id_admin', $this->idAdmin())->get()->getRowArray() ?? [];
    }

    /** Nomor telepon sudah dipakai akun lain (admin lain, kurir, atau pelanggan)? */
    private function hpDipakai(string $hp): bool
    {
        $db = \Config\Database::connect();

        $adminLain = $db->table('admin')->where('noHP', $hp)
            ->where('id_admin !=', $this->idAdmin())->countAllResults();

        return $adminLain > 0
            || $db->table('kurir')->where('no_hp', $hp)->countAllResults() > 0
            || $db->table('pelanggan')->where('no_hp', $hp)->countAllResults() > 0;
    }

    // ---------- Halaman ----------

    public function index()
    {
        return view('admin/akun_adm', ['admin' => $this->ambilAdmin(), 'aktif' => 'akun']);
    }

    public function data()
    {
        return view('admin/datapribadi_adm', ['admin' => $this->ambilAdmin(), 'aktif' => 'akun']);
    }

    public function ubah()
    {
        return view('admin/ubahdata_adm', ['admin' => $this->ambilAdmin(), 'aktif' => 'akun']);
    }

    public function password()
    {
        return view('admin/katasandi_adm', ['admin' => $this->ambilAdmin(), 'aktif' => 'akun']);
    }

    // ---------- Data pribadi ----------

    public function simpan()
    {
        $rules = [
            'nama'  => 'required|min_length[3]|max_length[100]',
            'no_hp' => 'required|numeric|min_length[10]|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = trim((string) $this->request->getPost('nama'));
        $hp   = trim((string) $this->request->getPost('no_hp'));

        if ($this->hpDipakai($hp)) {
            return redirect()->back()->withInput()
                ->with('errors', ['Nomor telepon sudah dipakai akun lain.']);
        }

        \Config\Database::connect()->table('admin')
            ->where('id_admin', $this->idAdmin())
            ->update(['nama' => $nama, 'noHP' => $hp]);

        session()->set('nama', $nama);

        return redirect()->to('/admin/akun/data')->with('success', 'Data pribadi diperbarui.');
    }

    // ---------- Kata sandi ----------

    public function gantiPassword()
    {
        $rules = [
            'password_lama'  => 'required',
            'password_baru'  => 'required|min_length[6]',
            'password_ulang' => 'required|matches[password_baru]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $admin = $this->ambilAdmin();

        if (! $admin || ! password_verify((string) $this->request->getPost('password_lama'), $admin['password'])) {
            return redirect()->back()->with('errors', ['Kata sandi lama salah.']);
        }

        \Config\Database::connect()->table('admin')
            ->where('id_admin', $this->idAdmin())
            ->update(['password' => password_hash((string) $this->request->getPost('password_baru'), PASSWORD_DEFAULT)]);

        return redirect()->to('/admin/akun')->with('success', 'Kata sandi berhasil diganti.');
    }

    // ---------- Foto profil ----------

    public function foto()
    {
        $rules = [
            'foto' => [
                'label' => 'Foto',
                'rules' => 'uploaded[foto]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/akun')->with('errors', $this->validator->getErrors());
        }

        $db  = \Config\Database::connect();
        $dir = FCPATH . 'uploads/profil';

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $lama = (string) ($this->ambilAdmin()['foto'] ?? '');

        $file = $this->request->getFile('foto');
        $nama = $file->getRandomName();
        $file->move($dir, $nama);

        $db->table('admin')->where('id_admin', $this->idAdmin())->update(['foto' => $nama]);

        if ($lama !== '' && is_file($dir . '/' . $lama)) {
            @unlink($dir . '/' . $lama);
        }

        session()->set('foto', $nama);

        return redirect()->to('/admin/akun')->with('success', 'Foto profil diperbarui.');
    }

    public function hapusFoto()
    {
        $dir  = FCPATH . 'uploads/profil';
        $lama = (string) ($this->ambilAdmin()['foto'] ?? '');

        if ($lama !== '' && is_file($dir . '/' . $lama)) {
            @unlink($dir . '/' . $lama);
        }

        \Config\Database::connect()->table('admin')
            ->where('id_admin', $this->idAdmin())
            ->update(['foto' => null]);

        session()->set('foto', '');

        return redirect()->to('/admin/akun')->with('success', 'Foto profil dihapus.');
    }
}