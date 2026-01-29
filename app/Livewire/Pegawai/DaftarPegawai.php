<?php

namespace App\Livewire\Pegawai;

use App\Models\Pegawai;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class DaftarPegawai
 * Menangani manajemen data pegawai dan akun pengguna terkait.
 */
class DaftarPegawai extends Component
{
    use WithPagination;

    // Filter & Pencarian
    public $cari = '';

    // State Modal
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idPegawaiDiedit = null;
    public $idPenggunaTerkait = null;

    // Form Fields
    public $nama_lengkap;
    public $email;
    public $nip;
    public $nik;
    public $sip;
    public $str; // Tambahan STR
    public $jabatan;
    public $status_kepegawaian; // Tambahan
    public $tempat_lahir; // Tambahan
    public $tanggal_lahir; // Tambahan
    public $jenis_kelamin; // Tambahan
    public $pendidikan_terakhir; // Tambahan
    public $no_telepon;
    public $alamat;
    public $peran = 'perawat';
    public $sandi_baru;

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:pengguna,email',
        'nip' => 'required|string|unique:pegawai,nip',
        'nik' => 'required|numeric|digits:16', // Sekarang NIK wajib dan ada di DB
        'jabatan' => 'required|string',
        'peran' => 'required|in:admin,dokter,perawat,apoteker,pendaftaran,kasir,analis,kapus,superadmin',
        'status_kepegawaian' => 'required',
    ];

    // ...

    public function edit($id)
    {
        $pegawai = Pegawai::with('pengguna')->findOrFail($id);
        
        $this->idPegawaiDiedit = $id;
        $this->idPenggunaTerkait = $pegawai->id_pengguna;
        
        // Data Pegawai
        $this->nip = $pegawai->nip;
        $this->nik = $pegawai->nik;
        $this->sip = $pegawai->sip;
        $this->str = $pegawai->str;
        $this->jabatan = $pegawai->jabatan;
        $this->status_kepegawaian = $pegawai->status_kepegawaian;
        $this->tempat_lahir = $pegawai->tempat_lahir;
        $this->tanggal_lahir = $pegawai->tanggal_lahir;
        $this->jenis_kelamin = $pegawai->jenis_kelamin;
        $this->pendidikan_terakhir = $pegawai->pendidikan_terakhir;
        
        // Data Pengguna
        if ($pegawai->pengguna) {
            $this->nama_lengkap = $pegawai->pengguna->nama_lengkap;
            $this->email = $pegawai->pengguna->email;
            $this->no_telepon = $pegawai->pengguna->no_telepon;
            $this->alamat = $pegawai->pengguna->alamat;
            $this->peran = $pegawai->pengguna->peran;
        }

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        // Validasi Email Unik & NIP Unik
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['email'] = 'required|email|unique:pengguna,email,' . $this->idPenggunaTerkait;
            $rules['nip'] = 'required|string|unique:pegawai,nip,' . $this->idPegawaiDiedit;
            unset($rules['sandi']);
        }

        $this->validate($rules);

        // 1. Simpan/Update Data Pengguna
        $dataPengguna = [
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'peran' => $this->peran,
            'no_telepon' => $this->no_telepon,
            'alamat' => $this->alamat,
        ];

        if ($this->sandi_baru) {
            $dataPengguna['sandi'] = Hash::make($this->sandi_baru);
        } elseif (!$this->modeEdit) {
            $dataPengguna['sandi'] = Hash::make('12345678');
        }

        if ($this->modeEdit) {
            $pengguna = Pengguna::find($this->idPenggunaTerkait);
            $pengguna->update($dataPengguna);
        } else {
            $pengguna = Pengguna::create($dataPengguna);
        }

        // 2. Simpan/Update Data Pegawai
        $dataPegawai = [
            'id_pengguna' => $pengguna->id,
            'nip' => $this->nip,
            'nik' => $this->nik,
            'sip' => $this->sip,
            'str' => $this->str,
            'jabatan' => $this->jabatan,
            'status_kepegawaian' => $this->status_kepegawaian,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
        ];

        if ($this->modeEdit) {
            Pegawai::find($this->idPegawaiDiedit)->update($dataPegawai);
            session()->flash('sukses', 'Data pegawai berhasil diperbarui.');
        } else {
            $dataPegawai['tanggal_masuk'] = now();
            Pegawai::create($dataPegawai);
            session()->flash('sukses', 'Pegawai baru berhasil ditambahkan. Sandi default: 12345678');
        }

        $this->tutupModal();
    }

    // ... (Hapus & Reset Form)

    private function resetForm()
    {
        $this->reset([
            'nama_lengkap', 'email', 'nip', 'nik', 'sip', 'str', 'jabatan', 
            'no_telepon', 'alamat', 'peran', 'sandi_baru', 'idPegawaiDiedit', 'idPenggunaTerkait',
            'status_kepegawaian', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'pendidikan_terakhir'
        ]);
    }

    public function render()
    {
        $pegawai = Pegawai::with('pengguna')
            ->whereHas('pengguna', function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->cari . '%');
            })
            ->orWhere('nip', 'like', '%' . $this->cari . '%')
            ->orWhere('jabatan', 'like', '%' . $this->cari . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pegawai.daftar-pegawai', [
            'dataPegawai' => $pegawai
        ])->layout('components.layouts.admin', ['title' => 'Manajemen Pegawai']);
    }
}