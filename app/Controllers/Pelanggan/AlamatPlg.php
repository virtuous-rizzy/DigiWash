<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\AlamatModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AlamatPlg extends BaseController
{
    private array $rules = [
        'label'          => 'required|max_length[30]',
        'nama_penerima'  => 'required|min_length[3]|max_length[100]',
        'no_hp'          => 'required|numeric|min_length[10]|max_length[20]',
        'alamat_lengkap' => 'required|min_length[10]',
        'catatan_kurir'  => 'permit_empty|max_length[150]',
    ];

    private function idPelanggan(): int
    {
        return (int) session()->get('user_id');
    }

    private function dataForm(): array
    {
        return [
            'label'          => trim((string) $this->request->getPost('label')),
            'nama_penerima'  => trim((string) $this->request->getPost('nama_penerima')),
            'no_hp'          => trim((string) $this->request->getPost('no_hp')),
            'alamat_lengkap' => trim((string) $this->request->getPost('alamat_lengkap')),
            'catatan_kurir'  => trim((string) $this->request->getPost('catatan_kurir')) ?: null,
        ];
    }

    public function index()
    {
        return view('pelanggan/alamat_plg', [
            'daftar' => (new AlamatModel())->daftar($this->idPelanggan()),
        ]);
    }

    public function tambah()
    {
        return view('pelanggan/alamat_form_plg', ['alamat' => null]);
    }

    public function simpan()
    {
        if (! $this->validate($this->rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new AlamatModel();
        $idP   = $this->idPelanggan();

        $pertama = $model->where('id_pelanggan', $idP)->countAllResults() === 0;

        $idBaru = $model->insert($this->dataForm() + ['id_pelanggan' => $idP, 'is_default' => 0]);

        if ($pertama || $this->request->getPost('is_default')) {
            $model->setDefault((int) $idBaru, $idP);
        }

        return redirect()->to('/alamat')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function ubah(int $id)
    {
        $alamat = (new AlamatModel())->milik($id, $this->idPelanggan());

        if (! $alamat) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('pelanggan/alamat_form_plg', ['alamat' => $alamat]);
    }

    public function update(int $id)
    {
        $model = new AlamatModel();
        $idP   = $this->idPelanggan();

        if (! $model->milik($id, $idP)) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate($this->rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, $this->dataForm());

        if ($this->request->getPost('is_default')) {
            $model->setDefault($id, $idP);
        }

        return redirect()->to('/alamat')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function utama(int $id)
    {
        $model = new AlamatModel();
        $idP   = $this->idPelanggan();

        if (! $model->milik($id, $idP)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $model->setDefault($id, $idP);

        return redirect()->to('/alamat')->with('success', 'Alamat utama diganti.');
    }

    public function hapus(int $id)
    {
        $model  = new AlamatModel();
        $idP    = $this->idPelanggan();
        $alamat = $model->milik($id, $idP);

        if (! $alamat) {
            throw PageNotFoundException::forPageNotFound();
        }

        $model->delete($id);

        if ((int) $alamat['is_default'] === 1) {
            $sisa = $model->daftar($idP);
            if ($sisa) {
                $model->setDefault((int) $sisa[0]['id_alamat'], $idP);
            }
        }

        return redirect()->to('/alamat')->with('success', 'Alamat dihapus.');
    }
}