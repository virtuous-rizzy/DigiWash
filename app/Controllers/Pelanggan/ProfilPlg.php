<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
<<<<<<< HEAD
use App\Models\AlamatModel;
=======
>>>>>>> origin/digiwash-semoga-final
use App\Models\PelangganModel;

class ProfilPlg extends BaseController
{
    private function idPelanggan(): int
    {
<<<<<<< HEAD
        return (int) session()->get('user_id');
=======
        return (int) (session()->get('id_pelanggan') ?? session()->get('user_id') ?? 0);
>>>>>>> origin/digiwash-semoga-final
    }

    private function ambilUser(): ?array
    {
        $user = (new PelangganModel())->find($this->idPelanggan());

        return is_array($user) ? $user : null;
    }

    private function keluarkan()
    {
        session()->destroy();
        return redirect()->to('/masuk');
    }

    public function index()
    {
        $user = $this->ambilUser();
        if (! $user) {
            return $this->keluarkan();
        }

        return view('pelanggan/profil_plg', ['user' => $user, 'judul' => 'Profil Saya']);
    }

    public function data()
    {
        $user = $this->ambilUser();
        if (! $user) {
            return $this->keluarkan();
        }

        return view('pelanggan/data_plg', [
            'user'  => $user,
<<<<<<< HEAD
            'utama' => (new AlamatModel())->utama($this->idPelanggan()),
=======
>>>>>>> origin/digiwash-semoga-final
            'judul' => 'Data Pribadi',
        ]);
    }

    public function ubah()
    {
        $user = $this->ambilUser();
        if (! $user) {
            return $this->keluarkan();
        }

<<<<<<< HEAD
        return view('pelanggan/data_ubah_plg', ['user' => $user, 'judul' => 'Ubah Data Pribadi']);
=======
        return view('pelanggan/data_ubah_plg', [
            'user'  => $user,
            'judul' => 'Ubah Data Pribadi',
        ]);
>>>>>>> origin/digiwash-semoga-final
    }

