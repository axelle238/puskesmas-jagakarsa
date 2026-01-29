<?php

namespace App\Livewire\Farmasi;

use App\Models\Obat;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Stok Obat')]
class StokObat extends Component
{
    use WithPagination;

    public $cari = '';
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idObat;

    // Form Data
    public $kode_obat, $nama_obat, $kategori, $satuan, $stok_saat_ini, $stok_minimum, $harga_satuan, $tanggal_kedaluwarsa;

    protected $rules = [
        'kode_obat' => 'required|unique:obat,kode_obat',
        'nama_obat' => 'required',
        'stok_saat_ini' => 'required|numeric|min:0',
        'harga_satuan' => 'required|numeric|min:0',
        'tanggal_kedaluwarsa' => 'required|date',
    ];

    public function render()
    {
        return view('livewire.farmasi.stok-obat', [
            'obats' => Obat::where('nama_obat', 'like', '%' . $this->cari . '%')
                ->orWhere('kode_obat', 'like', '%' . $this->cari . '%')
                ->orderBy('nama_obat')
                ->paginate(10)
        ])->layout('components.layouts.admin');
    }

    public function tambah()
    {
        $this->resetForm();
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $obat = Obat::find($id);
        $this->idObat = $id;
        $this->kode_obat = $obat->kode_obat;
        $this->nama_obat = $obat->nama_obat;
        $this->kategori = $obat->kategori;
        $this->satuan = $obat->satuan;
        $this->stok_saat_ini = $obat->stok_saat_ini;
        $this->stok_minimum = $obat->stok_minimum;
        $this->harga_satuan = $obat->harga_satuan;
        $this->tanggal_kedaluwarsa = $obat->tanggal_kedaluwarsa->format('Y-m-d');
        
        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['kode_obat'] = 'required|unique:obat,kode_obat,' . $this->idObat;
        }

        $this->validate($rules);

        $data = [
            'kode_obat' => $this->kode_obat,
            'nama_obat' => $this->nama_obat,
            'kategori' => $this->kategori,
            'satuan' => $this->satuan,
            'stok_saat_ini' => $this->stok_saat_ini,
            'stok_minimum' => $this->stok_minimum,
            'harga_satuan' => $this->harga_satuan,
            'tanggal_kedaluwarsa' => $this->tanggal_kedaluwarsa,
        ];

        if ($this->modeEdit) {
            Obat::find($this->idObat)->update($data);
            session()->flash('pesan', 'Data obat diperbarui.');
        } else {
            Obat::create($data);
            session()->flash('pesan', 'Obat baru ditambahkan.');
        }

        $this->tampilkanModal = false;
        $this->resetForm();
    }

    public function hapus($id)
    {
        Obat::find($id)->delete();
        session()->flash('pesan', 'Obat dihapus dari database.');
    }

    public function resetForm()
    {
        $this->reset(['kode_obat', 'nama_obat', 'kategori', 'satuan', 'stok_saat_ini', 'stok_minimum', 'harga_satuan', 'tanggal_kedaluwarsa', 'idObat']);
    }
}
