<?php

namespace App\Livewire\Pasien;

use App\Models\Pasien;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Title('Manajemen Pasien')]
class DaftarPasien extends Component
{
    use WithPagination;

    public $cari = '';
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idPasien;

    // Form Data
    public $nik, $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin = 'L';
    public $alamat_lengkap, $golongan_darah, $no_bpjs, $no_telepon_darurat, $nama_kontak_darurat;

    protected $rules = [
        'nik' => 'required|numeric|digits:16|unique:pasien,nik',
        'nama_lengkap' => 'required|string|max:255',
        'tempat_lahir' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:L,P',
        'alamat_lengkap' => 'required|string',
    ];

    public function render()
    {
        $pasiens = Pasien::where('nama_lengkap', 'like', '%' . $this->cari . '%')
            ->orWhere('no_rekam_medis', 'like', '%' . $this->cari . '%')
            ->orWhere('nik', 'like', '%' . $this->cari . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pasien.daftar-pasien', ['pasiens' => $pasiens])
            ->layout('components.layouts.admin');
    }

    public function tambah()
    {
        $this->resetForm();
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $pasien = Pasien::find($id);
        $this->idPasien = $id;
        $this->nik = $pasien->nik;
        $this->nama_lengkap = $pasien->nama_lengkap;
        $this->tempat_lahir = $pasien->tempat_lahir;
        $this->tanggal_lahir = $pasien->tanggal_lahir->format('Y-m-d');
        $this->jenis_kelamin = $pasien->jenis_kelamin;
        $this->alamat_lengkap = $pasien->alamat_lengkap;
        $this->golongan_darah = $pasien->golongan_darah;
        $this->no_bpjs = $pasien->no_bpjs;
        $this->no_telepon_darurat = $pasien->no_telepon_darurat;
        $this->nama_kontak_darurat = $pasien->nama_kontak_darurat;
        
        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        // Adjust rules for update
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['nik'] = 'required|numeric|digits:16|unique:pasien,nik,' . $this->idPasien;
        }

        $this->validate($rules);

        if ($this->modeEdit) {
            $pasien = Pasien::find($this->idPasien);
            $pasien->update([
                'nik' => $this->nik,
                'nama_lengkap' => $this->nama_lengkap,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
                'alamat_lengkap' => $this->alamat_lengkap,
                'golongan_darah' => $this->golongan_darah,
                'no_bpjs' => $this->no_bpjs,
                'no_telepon_darurat' => $this->no_telepon_darurat,
                'nama_kontak_darurat' => $this->nama_kontak_darurat,
            ]);
            session()->flash('pesan', 'Data pasien berhasil diperbarui.');
        } else {
            // Generate No Rekam Medis (RM-YYYYMM-XXX)
            $prefix = 'RM-' . date('Ym') . '-';
            $lastPasien = Pasien::where('no_rekam_medis', 'like', $prefix . '%')->orderBy('no_rekam_medis', 'desc')->first();
            $lastNumber = $lastPasien ? intval(substr($lastPasien->no_rekam_medis, -3)) : 0;
            $newNoRM = $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            Pasien::create([
                'no_rekam_medis' => $newNoRM,
                'nik' => $this->nik,
                'nama_lengkap' => $this->nama_lengkap,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
                'alamat_lengkap' => $this->alamat_lengkap,
                'golongan_darah' => $this->golongan_darah,
                'no_bpjs' => $this->no_bpjs,
                'no_telepon_darurat' => $this->no_telepon_darurat,
                'nama_kontak_darurat' => $this->nama_kontak_darurat,
            ]);
            session()->flash('pesan', 'Pasien baru berhasil ditambahkan: ' . $newNoRM);
        }

        $this->tampilkanModal = false;
        $this->resetForm();
    }

    public function hapus($id)
    {
        Pasien::find($id)->delete();
        session()->flash('pesan', 'Data pasien berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->reset(['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_lengkap', 'golongan_darah', 'no_bpjs', 'no_telepon_darurat', 'nama_kontak_darurat', 'idPasien', 'modeEdit']);
    }
}