    public function simpan()
    {
        $id = $this->idPelanggan();

        $rules = [
            'nama'          => 'required|min_length[3]',
            'no_hp'         => 'required|numeric|min_length[10]|is_unique[pelanggan.no_hp,id_pelanggan,' . $id . ']',
<<<<<<< HEAD
=======
            'alamat'        => 'permit_empty',
>>>>>>> origin/digiwash-semoga-final
            'tanggal_lahir' => 'permit_empty|valid_date[Y-m-d]',
            'jenis_kelamin' => 'permit_empty|in_list[Laki-laki,Perempuan]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

<<<<<<< HEAD
        $nama = trim((string) $this->request->getPost('nama'));

        (new PelangganModel())->update($id, [
            'nama'          => $nama,
            'no_hp'         => $this->request->getPost('no_hp'),
=======
        $nama   = trim((string) $this->request->getPost('nama'));
        $noHp   = trim((string) $this->request->getPost('no_hp'));
        $alamat = trim((string) $this->request->getPost('alamat'));

        (new PelangganModel())->update($id, [
            'nama'          => $nama,
            'no_hp'         => $noHp,
            'alamat'        => $alamat !== '' ? $alamat : null,
>>>>>>> origin/digiwash-semoga-final
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin') ?: null,
        ]);

        session()->set('nama', $nama);

        return redirect()->to('/profil/data')->with('success', 'Data pribadi berhasil diperbarui.');
    }

    public function password()
    {
        $user = $this->ambilUser();
        if (! $user) {
            return $this->keluarkan();
        }

        return view('pelanggan/katasandi_plg', ['user' => $user, 'judul' => 'Ganti Kata Sandi']);
    }

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

        $user = $this->ambilUser();

        if (! $user || ! password_verify((string) $this->request->getPost('password_lama'), $user['password'])) {
            return redirect()->back()->with('errors', ['Kata sandi lama salah.']);
        }

        (new PelangganModel())->update($this->idPelanggan(), [
            'password' => password_hash((string) $this->request->getPost('password_baru'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/profil')->with('success', 'Kata sandi berhasil diganti.');
    }

    public function bantuan()
    {
        return view('pelanggan/bantuan_plg', ['judul' => 'Bantuan']);
    }

    public function tentang()
    {
        return view('pelanggan/tentang_plg', ['judul' => 'Tentang Aplikasi']);
    }
<<<<<<< HEAD
=======
    // ===== ALAMAT =====
    public function alamat()
    {
        $user = $this->ambilUser();
        if (! $user) {
            return $this->keluarkan();
        }

        return view('pelanggan/alamat_plg', [
            'user'  => $user,
            'judul' => 'Alamat Saya',
        ]);
    }

    public function ubahAlamat()
    {
        $user = $this->ambilUser();
        if (! $user) {
            return $this->keluarkan();
        }

        $tipe = $this->request->getGet('tipe') === 'alternatif' ? 'alternatif' : 'utama';

        return view('pelanggan/alamat_form_plg', [
            'user'  => $user,
            'tipe'  => $tipe,
            'judul' => $tipe === 'alternatif' ? 'Ubah Alamat Alternatif' : 'Ubah Alamat Utama',
        ]);
    }

    public function simpanAlamat()
    {
        $id   = $this->idPelanggan();
        $tipe = $this->request->getPost('tipe') === 'alternatif' ? 'alternatif' : 'utama';

        if ($tipe === 'alternatif') {
            $field = 'alamat_alternatif';
        } else {
            $field = 'alamat';
        }

        $nilai = trim((string) $this->request->getPost($field));

        (new PelangganModel())->update($id, [
            $field => $nilai !== '' ? $nilai : null,
        ]);

        return redirect()->to('/profil/alamat')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function hapusAlamat()
    {
        $id   = $this->idPelanggan();
        $tipe = $this->request->getPost('tipe') === 'alternatif' ? 'alternatif' : 'utama';
        $field = $tipe === 'alternatif' ? 'alamat_alternatif' : 'alamat';

        (new PelangganModel())->update($id, [$field => null]);

        return redirect()->to('/profil/alamat')->with('success', 'Alamat berhasil dihapus.');
    }

>>>>>>> origin/digiwash-semoga-final
    public function foto()
{
    $rules = [
        'foto' => [
            'label' => 'Foto',
            'rules' => 'uploaded[foto]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
        ],
    ];

    if (! $this->validate($rules)) {
        return redirect()->to('/profil')->with('errors', $this->validator->getErrors());
    }

    $id  = (int) session()->get('user_id');
    $db  = \Config\Database::connect();
    $dir = FCPATH . 'uploads/profil';

    if (! is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $baris = $db->table('pelanggan')->select('foto')->where('id_pelanggan', $id)->get()->getRowArray();
    $lama  = (string) ($baris['foto'] ?? '');

    $file = $this->request->getFile('foto');
    $nama = $file->getRandomName();
    $file->move($dir, $nama);

    $db->table('pelanggan')->where('id_pelanggan', $id)->update(['foto' => $nama]);

    if ($lama !== '' && is_file($dir . '/' . $lama)) {
        @unlink($dir . '/' . $lama);
    }

    session()->set('foto', $nama);

    return redirect()->to('/profil')->with('success', 'Foto profil diperbarui.');
}

public function hapusFoto()
{
    $id  = (int) session()->get('user_id');
    $db  = \Config\Database::connect();
    $dir = FCPATH . 'uploads/profil';

    $baris = $db->table('pelanggan')->select('foto')->where('id_pelanggan', $id)->get()->getRowArray();
    $lama  = (string) ($baris['foto'] ?? '');

    if ($lama !== '' && is_file($dir . '/' . $lama)) {
        @unlink($dir . '/' . $lama);
    }

    $db->table('pelanggan')->where('id_pelanggan', $id)->update(['foto' => null]);
    session()->set('foto', '');

    return redirect()->to('/profil')->with('success', 'Foto profil dihapus.');
}

}