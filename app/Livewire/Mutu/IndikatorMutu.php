<?php

namespace App\Livewire\Mutu;

use App\Models\HasilMutu;
use App\Models\IndikatorMutu as ModelIndikator;
use Livewire\Component;
use Livewire\WithPagination;

class IndikatorMutu extends Component
{
    use WithPagination;

    public $activeTab = 'input'; // input, master
    public $bulan;
    public $tahun;

    // Form Master Indikator
    public $judul_indikator;
    public $tipe = 'nasional';
    public $unit_terkait;
    public $target_capaian;
    public $modalMaster = false;

    // Form Input Hasil
    public $indikator_id;
    public $pembilang;
    public $penyebut;
    public $analisis;
    public $tindak_lanjut;
    public $modalInput = false;

    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }

    // --- MASTER DATA ---
    
    public function tambahMaster()
    {
        $this->reset(['judul_indikator', 'tipe', 'unit_terkait', 'target_capaian']);
        $this->modalMaster = true;
    }

    public function simpanMaster()
    {
        $this->validate([
            'judul_indikator' => 'required',
            'unit_terkait' => 'required',
            'target_capaian' => 'required|numeric|min:0|max:100',
        ]);

        ModelIndikator::create([
            'judul_indikator' => $this->judul_indikator,
            'tipe' => $this->tipe,
            'unit_terkait' => $this->unit_terkait,
            'target_capaian' => $this->target_capaian
        ]);

        $this->modalMaster = false;
        session()->flash('sukses', 'Indikator mutu berhasil ditambahkan.');
    }

    // --- INPUT CAPAIAN ---

    public function inputCapaian($idIndikator)
    {
        $this->indikator_id = $idIndikator;
        // Cek jika sudah ada input bulan ini
        $existing = HasilMutu::where('indikator_id', $idIndikator)
            ->where('bulan', "$this->bulan-$this->tahun")
            ->first();

        if ($existing) {
            $this->pembilang = $existing->pembilang;
            $this->penyebut = $existing->penyebut;
            $this->analisis = $existing->analisis;
            $this->tindak_lanjut = $existing->tindak_lanjut;
        } else {
            $this->reset(['pembilang', 'penyebut', 'analisis', 'tindak_lanjut']);
        }
        
        $this->modalInput = true;
    }

    public function simpanCapaian()
    {
        $this->validate([
            'pembilang' => 'required|numeric',
            'penyebut' => 'required|numeric|gt:0', // Denominator tidak boleh 0
        ]);

        $capaian = ($this->pembilang / $this->penyebut) * 100;

        HasilMutu::updateOrCreate(
            [
                'indikator_id' => $this->indikator_id,
                'bulan' => "$this->bulan-$this->tahun"
            ],
            [
                'pembilang' => $this->pembilang,
                'penyebut' => $this->penyebut,
                'capaian' => round($capaian, 2),
                'analisis' => $this->analisis,
                'tindak_lanjut' => $this->tindak_lanjut,
                'id_petugas' => auth()->user()->pegawai->id ?? 1
            ]
        );

        $this->modalInput = false;
        session()->flash('sukses', 'Data capaian berhasil disimpan.');
    }

    public function render()
    {
        $indikator = ModelIndikator::with(['hasil' => function($q) {
            $q->where('bulan', "$this->bulan-$this->tahun");
        }])->get();

        return view('livewire.mutu.indikator-mutu', [
            'dataIndikator' => $indikator
        ])->layout('components.layouts.admin', ['title' => 'Indikator Mutu']);
    }
}
