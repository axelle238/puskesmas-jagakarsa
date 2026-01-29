<?php

namespace App\Livewire\Barang;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\MutasiBarang;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class DaftarBarang
 * Manajemen Inventaris dan Aset Puskesmas.
 */
class DaftarBarang extends Component
{
    use WithPagination;

    public $cari = '';
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idBarangDiedit = null;

    // Form
    public $kode_barang;
    public $nama_barang;
    public $id_kategori;
    public $merk;
    public $nomor_seri;
    public $tahun_perolehan;
    public $harga_perolehan;
    public $kondisi = 'baik';
    public $lokasi_penyimpanan;
    public $keterangan;

    // Filter
    public $filterKategori = '';
    public $filterKondisi = '';

    protected $rules = [
        'kode_barang' => 'required|unique:barang,kode_barang',
        'nama_barang' => 'required',
        'id_kategori' => 'required',
        'kondisi' => 'required',
    ];

    public function updatedCari() { $this->resetPage(); }

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
        $this->modeEdit = false;
    }

    public function edit($id)
    {
        $b = Barang::findOrFail($id);
        $this->idBarangDiedit = $id;
        $this->kode_barang = $b->kode_barang;
        $this->nama_barang = $b->nama_barang;
        $this->id_kategori = $b->id_kategori;
        $this->merk = $b->merk;
        $this->nomor_seri = $b->nomor_seri;
        $this->tahun_perolehan = $b->tahun_perolehan;
        $this->harga_perolehan = $b->harga_perolehan;
        $this->kondisi = $b->kondisi;
        $this->lokasi_penyimpanan = $b->lokasi_penyimpanan;
        $this->keterangan = $b->keterangan;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['kode_barang'] = 'required|unique:barang,kode_barang,' . $this->idBarangDiedit;
        }
        $this->validate($rules);

        $data = [
            'kode_barang' => $this->kode_barang,
            'nama_barang' => $this->nama_barang,
            'id_kategori' => $this->id_kategori,
            'merk' => $this->merk,
            'nomor_seri' => $this->nomor_seri,
            'tahun_perolehan' => $this->tahun_perolehan,
            'harga_perolehan' => $this->harga_perolehan ?: 0,
            'kondisi' => $this->kondisi,
            'lokasi_penyimpanan' => $this->lokasi_penyimpanan,
            'keterangan' => $this->keterangan,
        ];

        if ($this->modeEdit) {
            $barangLama = Barang::find($this->idBarangDiedit);
            
            // Cek perubahan kondisi/lokasi untuk audit trail
            if ($barangLama->kondisi != $this->kondisi || $barangLama->lokasi_penyimpanan != $this->lokasi_penyimpanan) {
                MutasiBarang::create([
                    'id_barang' => $barangLama->id,
                    'id_pegawai' => auth()->user()->pegawai->id ?? null,
                    'tanggal_mutasi' => now(),
                    'jenis_mutasi' => 'Update Data',
                    'keterangan_lama' => "{$barangLama->kondisi} / {$barangLama->lokasi_penyimpanan}",
                    'keterangan_baru' => "{$this->kondisi} / {$this->lokasi_penyimpanan}",
                    'catatan' => 'Perubahan via Edit Admin'
                ]);
            }

            $barangLama->update($data);
            session()->flash('sukses', 'Data barang berhasil diperbarui.');
        } else {
            $barang = Barang::create($data);
            
            // Log Awal
            MutasiBarang::create([
                'id_barang' => $barang->id,
                'id_pegawai' => auth()->user()->pegawai->id ?? null,
                'tanggal_mutasi' => now(),
                'jenis_mutasi' => 'Registrasi Awal',
                'keterangan_baru' => "Kondisi: {$this->kondisi}, Lokasi: {$this->lokasi_penyimpanan}",
                'catatan' => 'Input Barang Baru'
            ]);

            session()->flash('sukses', 'Barang baru berhasil ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        Barang::find($id)->delete();
        session()->flash('sukses', 'Data barang dihapus.');
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['kode_barang', 'nama_barang', 'id_kategori', 'merk', 'nomor_seri', 'tahun_perolehan', 'harga_perolehan', 'kondisi', 'lokasi_penyimpanan', 'keterangan', 'idBarangDiedit']);
    }

    public function render()
    {
        $query = Barang::with('kategori');

        if ($this->cari) {
            $query->where('nama_barang', 'like', '%' . $this->cari . '%')
                  ->orWhere('kode_barang', 'like', '%' . $this->cari . '%');
        }

        if ($this->filterKategori) {
            $query->where('id_kategori', $this->filterKategori);
        }

        if ($this->filterKondisi) {
            $query->where('kondisi', $this->filterKondisi);
        }

        $barang = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoriList = KategoriBarang::all();

        return view('livewire.barang.daftar-barang', [
            'dataBarang' => $barang,
            'kategoriList' => $kategoriList
        ])->layout('components.layouts.admin', ['title' => 'Manajemen Barang & Aset']);
    }
}
