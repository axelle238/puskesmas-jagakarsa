<?php

namespace App\Livewire\Master;

use App\Models\Poli;
use App\Models\TindakanMedis;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarTindakan extends Component
{
    use WithPagination;

    public $cari = '';
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idTindakanDiedit = null;

    // Form
    public $kode_tindakan;
    public $nama_tindakan;
    public $id_poli;
    public $tarif;
    public $aktif = true;

    protected $rules = [
        'kode_tindakan' => 'required|unique:tindakan_medis,kode_tindakan',
        'nama_tindakan' => 'required',
        'id_poli' => 'required',
        'tarif' => 'required|numeric|min:0',
    ];

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
        $this->modeEdit = false;
    }

    public function edit($id)
    {
        $t = TindakanMedis::findOrFail($id);
        $this->idTindakanDiedit = $id;
        $this->kode_tindakan = $t->kode_tindakan;
        $this->nama_tindakan = $t->nama_tindakan;
        $this->id_poli = $t->id_poli;
        $this->tarif = $t->tarif;
        $this->aktif = $t->aktif;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['kode_tindakan'] = 'required|unique:tindakan_medis,kode_tindakan,' . $this->idTindakanDiedit;
        }
        $this->validate($rules);

        $data = [
            'kode_tindakan' => $this->kode_tindakan,
            'nama_tindakan' => $this->nama_tindakan,
            'id_poli' => $this->id_poli,
            'tarif' => $this->tarif,
            'aktif' => $this->aktif,
        ];

        if ($this->modeEdit) {
            TindakanMedis::find($this->idTindakanDiedit)->update($data);
            session()->flash('sukses', 'Tindakan diperbarui.');
        } else {
            TindakanMedis::create($data);
            session()->flash('sukses', 'Tindakan ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        TindakanMedis::find($id)->delete();
        session()->flash('sukses', 'Tindakan dihapus.');
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['kode_tindakan', 'nama_tindakan', 'id_poli', 'tarif', 'aktif', 'idTindakanDiedit']);
    }

    public function render()
    {
        $tindakan = TindakanMedis::with('poli')
            ->where('nama_tindakan', 'like', '%' . $this->cari . '%')
            ->orderBy('kode_tindakan')
            ->paginate(10);
            
        $poliList = Poli::where('aktif', true)->get();

        return view('livewire.master.daftar-tindakan', [
            'dataTindakan' => $tindakan,
            'poliList' => $poliList
        ])->layout('components.layouts.admin', ['title' => 'Master Tindakan Medis']);
    }
}
