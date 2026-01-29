<?php

namespace App\Livewire\Pegawai;

use App\Models\Presensi;
use Livewire\Component;
use Livewire\WithPagination;

class PresensiHarian extends Component
{
    use WithPagination;

    public $statusHariIni = null; // null, hadir, selesai
    public $presensiHariIni = null;

    public function mount()
    {
        $this->cekStatusHariIni();
    }

    public function cekStatusHariIni()
    {
        $pegawai = auth()->user()->pegawai;
        if (!$pegawai) return;

        $presensi = Presensi::where('id_pegawai', $pegawai->id)
            ->whereDate('tanggal', date('Y-m-d'))
            ->first();

        if ($presensi) {
            $this->presensiHariIni = $presensi;
            if ($presensi->jam_pulang) {
                $this->statusHariIni = 'selesai';
            } else {
                $this->statusHariIni = 'masuk';
            }
        } else {
            $this->statusHariIni = null; // Belum absen
        }
    }

    public function absenMasuk()
    {
        $pegawai = auth()->user()->pegawai;
        if (!$pegawai) {
            session()->flash('error', 'Akun anda tidak terhubung dengan data pegawai.');
            return;
        }

        Presensi::create([
            'id_pegawai' => $pegawai->id,
            'tanggal' => date('Y-m-d'),
            'jam_masuk' => now(),
            'status' => 'hadir'
        ]);

        $this->cekStatusHariIni();
        session()->flash('sukses', 'Berhasil melakukan absensi masuk. Selamat bekerja!');
    }

    public function absenPulang()
    {
        if ($this->presensiHariIni) {
            $this->presensiHariIni->update([
                'jam_pulang' => now()
            ]);
            $this->cekStatusHariIni();
            session()->flash('sukses', 'Berhasil absensi pulang. Hati-hati di jalan!');
        }
    }

    public function render()
    {
        $pegawai = auth()->user()->pegawai;
        
        $riwayat = collect([]);
        if ($pegawai) {
            $riwayat = Presensi::where('id_pegawai', $pegawai->id)
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
        }

        return view('livewire.pegawai.presensi-harian', [
            'riwayat' => $riwayat
        ])->layout('components.layouts.admin', ['title' => 'Presensi Harian']);
    }
}
