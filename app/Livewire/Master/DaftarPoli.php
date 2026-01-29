<?php

namespace App\Livewire\Master;

use App\Models\Poli;
use App\Models\KlasterIlp;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Manajemen Poli')]
class DaftarPoli extends Component
{
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idPoli;

    public $id_klaster, $kode_poli, $nama_poli, $deskripsi, $lokasi_ruangan;

    protected $rules = [
        'nama_poli' => 'required|string|max:255',
        'kode_poli' => 'required|string|max:10|unique:poli,kode_poli',
        'id_klaster' => 'required|exists:klaster_ilp,id',
        'lokasi_ruangan' => 'nullable|string',
    ];

    public function render()
    {
        return view('livewire.master.daftar-poli', [
            'polis' => Poli::with('klaster')->withCount('rekamMedis')->get(),
            'klasters' => KlasterIlp::all()
        ])->layout('components.layouts.admin');
    }

    public function tambah()
    {
        $this->reset(['id_klaster', 'kode_poli', 'nama_poli', 'deskripsi', 'lokasi_ruangan', 'idPoli']);
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $poli = Poli::find($id);
        $this->idPoli = $id;
        $this->id_klaster = $poli->id_klaster;
        $this->kode_poli = $poli->kode_poli;
        $this->nama_poli = $poli->nama_poli;
        $this->deskripsi = $poli->deskripsi;
        $this->lokasi_ruangan = $poli->lokasi_ruangan;
        
        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['kode_poli'] = 'required|string|max:10|unique:poli,kode_poli,' . $this->idPoli;
        }

        $this->validate($rules);

        $data = [
            'id_klaster' => $this->id_klaster,
            'kode_poli' => $this->kode_poli,
            'nama_poli' => $this->nama_poli,
            'deskripsi' => $this->deskripsi,
            'lokasi_ruangan' => $this->lokasi_ruangan,
        ];

        if ($this->modeEdit) {
            Poli::find($this->idPoli)->update($data);
            session()->flash('pesan', 'Poli berhasil diperbarui.');
        } else {
            Poli::create($data);
            session()->flash('pesan', 'Poli baru berhasil ditambahkan.');
        }

        $this->tampilkanModal = false;
    }

    public function hapus($id)
    {
        Poli::find($id)->delete();
        session()->flash('pesan', 'Poli berhasil dihapus.');
    }
}
