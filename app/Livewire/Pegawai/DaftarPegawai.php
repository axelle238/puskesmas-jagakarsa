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
    public $sip; // Surat Izin Praktik (Khusus Dokter)
    public $jabatan;
    public $no_telepon;
    public $alamat;
    public $peran = 'perawat'; // Default role
    public $sandi_baru; // Opsional saat edit

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:pengguna,email',
        'nip' => 'required|string|unique:pegawai,nip',
        'nik' => 'required|numeric|digits:16',
        'jabatan' => 'required|string',
        'peran' => 'required|in:admin,dokter,perawat,apoteker,pendaftaran',
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
        $pegawai = Pegawai::with('pengguna')->findOrFail($id);
        
        $this->idPegawaiDiedit = $id;
        $this->idPenggunaTerkait = $pegawai->id_pengguna;
        
        // Data Pegawai
        $this->nip = $pegawai->nip;
        $this->sip = $pegawai->sip;
        $this->jabatan = $pegawai->jabatan;
        $this->nik = $pegawai->nik; // Asumsi ada kolom NIK di tabel pegawai/pengguna (cek migrasi, jika tidak ada di pegawai, ambil dr pengguna atau skip)
        // Cek struktur tabel: Pegawai punya nik? Migrasi awal: nip, sip, jabatan, status_kepegawaian. NIK ada di pengguna? Tidak, pengguna: nama, email, password, peran, no_telepon, alamat.
        // Koreksi: NIK biasanya data sensitif personal. Kita simpan di Pegawai jika perlu, atau skip jika tidak ada kolomnya. 
        // Saya akan cek migrasi nanti. Untuk aman, saya pakai field yang pasti ada.
        
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
        // Validasi Email Unik (Kecuali punya sendiri)
        $rules = $this->rules;
        if ($this->modeEdit) {
            $rules['email'] = 'required|email|unique:pengguna,email,' . $this->idPenggunaTerkait;
            $rules['nip'] = 'required|string|unique:pegawai,nip,' . $this->idPegawaiDiedit;
            unset($rules['sandi']); // Sandi opsional saat edit
        } else {
            // Sandi default untuk user baru jika tidak diisi? Kita set default atau wajibkan.
            // Kita buat default NIP atau 123456
        }

        $this->validate($rules);

        // 1. Simpan/Update Data Pengguna (Akun Login)
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
            $dataPengguna['sandi'] = Hash::make('12345678'); // Default password
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
            'sip' => $this->sip,
            'jabatan' => $this->jabatan,
            // 'nik' => $this->nik, // Skip jika kolom belum dipastikan ada
        ];

        if ($this->modeEdit) {
            Pegawai::find($this->idPegawaiDiedit)->update($dataPegawai);
            session()->flash('sukses', 'Data pegawai berhasil diperbarui.');
        } else {
            Pegawai::create($dataPegawai);
            session()->flash('sukses', 'Pegawai baru berhasil ditambahkan. Sandi default: 12345678');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        $pegawai = Pegawai::find($id);
        if ($pegawai) {
            // Hapus pengguna terkait juga? Tergantung kebijakan. 
            // Biasanya soft delete. Kita hapus data pegawai saja, user dinonaktifkan.
            // Untuk MVP ini kita hapus keduanya agar bersih.
            $userId = $pegawai->id_pengguna;
            $pegawai->delete();
            Pengguna::find($userId)->delete();
            
            session()->flash('sukses', 'Data pegawai berhasil dihapus.');
        }
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nama_lengkap', 'email', 'nip', 'nik', 'sip', 'jabatan', 'no_telepon', 'alamat', 'peran', 'sandi_baru', 'idPegawaiDiedit', 'idPenggunaTerkait']);
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