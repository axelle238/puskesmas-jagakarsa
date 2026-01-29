<?php

namespace App\Livewire\Kesekretariatan;

use App\Models\DisposisiSurat;
use App\Models\Pegawai;
use App\Models\SuratMasuk as ModelSuratMasuk;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class SuratMasuk extends Component
{
    use WithPagination, WithFileUploads;

    public $cari = '';
    public $tampilkanModal = false;
    public $tampilkanModalDisposisi = false;
    public $suratTerpilih = null;

    // Form Surat
    public $nomor_surat;
    public $asal_surat;
    public $perihal;
    public $tanggal_surat;
    public $tanggal_diterima;
    public $sifat = 'biasa';
    public $file_dokumen;

    // Form Disposisi
    public $ke_pegawai_id;
    public $instruksi;
    public $catatan_tambahan;
    public $batas_waktu;

    protected $rules = [
        'nomor_surat' => 'required|unique:surat_masuk,nomor_surat',
        'asal_surat' => 'required',
        'perihal' => 'required',
        'tanggal_surat' => 'required|date',
        'tanggal_diterima' => 'required|date',
        'file_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
    ];

    public function mount()
    {
        $this->tanggal_diterima = date('Y-m-d');
    }

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate();

        $path = null;
        if ($this->file_dokumen) {
            $path = $this->file_dokumen->store('arsip_surat_masuk', 'public');
        }

        ModelSuratMasuk::create([
            'nomor_surat' => $this->nomor_surat,
            'asal_surat' => $this->asal_surat,
            'perihal' => $this->perihal,
            'tanggal_surat' => $this->tanggal_surat,
            'tanggal_diterima' => $this->tanggal_diterima,
            'sifat' => $this->sifat,
            'file_dokumen' => $path,
            'status' => 'menunggu_disposisi',
            'id_pencatat' => auth()->id()
        ]);

        $this->tutupModal();
        session()->flash('sukses', 'Surat masuk berhasil dicatat.');
    }

    public function bukaDisposisi($id)
    {
        $this->suratTerpilih = ModelSuratMasuk::with('disposisi.kePegawai')->find($id);
        $this->tampilkanModalDisposisi = true;
        $this->reset(['ke_pegawai_id', 'instruksi', 'catatan_tambahan', 'batas_waktu']);
    }

    public function simpanDisposisi()
    {
        $this->validate([
            'ke_pegawai_id' => 'required',
            'instruksi' => 'required',
            'batas_waktu' => 'nullable|date'
        ]);

        // Cek pengirim (asumsi user login punya relasi pegawai, kalau admin pake ID pegawai 1 sementara atau ambil dari session pegawai)
        $pengirimId = auth()->user()->pegawai->id ?? 1; // Fallback ID 1 jika testing

        DisposisiSurat::create([
            'surat_masuk_id' => $this->suratTerpilih->id,
            'dari_pegawai_id' => $pengirimId, 
            'ke_pegawai_id' => $this->ke_pegawai_id,
            'instruksi' => $this->instruksi,
            'catatan_tambahan' => $this->catatan_tambahan,
            'batas_waktu' => $this->batas_waktu,
            'status_penyelesaian' => 'belum'
        ]);

        $this->suratTerpilih->update(['status' => 'didisposisi']);
        
        // Refresh data
        $this->suratTerpilih = ModelSuratMasuk::with('disposisi.kePegawai')->find($this->suratTerpilih->id);
        
        $this->reset(['ke_pegawai_id', 'instruksi', 'catatan_tambahan', 'batas_waktu']);
        session()->flash('sukses_disposisi', 'Disposisi berhasil dikirim.');
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->tampilkanModalDisposisi = false;
    }

    public function resetForm()
    {
        $this->reset(['nomor_surat', 'asal_surat', 'perihal', 'tanggal_surat', 'file_dokumen']);
        $this->tanggal_diterima = date('Y-m-d');
    }

    public function render()
    {
        $surat = ModelSuratMasuk::where('perihal', 'like', '%' . $this->cari . '%')
            ->orWhere('nomor_surat', 'like', '%' . $this->cari . '%')
            ->orderBy('tanggal_diterima', 'desc')
            ->paginate(10);

        $pegawaiList = Pegawai::where('status_aktif', 'Aktif')->orderBy('nama_lengkap')->get();

        return view('livewire.kesekretariatan.surat-masuk', [
            'dataSurat' => $surat,
            'pegawaiList' => $pegawaiList
        ])->layout('components.layouts.admin', ['title' => 'Surat Masuk & Disposisi']);
    }
}
