<?php

namespace App\Controllers\Kurir;

use App\Controllers\BaseController;

class AkunKrr extends BaseController
{
    private function idKurir(): int
    {
        return (int) session()->get('user_id');
    }

    private function ambilKurir(): array
    {
        return \Config\Database::connect()->table('kurir')
            ->where('id_kurir', $this->idKurir())->get()->getRowArray() ?? [];
    }

    /** Nomor telepon sudah dipakai akun lain (kurir lain, admin, atau pelanggan)? */
    private function hpDipakai(string $hp): bool
    {
        $db = \Config\Database::connect();

        $kurirLain = $db->table('kurir')->where('no_hp', $hp)
            ->where('id_kurir !=', $this->idKurir())->countAllResults();

        return $kurirLain > 0
            || $db->table('admin')->where('noHP', $hp)->countAllResults() > 0
            || $db->table('pelanggan')->where('no_hp', $hp)->countAllResults() > 0;
    }

    // ---------- Halaman ----------

    public function index()
    {
        return view('kurir/akun_krr', ['kurir' => $this->ambilKurir(), 'aktif' => 'akun']);
    }

    public function data()
    {
        return view('kurir/datapribadi_krr', ['kurir' => $this->ambilKurir(), 'aktif' => 'akun']);
    }

    public function ubah()
    {
        return view('kurir/ubahdata_krr', ['kurir' => $this->ambilKurir(), 'aktif' => 'akun']);
    }

    public function password()
    {
        return view('kurir/katasandi_krr', ['kurir' => $this->ambilKurir(), 'aktif' => 'akun']);
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

        \Config\Database::connect()->table('kurir')
            ->where('id_kurir', $this->idKurir())
            ->update(['nama' => $nama, 'no_hp' => $hp]);

        session()->set('nama', $nama);

        return redirect()->to('/kurir/akun/data')->with('success', 'Data pribadi diperbarui.');
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

        $kurir = $this->ambilKurir();

        if (! $kurir || ! password_verify((string) $this->request->getPost('password_lama'), $kurir['password'])) {
            return redirect()->back()->with('errors', ['Kata sandi lama salah.']);
        }

        \Config\Database::connect()->table('kurir')
            ->where('id_kurir', $this->idKurir())
            ->update(['password' => password_hash((string) $this->request->getPost('password_baru'), PASSWORD_DEFAULT)]);

        return redirect()->to('/kurir/akun')->with('success', 'Kata sandi berhasil diganti.');
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
            return redirect()->to('/kurir/akun')->with('errors', $this->validator->getErrors());
        }

        $db  = \Config\Database::connect();
        $dir = FCPATH . 'uploads/profil';

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $lama = (string) ($this->ambilKurir()['foto'] ?? '');

        $file = $this->request->getFile('foto');
        $nama = $file->getRandomName();
        $file->move($dir, $nama);

        $db->table('kurir')->where('id_kurir', $this->idKurir())->update(['foto' => $nama]);

        if ($lama !== '' && is_file($dir . '/' . $lama)) {
            @unlink($dir . '/' . $lama);
        }

        session()->set('foto', $nama);

        return redirect()->to('/kurir/akun')->with('success', 'Foto profil diperbarui.');
    }

    public function hapusFoto()
    {
        $dir  = FCPATH . 'uploads/profil';
        $lama = (string) ($this->ambilKurir()['foto'] ?? '');

        if ($lama !== '' && is_file($dir . '/' . $lama)) {
            @unlink($dir . '/' . $lama);
        }

        \Config\Database::connect()->table('kurir')
            ->where('id_kurir', $this->idKurir())
            ->update(['foto' => null]);

        session()->set('foto', '');

        return redirect()->to('/kurir/akun')->with('success', 'Foto profil dihapus.');
    }
}