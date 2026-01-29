<?php

namespace App\Livewire\Kesekretariatan;

use App\Models\SuratKeluar as ModelSuratKeluar;
use App\Models\Pegawai; // To select the drafter/creator
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class SuratKeluar extends Component
{
    use WithPagination, WithFileUploads;

    public $cari = '';
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idSuratDiedit = null;

    // Form Fields
    public $nomor_surat;
    public $tujuan_surat;
    public $perihal;
    public $tanggal_surat;
    public $sifat = 'biasa';
    public $file_dokumen;
    public $file_dokumen_lama;
    public $id_pembuat; // Pegawai who drafted it

    protected $rules = [
        'nomor_surat' => 'required|unique:surat_keluar,nomor_surat',
        'tujuan_surat' => 'required',
        'perihal' => 'required',
        'tanggal_surat' => 'required|date',
        'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB
    ];

    public function mount()
    {
        $this->tanggal_surat = date('Y-m-d');
    }

    public function tambah()
    {
        $this->resetForm();
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $s = ModelSuratKeluar::findOrFail($id);
        $this->idSuratDiedit = $id;
        $this->nomor_surat = $s->nomor_surat;
        $this->tujuan_surat = $s->tujuan_surat;
        $this->perihal = $s->perihal;
        $this->tanggal_surat = $s->tanggal_surat->format('Y-m-d');
        $this->sifat = $s->sifat;
        $this->file_dokumen_lama = $s->file_dokumen;
        $this->id_pembuat = $s->id_pembuat;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['nomor_surat'] = 'required|unique:surat_keluar,nomor_surat,' . $this->idSuratDiedit;
        }
        $this->validate($rules);

        $path = $this->file_dokumen_lama;
        if ($this->file_dokumen) {
            $path = $this->file_dokumen->store('arsip_surat_keluar', 'public');
        }

        $data = [
            'nomor_surat' => $this->nomor_surat,
            'tujuan_surat' => $this->tujuan_surat,
            'perihal' => $this->perihal,
            'tanggal_surat' => $this->tanggal_surat,
            'sifat' => $this->sifat,
            'file_dokumen' => $path,
            'id_pembuat' => $this->id_pembuat ?: auth()->user()->pegawai->id ?? null
        ];

        if ($this->modeEdit) {
            ModelSuratKeluar::find($this->idSuratDiedit)->update($data);
            session()->flash('sukses', 'Surat keluar diperbarui.');
        } else {
            ModelSuratKeluar::create($data);
            session()->flash('sukses', 'Surat keluar berhasil diarsipkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        ModelSuratKeluar::find($id)->delete();
        session()->flash('sukses', 'Arsip surat dihapus.');
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['nomor_surat', 'tujuan_surat', 'perihal', 'tanggal_surat', 'sifat', 'file_dokumen', 'file_dokumen_lama', 'id_pembuat', 'idSuratDiedit']);
        $this->tanggal_surat = date('Y-m-d');
    }

    public function render()
    {
        $surat = ModelSuratKeluar::with('pembuat')
            ->where('perihal', 'like', '%' . $this->cari . '%')
            ->orWhere('nomor_surat', 'like', '%' . $this->cari . '%')
            ->orWhere('tujuan_surat', 'like', '%' . $this->cari . '%')
            ->orderBy('tanggal_surat', 'desc')
            ->paginate(10);
            
        $pegawaiList = Pegawai::where('status_aktif', 'Aktif')->orderBy('nama_lengkap')->get();

        return view('livewire.kesekretariatan.surat-keluar', [
            'dataSurat' => $surat,
            'pegawaiList' => $pegawaiList
        ])->layout('components.layouts.admin', ['title' => 'Arsip Surat Keluar']);
    }
}
