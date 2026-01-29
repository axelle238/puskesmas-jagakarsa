<?php

namespace App\Livewire\Farmasi;

use App\Models\Obat;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class StokObat
 * Manajemen Master Data Obat dan Stok Farmasi.
 */
class StokObat extends Component
{
    use WithPagination;

    public $cari = '';
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idObatDiedit = null;

    // Form Fields
    public $kode_obat;
    public $nama_obat;
    public $kategori;
    public $satuan;
    public $stok_saat_ini;
    public $stok_minimum;
    public $harga_satuan;
    public $tanggal_kedaluwarsa;

    protected $rules = [
        'kode_obat' => 'required|unique:obat,kode_obat',
        'nama_obat' => 'required|string',
        'kategori' => 'required|string',
        'satuan' => 'required|string',
        'stok_saat_ini' => 'required|numeric|min:0',
        'stok_minimum' => 'required|numeric|min:0',
        'harga_satuan' => 'required|numeric|min:0',
        'tanggal_kedaluwarsa' => 'required|date',
    ];

    public function updatedCari()
    {
        $this->resetPage();
    }

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
        $this->modeEdit = false;
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $this->idObatDiedit = $id;
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
            $rules['kode_obat'] = 'required|unique:obat,kode_obat,' . $this->idObatDiedit;
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
            Obat::find($this->idObatDiedit)->update($data);
            session()->flash('sukses', 'Data obat berhasil diperbarui.');
        } else {
            Obat::create($data);
            session()->flash('sukses', 'Obat baru berhasil ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        try {
            Obat::find($id)->delete();
            session()->flash('sukses', 'Data obat berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus. Obat mungkin sedang digunakan dalam transaksi.');
        }
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['kode_obat', 'nama_obat', 'kategori', 'satuan', 'stok_saat_ini', 'stok_minimum', 'harga_satuan', 'tanggal_kedaluwarsa', 'idObatDiedit']);
    }

    public function render()
    {
        $obat = Obat::where('nama_obat', 'like', '%' . $this->cari . '%')
            ->orWhere('kode_obat', 'like', '%' . $this->cari . '%')
            ->orderBy('nama_obat', 'asc')
            ->paginate(10);

        return view('livewire.farmasi.stok-obat', [
            'dataObat' => $obat
        ])->layout('components.layouts.admin', ['title' => 'Stok Farmasi']);
    }
}