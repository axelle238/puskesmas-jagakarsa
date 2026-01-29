<?php

namespace App\Livewire\Pasien;

use App\Models\Pasien;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarPasien extends Component
{
    use WithPagination;

    // Search & Filter
    public $cari = '';
    
    // Modal State
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idPasienDiedit = null;

    // Form Fields
    public $nik;
    public $no_rekam_medis; // Auto-generated
    public $nama_lengkap;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $jenis_kelamin = 'L';
    public $alamat_lengkap;
    public $no_telepon;
    public $no_bpjs;

    protected $rules = [
        'nik' => 'required|numeric|digits:16|unique:pasien,nik',
        'nama_lengkap' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:L,P',
        'alamat_lengkap' => 'required|string',
    ];

    // Reset pagination saat search berubah
    public function updatedCari()
    {
        $this->resetPage();
    }

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
        $this->modeEdit = false;
        $this->no_rekam_medis = $this->generateNoRM();
    }

    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        $this->idPasienDiedit = $id;
        $this->nik = $pasien->nik;
        $this->no_rekam_medis = $pasien->no_rekam_medis;
        $this->nama_lengkap = $pasien->nama_lengkap;
        $this->tempat_lahir = $pasien->tempat_lahir;
        $this->tanggal_lahir = $pasien->tanggal_lahir; // Pastikan format Y-m-d
        $this->jenis_kelamin = $pasien->jenis_kelamin;
        $this->alamat_lengkap = $pasien->alamat_lengkap;
        $this->no_telepon = $pasien->no_telepon;
        $this->no_bpjs = $pasien->no_bpjs;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        // Validasi unik NIK kecuali punya diri sendiri saat edit
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['nik'] = 'required|numeric|digits:16|unique:pasien,nik,' . $this->idPasienDiedit;
        }

        $this->validate($rules);

        $data = [
            'nik' => $this->nik,
            'nama_lengkap' => $this->nama_lengkap,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat_lengkap' => $this->alamat_lengkap,
            'no_telepon' => $this->no_telepon,
            'no_bpjs' => $this->no_bpjs,
        ];

        if ($this->modeEdit) {
            Pasien::find($this->idPasienDiedit)->update($data);
            session()->flash('sukses', 'Data pasien berhasil diperbarui.');
        } else {
            $data['no_rekam_medis'] = $this->no_rekam_medis; // Include generated RM
            Pasien::create($data);
            session()->flash('sukses', 'Pasien baru berhasil ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        // Cek relasi dulu (Antrian/Rekam Medis)
        // Untuk MVP, kita soft delete atau cek manual.
        // Asumsi: Jika ada rekam medis, jangan hapus.
        try {
            Pasien::findOrFail($id)->delete();
            session()->flash('sukses', 'Data pasien berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data pasien.');
        }
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nik', 'no_rekam_medis', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_lengkap', 'no_telepon', 'no_bpjs', 'idPasienDiedit']);
    }

    private function generateNoRM()
    {
        // Format: RM-YYYYMM-XXXX
        $prefix = 'RM-' . date('Ym') . '-';
        $last = Pasien::where('no_rekam_medis', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->no_rekam_medis, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $pasien = Pasien::where('nama_lengkap', 'like', '%' . $this->cari . '%')
            ->orWhere('nik', 'like', '%' . $this->cari . '%')
            ->orWhere('no_rekam_medis', 'like', '%' . $this->cari . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pasien.daftar-pasien', [
            'dataPasien' => $pasien
        ])->layout('components.layouts.admin', ['title' => 'Manajemen Pasien']);
    }
}