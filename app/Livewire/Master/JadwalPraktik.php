<?php

namespace App\Livewire\Master;

use App\Models\JadwalDokter;
use App\Models\Pegawai;
use App\Models\Poli;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class JadwalPraktik
 * Mengatur jadwal dokter per poli per hari.
 */
class JadwalPraktik extends Component
{
    use WithPagination;

    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idJadwalDiedit = null;

    // Form
    public $id_dokter;
    public $id_poli;
    public $hari;
    public $jam_mulai;
    public $jam_selesai;
    public $kuota_pasien;
    public $aktif = true;

    protected $rules = [
        'id_dokter' => 'required|exists:pegawai,id',
        'id_poli' => 'required|exists:poli,id',
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
        'kuota_pasien' => 'required|numeric|min:1',
    ];

    public function tambah()
    {
        $this->resetForm();
        $this->tampilkanModal = true;
        $this->modeEdit = false;
    }

    public function edit($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);
        $this->idJadwalDiedit = $id;
        $this->id_dokter = $jadwal->id_dokter;
        $this->id_poli = $jadwal->id_poli;
        $this->hari = $jadwal->hari;
        $this->jam_mulai = $jadwal->jam_mulai;
        $this->jam_selesai = $jadwal->jam_selesai;
        $this->kuota_pasien = $jadwal->kuota_pasien;
        $this->aktif = $jadwal->aktif;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate();

        // Cek tabrakan jadwal (Opsional, skip dulu untuk MVP)

        $data = [
            'id_dokter' => $this->id_dokter,
            'id_poli' => $this->id_poli,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'kuota_pasien' => $this->kuota_pasien,
            'aktif' => $this->aktif,
        ];

        if ($this->modeEdit) {
            JadwalDokter::find($this->idJadwalDiedit)->update($data);
            session()->flash('sukses', 'Jadwal berhasil diperbarui.');
        } else {
            JadwalDokter::create($data);
            session()->flash('sukses', 'Jadwal baru berhasil ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        JadwalDokter::find($id)->delete();
        session()->flash('sukses', 'Jadwal berhasil dihapus.');
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['id_dokter', 'id_poli', 'hari', 'jam_mulai', 'jam_selesai', 'kuota_pasien', 'aktif', 'idJadwalDiedit']);
    }

    public function render()
    {
        // Ambil dokter saja
        $dokterList = Pegawai::with('pengguna')
            ->whereHas('pengguna', function($q) {
                $q->where('peran', 'dokter');
            })->get();
            
        $poliList = Poli::where('aktif', true)->get();

        $jadwal = JadwalDokter::with(['dokter.pengguna', 'poli'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->paginate(10);

        return view('livewire.master.jadwal-praktik', [
            'dataJadwal' => $jadwal,
            'dokterList' => $dokterList,
            'poliList' => $poliList
        ])->layout('components.layouts.admin', ['title' => 'Jadwal Praktik']);
    }
}