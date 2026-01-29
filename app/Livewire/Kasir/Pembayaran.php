<?php

namespace App\Livewire\Kasir;

use App\Models\DetailResep;
use App\Models\DetailTagihan;
use App\Models\PermintaanLab;
use App\Models\RekamMedis;
use App\Models\Tagihan;
use App\Models\TindakanMedis;
use Livewire\Component;

/**
 * Class Pembayaran
 * Modul Kasir untuk generate invoice dan proses pembayaran.
 */
class Pembayaran extends Component
{
    public $tampilkanModal = false;
    public $rmTerpilih;
    public $detailTagihanList = [];
    public $totalTagihan = 0;
    
    // Tambah Tindakan Manual
    public $tindakanTersedia = [];
    public $tindakanDipilihId;

    // Form Bayar
    public $jumlah_bayar = 0;
    public $metode_bayar = 'tunai';
    public $kembalian = 0;

    public function updatedJumlahBayar()
    {
        $this->kembalian = (float)$this->jumlah_bayar - (float)$this->totalTagihan;
    }

    public function buatTagihan($idRm)
    {
        $this->rmTerpilih = RekamMedis::with(['pasien', 'poli', 'dokter.pengguna'])->find($idRm);
        $this->detailTagihanList = [];
        $this->totalTagihan = 0;
        
        // Load Tindakan sesuai Poli
        $this->tindakanTersedia = TindakanMedis::where('id_poli', $this->rmTerpilih->id_poli)
            ->where('aktif', true)
            ->get();

        // 1. Biaya Jasa Dokter / Pendaftaran (Default)
        // Kita cari tindakan default jika ada, kalau tidak ada pakai flat
        // Asumsi: Ada tindakan 'Pemeriksaan Umum' di master. Jika tidak, pakai flat.
        $this->detailTagihanList[] = [
            'item' => 'Jasa Pelayanan Medis',
            'kategori' => 'Tindakan',
            'jumlah' => 1,
            'harga' => 15000, // Fallback price
            'subtotal' => 15000
        ];

        // 2. Biaya Obat (Farmasi)
        $detailResep = DetailResep::where('id_rekam_medis', $idRm)->get();
        foreach ($detailResep as $obat) {
            $subtotal = $obat->jumlah * $obat->harga_satuan_saat_ini;
            $this->detailTagihanList[] = [
                'item' => $obat->obat->nama_obat ?? 'Obat',
                'kategori' => 'Obat',
                'jumlah' => $obat->jumlah,
                'harga' => $obat->harga_satuan_saat_ini,
                'subtotal' => $subtotal
            ];
        }

        // 3. Biaya Lab
        $lab = PermintaanLab::where('id_rekam_medis', $idRm)->get();
        foreach ($lab as $l) {
            $this->detailTagihanList[] = [
                'item' => 'Lab: ' . $l->no_permintaan,
                'kategori' => 'Laboratorium',
                'jumlah' => 1,
                'harga' => $l->biaya_lab,
                'subtotal' => $l->biaya_lab
            ];
        }

        $this->hitungTotal();
        
        // Cek BPJS
        if ($this->rmTerpilih->pasien->no_bpjs) {
            $this->metode_bayar = 'bpjs';
        } else {
            $this->metode_bayar = 'tunai';
        }

        $this->tampilkanModal = true;
    }

    public function tambahTindakan()
    {
        if(!$this->tindakanDipilihId) return;

        $tindakan = TindakanMedis::find($this->tindakanDipilihId);
        if($tindakan) {
            $this->detailTagihanList[] = [
                'item' => $tindakan->nama_tindakan,
                'kategori' => 'Tindakan',
                'jumlah' => 1,
                'harga' => $tindakan->tarif,
                'subtotal' => $tindakan->tarif
            ];
            $this->hitungTotal();
        }
    }

    public function hapusItem($index)
    {
        unset($this->detailTagihanList[$index]);
        $this->detailTagihanList = array_values($this->detailTagihanList);
        $this->hitungTotal();
    }

    private function hitungTotal()
    {
        $this->totalTagihan = array_sum(array_column($this->detailTagihanList, 'subtotal'));
        $this->updatedJumlahBayar();
    }

    public function prosesBayar()
    {
        if ($this->metode_bayar != 'bpjs' && $this->jumlah_bayar < $this->totalTagihan) {
            session()->flash('error', 'Jumlah pembayaran kurang.');
            return;
        }

        $tagihan = Tagihan::create([
            'no_tagihan' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
            'id_rekam_medis' => $this->rmTerpilih->id,
            'id_kasir' => auth()->user()->pegawai->id ?? null,
            'total_biaya' => $this->totalTagihan,
            'jumlah_bayar' => $this->jumlah_bayar,
            'kembalian' => $this->kembalian,
            'status_bayar' => $this->metode_bayar == 'bpjs' ? 'gratis' : 'lunas',
            'metode_bayar' => $this->metode_bayar
        ]);

        foreach ($this->detailTagihanList as $d) {
            DetailTagihan::create([
                'id_tagihan' => $tagihan->id,
                'item' => $d['item'],
                'kategori' => $d['kategori'],
                'jumlah' => $d['jumlah'],
                'harga_satuan' => $d['harga'],
                'subtotal' => $d['subtotal']
            ]);
        }

        if ($this->rmTerpilih->antrian) {
            $this->rmTerpilih->antrian->status = 'selesai'; 
            $this->rmTerpilih->antrian->save();
        }

        session()->flash('sukses', 'Pembayaran berhasil. Invoice #' . $tagihan->no_tagihan);
        $this->tutupModal();
    }

    public function tutupModal()
    {
        $this->tampilkanModal = false;
        $this->rmTerpilih = null;
        $this->detailTagihanList = [];
    }

    public function render()
    {
        $antrianBayar = RekamMedis::whereDate('created_at', today())
            ->whereDoesntHave('tagihan')
            ->whereHas('antrian', function($q) {
                $q->whereIn('status', ['selesai', 'diperiksa']); 
            })
            ->with(['pasien', 'poli'])
            ->latest()
            ->get();

        return view('livewire.kasir.pembayaran', [
            'antrianBayar' => $antrianBayar
        ])->layout('components.layouts.admin', ['title' => 'Kasir & Pembayaran']);
    }
}