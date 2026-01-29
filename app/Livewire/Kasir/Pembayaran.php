<?php

namespace App\Livewire\Kasir;

use App\Models\RekamMedis;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Kasir & Billing')]
class Pembayaran extends Component
{
    public $rekam_medis_id;
    public $tagihan_aktif;
    public $metode_bayar = 'tunai';
    public $jumlah_bayar = 0;

    public function mount()
    {
        //
    }

    public function pilihPasien($idRM)
    {
        $this->rekam_medis_id = $idRM;
        $this->generateTagihan($idRM);
    }

    public function generateTagihan($idRM)
    {
        $rm = RekamMedis::with(['tindakanDetail', 'resepDetail'])->find($idRM);
        
        // Cek jika sudah ada tagihan
        $existing = Tagihan::where('id_rekam_medis', $idRM)->first();
        if ($existing) {
            $this->tagihan_aktif = $existing;
            return;
        }

        // Hitung Total
        $total = 0;
        $details = [];

        // 1. Biaya Tindakan
        foreach ($rm->tindakanDetail as $tindakan) {
            $details[] = [
                'item' => $tindakan->nama_tindakan,
                'kategori' => 'Tindakan Medis',
                'harga' => $tindakan->pivot->biaya_saat_ini,
                'jumlah' => 1,
                'subtotal' => $tindakan->pivot->biaya_saat_ini
            ];
            $total += $tindakan->pivot->biaya_saat_ini;
        }

        // 2. Biaya Obat
        foreach ($rm->resepDetail as $obat) {
            $biayaObat = $obat->harga_satuan * $obat->pivot->jumlah;
            $details[] = [
                'item' => $obat->nama_obat,
                'kategori' => 'Farmasi',
                'harga' => $obat->harga_satuan,
                'jumlah' => $obat->pivot->jumlah,
                'subtotal' => $biayaObat
            ];
            $total += $biayaObat;
        }

        // 3. Biaya Admin (Flat)
        $details[] = [
            'item' => 'Biaya Administrasi',
            'kategori' => 'Administrasi',
            'harga' => 5000,
            'jumlah' => 1,
            'subtotal' => 5000
        ];
        $total += 5000;

        DB::transaction(function () use ($rm, $total, $details) {
            $tagihan = Tagihan::create([
                'no_tagihan' => 'INV-' . date('Ymd') . '-' . $rm->id,
                'id_rekam_medis' => $rm->id,
                'total_biaya' => $total,
                'status_bayar' => 'belum_lunas'
            ]);

            foreach ($details as $d) {
                DetailTagihan::create([
                    'id_tagihan' => $tagihan->id,
                    'item' => $d['item'],
                    'kategori' => $d['kategori'],
                    'jumlah' => $d['jumlah'],
                    'harga_satuan' => $d['harga'],
                    'subtotal' => $d['subtotal']
                ]);
            }
            
            $this->tagihan_aktif = $tagihan;
        });
        
        // Refresh data
        $this->tagihan_aktif = Tagihan::where('id_rekam_medis', $idRM)->first();
    }

    public function prosesBayar()
    {
        $this->validate([
            'jumlah_bayar' => 'required|numeric|min:' . $this->tagihan_aktif->total_biaya
        ]);

        $kembalian = $this->jumlah_bayar - $this->tagihan_aktif->total_biaya;

        $this->tagihan_aktif->update([
            'jumlah_bayar' => $this->jumlah_bayar,
            'kembalian' => $kembalian,
            'metode_bayar' => $this->metode_bayar,
            'status_bayar' => 'lunas'
        ]);

        session()->flash('pesan', 'Pembayaran berhasil. Kembalian: Rp ' . number_format($kembalian));
    }

    public function render()
    {
        // Cari pasien yang status antriannya "selesai" (medis & farmasi beres) tapi belum bayar lunas
        // Simplifikasi: Ambil rekam medis hari ini
        $antrian_kasir = RekamMedis::whereDate('created_at', today())
            ->whereDoesntHave('tagihan', function($q) {
                $q->where('status_bayar', 'lunas');
            })
            ->with('pasien')
            ->get();

        return view('livewire.kasir.pembayaran', [
            'antrian_kasir' => $antrian_kasir
        ])->layout('components.layouts.admin');
    }
}
