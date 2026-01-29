<?php

namespace App\Livewire\Master;

use App\Models\JadwalDokter;
use App\Models\Pegawai;
use App\Models\Poli;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Jadwal Praktik')]
class JadwalPraktik extends Component
{
    public $tampilkanModal = false;
    public $modeEdit = false;
    public $idJadwal;

    // Form
    public $id_dokter, $id_poli, $hari, $jam_mulai, $jam_selesai, $kuota_pasien = 20, $aktif = true;

    protected $rules = [
        'id_dokter' => 'required',
        'id_poli' => 'required',
        'hari' => 'required',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
        'kuota_pasien' => 'required|numeric',
    ];

    public function render()
    {
        $jadwals = JadwalDokter::with(['dokter.pengguna', 'poli'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('livewire.master.jadwal-praktik', [
            'jadwals' => $jadwals,
            'dokters' => Pegawai::whereNotNull('sip')->with('pengguna')->get(),
            'polis' => Poli::all(),
        ])->layout('components.layouts.admin');
    }

    public function tambah()
    {
        $this->reset(['id_dokter', 'id_poli', 'hari', 'jam_mulai', 'jam_selesai', 'idJadwal']);
        $this->tampilkanModal = true;
        $this->modeEdit = false;
    }

    public function edit($id)
    {
        $jadwal = JadwalDokter::find($id);
        $this->idJadwal = $id;
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
            JadwalDokter::find($this->idJadwal)->update($data);
        } else {
            JadwalDokter::create($data);
        }

        $this->tampilkanModal = false;
        session()->flash('pesan', 'Jadwal berhasil disimpan.');
    }

    public function hapus($id)
    {
        JadwalDokter::find($id)->delete();
        session()->flash('pesan', 'Jadwal dihapus.');
    }
}
