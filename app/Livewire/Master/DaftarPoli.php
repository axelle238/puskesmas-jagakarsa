<?php

namespace App\Livewire\Master;

use App\Models\KlasterIlp;
use App\Models\Poli;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class DaftarPoli
 * Manajemen unit layanan / poli yang tersedia di Puskesmas.
 * Terintegrasi dengan Klaster ILP.
 */
class DaftarPoli extends Component
{
    use WithPagination;

    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idPoliDiedit = null;

    // Form
    public $nama_poli;
    public $kode_poli;
    public $deskripsi;
    public $lokasi_ruangan;
    public $id_klaster;
    public $aktif = true;

    protected $rules = [
        'nama_poli' => 'required|string|max:255',
        'kode_poli' => 'required|string|max:10|unique:poli,kode_poli',
        'id_klaster' => 'required|exists:klaster_ilp,id',
    ];

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
        $this->modeEdit = false;
    }

    public function edit($id)
    {
        $poli = Poli::findOrFail($id);
        $this->idPoliDiedit = $id;
        $this->nama_poli = $poli->nama_poli;
        $this->kode_poli = $poli->kode_poli;
        $this->deskripsi = $poli->deskripsi;
        $this->lokasi_ruangan = $poli->lokasi_ruangan;
        $this->id_klaster = $poli->id_klaster;
        $this->aktif = $poli->aktif;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['kode_poli'] = 'required|string|max:10|unique:poli,kode_poli,' . $this->idPoliDiedit;
        }

        $this->validate($rules);

        $data = [
            'nama_poli' => $this->nama_poli,
            'kode_poli' => $this->kode_poli,
            'deskripsi' => $this->deskripsi,
            'lokasi_ruangan' => $this->lokasi_ruangan,
            'id_klaster' => $this->id_klaster,
            'aktif' => $this->aktif,
        ];

        if ($this->modeEdit) {
            Poli::find($this->idPoliDiedit)->update($data);
            session()->flash('sukses', 'Data poli berhasil diperbarui.');
        } else {
            Poli::create($data);
            session()->flash('sukses', 'Poli baru berhasil ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        // Cek relasi jadwal dulu agar aman, tapi untuk sekarang hapus saja.
        Poli::find($id)->delete();
        session()->flash('sukses', 'Data poli berhasil dihapus.');
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nama_poli', 'kode_poli', 'deskripsi', 'lokasi_ruangan', 'id_klaster', 'aktif', 'idPoliDiedit']);
    }

    public function render()
    {
        $poli = Poli::with('klaster')->orderBy('kode_poli')->paginate(10);
        $klasterList = KlasterIlp::all();

        return view('livewire.master.daftar-poli', [
            'dataPoli' => $poli,
            'klasterList' => $klasterList
        ])->layout('components.layouts.admin', ['title' => 'Master Poli']);
    }
}