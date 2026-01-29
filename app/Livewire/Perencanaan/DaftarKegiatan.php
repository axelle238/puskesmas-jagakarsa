<?php

namespace App\Livewire\Perencanaan;

use App\Models\KegiatanPerencanaan;
use App\Models\Pegawai;
use App\Models\RealisasiAnggaran;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class DaftarKegiatan extends Component
{
    use WithPagination, WithFileUploads;

    // Filter
    public $tahun;
    public $sumber_dana = '';
    public $cari = '';

    // State UI
    public $tampilkanModal = false;
    public $tampilkanModalRealisasi = false;
    public $modeEdit = false;
    public $kegiatanTerpilih = null; // Untuk detail/realisasi

    // Form Kegiatan
    public $id_kegiatan;
    public $nama_kegiatan;
    public $tujuan;
    public $sasaran;
    public $target_kinerja;
    public $pagu_anggaran;
    public $anggaran_disetujui;
    public $waktu_pelaksanaan;
    public $penanggung_jawab_id;
    public $status = 'usulan';
    public $sumber_dana_input = 'APBD'; // Default

    // Form Realisasi
    public $tanggal_realisasi;
    public $jumlah_realisasi;
    public $uraian_realisasi;
    public $bukti_dokumen;

    protected function rules()
    {
        return [
            'nama_kegiatan' => 'required',
            'tahun' => 'required|numeric',
            'pagu_anggaran' => 'required|numeric',
        ];
    }

    public function mount()
    {
        $this->tahun = date('Y');
    }

    // --- LOGIC KEGIATAN ---

    public function tambah()
    {
        $this->resetForm();
        $this->modeEdit = false;
        $this->tampilkanModal = true;
    }

    public function edit($id)
    {
        $k = KegiatanPerencanaan::find($id);
        $this->id_kegiatan = $id;
        $this->nama_kegiatan = $k->nama_kegiatan;
        $this->tahun = $k->tahun_anggaran;
        $this->sumber_dana_input = $k->sumber_dana;
        $this->tujuan = $k->tujuan;
        $this->sasaran = $k->sasaran;
        $this->target_kinerja = $k->target_kinerja;
        $this->pagu_anggaran = $k->pagu_anggaran;
        $this->anggaran_disetujui = $k->anggaran_disetujui;
        $this->waktu_pelaksanaan = $k->waktu_pelaksanaan;
        $this->penanggung_jawab_id = $k->penanggung_jawab_id;
        $this->status = $k->status;

        $this->modeEdit = true;
        $this->tampilkanModal = true;
    }

    public function simpan()
    {
        $this->validate([
            'nama_kegiatan' => 'required',
            'pagu_anggaran' => 'required|numeric',
            'sumber_dana_input' => 'required'
        ]);

        $data = [
            'tahun_anggaran' => $this->tahun, // Menggunakan tahun filter/aktif sebagai basis input
            'sumber_dana' => $this->sumber_dana_input,
            'nama_kegiatan' => $this->nama_kegiatan,
            'tujuan' => $this->tujuan,
            'sasaran' => $this->sasaran,
            'target_kinerja' => $this->target_kinerja,
            'pagu_anggaran' => $this->pagu_anggaran,
            'anggaran_disetujui' => $this->anggaran_disetujui,
            'waktu_pelaksanaan' => $this->waktu_pelaksanaan,
            'penanggung_jawab_id' => $this->penanggung_jawab_id,
            'status' => $this->status,
        ];

        if ($this->modeEdit) {
            KegiatanPerencanaan::find($this->id_kegiatan)->update($data);
            session()->flash('sukses', 'Kegiatan berhasil diperbarui.');
        } else {
            KegiatanPerencanaan::create($data);
            session()->flash('sukses', 'Kegiatan baru berhasil ditambahkan.');
        }

        $this->tutupModal();
    }

    public function hapus($id)
    {
        KegiatanPerencanaan::find($id)->delete();
        session()->flash('sukses', 'Kegiatan dihapus.');
    }

    // --- LOGIC REALISASI ---

    public function kelolaRealisasi($id)
    {
        $this->kegiatanTerpilih = KegiatanPerencanaan::with('realisasi.penginput')->find($id);
        $this->tampilkanModalRealisasi = true;
        $this->resetFormRealisasi();
    }

    public function simpanRealisasi()
    {
        $this->validate([
            'tanggal_realisasi' => 'required|date',
            'jumlah_realisasi' => 'required|numeric|min:1',
            'uraian_realisasi' => 'required',
            'bukti_dokumen' => 'nullable|file|max:2048' // 2MB
        ]);

        // Cek apakah melebihi pagu
        $sisa = $this->kegiatanTerpilih->sisa_anggaran;
        if ($this->jumlah_realisasi > $sisa) {
            $this->addError('jumlah_realisasi', 'Jumlah realisasi melebihi sisa anggaran tersedai (Rp ' . number_format($sisa, 0) . ').');
            return;
        }

        $path = null;
        if ($this->bukti_dokumen) {
            $path = $this->bukti_dokumen->store('bukti_realisasi', 'public');
        }

        RealisasiAnggaran::create([
            'kegiatan_id' => $this->kegiatanTerpilih->id,
            'tanggal_realisasi' => $this->tanggal_realisasi,
            'jumlah' => $this->jumlah_realisasi,
            'uraian_pengeluaran' => $this->uraian_realisasi,
            'bukti_dokumen' => $path,
            'diinput_oleh' => auth()->id()
        ]);

        // Refresh data
        $this->kegiatanTerpilih = KegiatanPerencanaan::with('realisasi.penginput')->find($this->kegiatanTerpilih->id);
        
        $this->resetFormRealisasi();
        session()->flash('sukses_realisasi', 'Realisasi berhasil dicatat.');
    }

    public function hapusRealisasi($idRealisasi)
    {
        RealisasiAnggaran::find($idRealisasi)->delete();
        $this->kegiatanTerpilih = KegiatanPerencanaan::with('realisasi.penginput')->find($this->kegiatanTerpilih->id);
        session()->flash('sukses_realisasi', 'Data realisasi dihapus.');
    }

    // --- HELPER ---

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->tampilkanModalRealisasi = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'nama_kegiatan', 'tujuan', 'sasaran', 'target_kinerja', 
            'pagu_anggaran', 'anggaran_disetujui', 'waktu_pelaksanaan', 
            'penanggung_jawab_id', 'status', 'id_kegiatan'
        ]);
        $this->sumber_dana_input = 'APBD';
    }

    public function resetFormRealisasi()
    {
        $this->reset(['tanggal_realisasi', 'jumlah_realisasi', 'uraian_realisasi', 'bukti_dokumen']);
        $this->tanggal_realisasi = date('Y-m-d');
    }

    public function render()
    {
        $query = KegiatanPerencanaan::query();

        if ($this->tahun) {
            $query->where('tahun_anggaran', $this->tahun);
        }
        if ($this->sumber_dana) {
            $query->where('sumber_dana', $this->sumber_dana);
        }
        if ($this->cari) {
            $query->where('nama_kegiatan', 'like', '%' . $this->cari . '%');
        }

        $kegiatan = $query->with('penanggungJawab')->orderBy('created_at', 'desc')->paginate(10);
        $pegawaiList = Pegawai::where('status_aktif', 'Aktif')->get();

        // Statistik Dashboard Kecil
        $stats = [
            'total_pagu' => KegiatanPerencanaan::where('tahun_anggaran', $this->tahun)->sum('pagu_anggaran'),
            'total_disetujui' => KegiatanPerencanaan::where('tahun_anggaran', $this->tahun)->sum('anggaran_disetujui'),
            'total_realisasi' => RealisasiAnggaran::whereHas('kegiatan', function($q) {
                $q->where('tahun_anggaran', $this->tahun);
            })->sum('jumlah')
        ];

        return view('livewire.perencanaan.daftar-kegiatan', [
            'kegiatan' => $kegiatan,
            'pegawaiList' => $pegawaiList,
            'stats' => $stats
        ])->layout('components.layouts.admin', ['title' => 'Manajemen Perencanaan (PTP)']);
    }
}
