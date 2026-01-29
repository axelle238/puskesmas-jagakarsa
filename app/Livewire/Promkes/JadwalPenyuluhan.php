<?php

namespace App\Livewire\Promkes;

use App\Models\JadwalPenyuluhan as ModelJadwal;
use App\Models\LaporanPenyuluhan;
use App\Models\Pegawai;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class JadwalPenyuluhan extends Component
{
    use WithPagination, WithFileUploads;

    public $bulan;
    public $tahun;
    public $tampilkanModal = false;
    public $tampilkanModalLaporan = false;
    public $modeEdit = false;
    public $jadwalTerpilih = null;

    // Form Jadwal
    public $id_jadwal;
    public $topik_kegiatan;
    public $lokasi;
    public $tanggal_kegiatan;
    public $jam_mulai;
    public $jam_selesai;
    public $sasaran_peserta;
    public $id_petugas;
    public $status = 'rencana';

    // Form Laporan
    public $jumlah_peserta_hadir;
    public $materi_disampaikan;
    public $hasil_evaluasi;
    public $foto_dokumentasi;

    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }

    // --- CRUD JADWAL ---

    public function tambah()
    {
        $this->resetForm();
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $j = ModelJadwal::find($id);
        $this->id_jadwal = $id;
        $this->topik_kegiatan = $j->topik_kegiatan;
        $this->lokasi = $j->lokasi;
        $this->tanggal_kegiatan = $j->tanggal_kegiatan->format('Y-m-d');
        $this->jam_mulai = $j->jam_mulai ? $j->jam_mulai->format('H:i') : '';
        $this->jam_selesai = $j->jam_selesai ? $j->jam_selesai->format('H:i') : '';
        $this->sasaran_peserta = $j->sasaran_peserta;
        $this->id_petugas = $j->id_petugas;
        $this->status = $j->status;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate([
            'topik_kegiatan' => 'required',
            'lokasi' => 'required',
            'tanggal_kegiatan' => 'required|date',
            'jam_mulai' => 'required',
            'id_petugas' => 'required'
        ]);

        $data = [
            'topik_kegiatan' => $this->topik_kegiatan,
            'lokasi' => $this->lokasi,
            'tanggal_kegiatan' => $this->tanggal_kegiatan,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai ?: null,
            'sasaran_peserta' => $this->sasaran_peserta,
            'id_petugas' => $this->id_petugas,
            'status' => $this->status
        ];

        if ($this->modeEdit) {
            ModelJadwal::find($this->id_jadwal)->update($data);
            session()->flash('sukses', 'Jadwal diperbarui.');
        } else {
            ModelJadwal::create($data);
            session()->flash('sukses', 'Jadwal kegiatan berhasil ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        ModelJadwal::find($id)->delete();
        session()->flash('sukses', 'Jadwal dihapus.');
    }

    // --- PELAPORAN ---

    public function lapor($id)
    {
        $this->jadwalTerpilih = ModelJadwal::with('laporan')->find($id);
        $this->tampilkanModalLaporan = true;
        
        // Isi form jika sudah ada laporan sebelumnya
        if ($this->jadwalTerpilih->laporan) {
            $l = $this->jadwalTerpilih->laporan;
            $this->jumlah_peserta_hadir = $l->jumlah_peserta_hadir;
            $this->materi_disampaikan = $l->materi_disampaikan;
            $this->hasil_evaluasi = $l->hasil_evaluasi;
        } else {
            $this->reset(['jumlah_peserta_hadir', 'materi_disampaikan', 'hasil_evaluasi', 'foto_dokumentasi']);
        }
    }

    public function simpanLaporan()
    {
        $this->validate([
            'jumlah_peserta_hadir' => 'required|numeric',
            'materi_disampaikan' => 'required',
            'foto_dokumentasi' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($this->foto_dokumentasi) {
            $path = $this->foto_dokumentasi->store('laporan_promkes', 'public');
        } elseif ($this->jadwalTerpilih->laporan) {
            $path = $this->jadwalTerpilih->laporan->foto_dokumentasi;
        }

        LaporanPenyuluhan::updateOrCreate(
            ['jadwal_id' => $this->jadwalTerpilih->id],
            [
                'jumlah_peserta_hadir' => $this->jumlah_peserta_hadir,
                'materi_disampaikan' => $this->materi_disampaikan,
                'hasil_evaluasi' => $this->hasil_evaluasi,
                'foto_dokumentasi' => $path,
                'diinput_oleh' => auth()->id()
            ]
        );

        // Update status jadwal jadi terlaksana otomatis
        $this->jadwalTerpilih->update(['status' => 'terlaksana']);

        $this->tutupModal();
        session()->flash('sukses', 'Laporan kegiatan berhasil disimpan.');
    }

    // --- HELPER ---

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->tampilkanModalLaporan = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'topik_kegiatan', 'lokasi', 'tanggal_kegiatan', 'jam_mulai', 'jam_selesai',
            'sasaran_peserta', 'id_petugas', 'status', 'id_jadwal',
            'jumlah_peserta_hadir', 'materi_disampaikan', 'hasil_evaluasi', 'foto_dokumentasi'
        ]);
        $this->status = 'rencana';
    }

    public function render()
    {
        $jadwal = ModelJadwal::with(['petugas', 'laporan'])
            ->whereMonth('tanggal_kegiatan', $this->bulan)
            ->whereYear('tanggal_kegiatan', $this->tahun)
            ->orderBy('tanggal_kegiatan')
            ->get(); // Tidak perlu paginate untuk kalender/list bulanan agar terlihat semua tgl

        $pegawaiList = Pegawai::where('status_aktif', 'Aktif')->get();

        return view('livewire.promkes.jadwal-penyuluhan', [
            'jadwalKegiatan' => $jadwal,
            'pegawaiList' => $pegawaiList
        ])->layout('components.layouts.admin', ['title' => 'Promosi Kesehatan']);
    }
}
