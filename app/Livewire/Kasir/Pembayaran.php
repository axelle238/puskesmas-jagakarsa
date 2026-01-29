<?php

namespace App\Livewire\Kasir;

use App\Models\DetailResep;
use App\Models\DetailTagihan;
use App\Models\PermintaanLab;
use App\Models\RekamMedis;
use App\Models\Tagihan;
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

        // 1. Biaya Pendaftaran / Jasa Dokter
        $biayaDokter = 15000; // Flat rate contoh
        $this->detailTagihanList[] = [
            'item' => 'Jasa Dokter & Administrasi',
            'kategori' => 'Tindakan',
            'jumlah' => 1,
            'harga' => $biayaDokter,
            'subtotal' => $biayaDokter
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
                'item' => 'Pemeriksaan Lab: ' . $l->no_permintaan,
                'kategori' => 'Laboratorium',
                'jumlah' => 1,
                'harga' => $l->biaya_lab,
                'subtotal' => $l->biaya_lab
            ];
        }

        // Hitung Total
        $this->totalTagihan = array_sum(array_column($this->detailTagihanList, 'subtotal'));
        $this->jumlah_bayar = 0;
        $this->kembalian = 0;
        
        // Cek BPJS (Jika pasien punya no BPJS, mungkin gratis)
        if ($this->rmTerpilih->pasien->no_bpjs) {
            $this->metode_bayar = 'bpjs';
            // Logic diskon BPJS bisa ditambahkan disini
        } else {
            $this->metode_bayar = 'tunai';
        }

        $this->tampilkanModal = true;
    }

    public function prosesBayar()
    {
        if ($this->metode_bayar != 'bpjs' && $this->jumlah_bayar < $this->totalTagihan) {
            session()->flash('error', 'Jumlah pembayaran kurang.');
            return;
        }

        // 1. Buat Header Tagihan
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

        // 2. Simpan Detail
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

        // 3. Update Status Antrian Akhir
        if ($this->rmTerpilih->antrian) {
            $this->rmTerpilih->antrian->status = 'selesai'; 
            // Benar-benar selesai siklusnya
            $this->rmTerpilih->antrian->save();
        }

        session()->flash('sukses', 'Pembayaran berhasil diproses. Invoice #' . $tagihan->no_tagihan);
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
        // Cari pasien yang belum bayar
        // Logic: Antrian hari ini, status 'selesai' (dari poli/farmasi), tapi belum ada tagihan
        $antrianBayar = RekamMedis::whereDate('created_at', today())
            ->whereDoesntHave('tagihan') // Belum ada tagihan
            ->whereHas('antrian', function($q) {
                // Yang sudah dilayani dokter
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
