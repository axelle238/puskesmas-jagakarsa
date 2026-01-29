<?php

namespace App\Livewire\Pegawai;

use App\Models\Pegawai;
use App\Models\Pengguna;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

#[Title('Manajemen Pegawai')]
class DaftarPegawai extends Component
{
    use WithPagination;

    public $cari = '';
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idPegawai;

    // Form Data Pengguna
    public $nama_lengkap, $email, $sandi, $peran, $no_telepon, $alamat;
    
    // Form Data Pegawai
    public $nip, $str, $sip, $jabatan, $spesialisasi, $tanggal_masuk;

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:pengguna,email',
        'peran' => 'required|in:admin,dokter,perawat,apoteker,pendaftaran',
        'nip' => 'nullable|string|unique:pegawai,nip',
        'jabatan' => 'required|string',
        'tanggal_masuk' => 'required|date',
    ];

    public function render()
    {
        $pegawais = Pegawai::with('pengguna')
            ->whereHas('pengguna', function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->cari . '%');
            })
            ->orWhere('nip', 'like', '%' . $this->cari . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pegawai.daftar-pegawai', ['pegawais' => $pegawais])
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
        $pegawai = Pegawai::with('pengguna')->find($id);
        
        $this->idPegawai = $id;
        // Data Pengguna
        $this->nama_lengkap = $pegawai->pengguna->nama_lengkap;
        $this->email = $pegawai->pengguna->email;
        $this->peran = $pegawai->pengguna->peran;
        $this->no_telepon = $pegawai->pengguna->no_telepon;
        $this->alamat = $pegawai->pengguna->alamat;

        // Data Pegawai
        $this->nip = $pegawai->nip;
        $this->str = $pegawai->str;
        $this->sip = $pegawai->sip;
        $this->jabatan = $pegawai->jabatan;
        $this->spesialisasi = $pegawai->spesialisasi;
        $this->tanggal_masuk = $pegawai->tanggal_masuk ? $pegawai->tanggal_masuk->format('Y-m-d') : null;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        // Validasi kustom untuk edit (ignore unique id)
        $rules = $this->rules;
        if ($this->modeEdit) {
            $pegawai = Pegawai::find($this->idPegawai);
            $rules['email'] = 'required|email|unique:pengguna,email,' . $pegawai->id_pengguna;
            $rules['nip'] = 'nullable|string|unique:pegawai,nip,' . $this->idPegawai;
        } else {
            $rules['sandi'] = 'required|min:6';
        }

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->modeEdit) {
                // Update Pengguna
                $pegawai = Pegawai::find($this->idPegawai);
                $pengguna = $pegawai->pengguna;
                
                $dataPengguna = [
                    'nama_lengkap' => $this->nama_lengkap,
                    'email' => $this->email,
                    'peran' => $this->peran,
                    'no_telepon' => $this->no_telepon,
                    'alamat' => $this->alamat,
                ];
                
                if (!empty($this->sandi)) {
                    $dataPengguna['sandi'] = Hash::make($this->sandi);
                }
                
                $pengguna->update($dataPengguna);

                // Update Pegawai
                $pegawai->update([
                    'nip' => $this->nip,
                    'str' => $this->str,
                    'sip' => $this->sip,
                    'jabatan' => $this->jabatan,
                    'spesialisasi' => $this->spesialisasi,
                    'tanggal_masuk' => $this->tanggal_masuk,
                ]);

                session()->flash('pesan', 'Data pegawai berhasil diperbarui.');

            } else {
                // Buat Pengguna Baru
                $pengguna = Pengguna::create([
                    'nama_lengkap' => $this->nama_lengkap,
                    'email' => $this->email,
                    'sandi' => Hash::make($this->sandi),
                    'peran' => $this->peran,
                    'no_telepon' => $this->no_telepon,
                    'alamat' => $this->alamat,
                ]);

                // Buat Profil Pegawai
                Pegawai::create([
                    'id_pengguna' => $pengguna->id,
                    'nip' => $this->nip,
                    'str' => $this->str,
                    'sip' => $this->sip,
                    'jabatan' => $this->jabatan,
                    'spesialisasi' => $this->spesialisasi,
                    'tanggal_masuk' => $this->tanggal_masuk,
                ]);

                session()->flash('pesan', 'Pegawai baru berhasil ditambahkan.');
            }
        });

        $this->tampilkanModal = false;
        $this->resetForm();
    }

    public function hapus($id)
    {
        $pegawai = Pegawai::find($id);
        $idPengguna = $pegawai->id_pengguna;
        
        $pegawai->delete();
        Pengguna::find($idPengguna)->delete(); // Hapus akun login juga
        
        session()->flash('pesan', 'Data pegawai dan akun login berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->reset([
            'nama_lengkap', 'email', 'sandi', 'peran', 'no_telepon', 'alamat',
            'nip', 'str', 'sip', 'jabatan', 'spesialisasi', 'tanggal_masuk',
            'idPegawai', 'modeEdit'
        ]);
    }
}
