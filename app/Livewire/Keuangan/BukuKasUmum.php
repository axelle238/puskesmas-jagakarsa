<?php

namespace App\Livewire\Keuangan;

use App\Models\RealisasiAnggaran;
use App\Models\Tagihan;
use Livewire\Component;
use Livewire\WithPagination;

class BukuKasUmum extends Component
{
    use WithPagination;

    public $bulan;
    public $tahun;

    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }

    public function render()
    {
        // 1. Ambil Pemasukan (Dari Tagihan Lunas)
        $pemasukan = Tagihan::where('status_bayar', 'lunas')
            ->whereMonth('waktu_bayar', $this->bulan)
            ->whereYear('waktu_bayar', $this->tahun)
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->waktu_bayar,
                    'jenis' => 'masuk',
                    'keterangan' => 'Pembayaran Pasien - ' . ($item->registrasi->pasien->nama_lengkap ?? 'Umum') . ' (' . $item->kode_invoice . ')',
                    'jumlah' => $item->total_bayar,
                    'ref_id' => $item->id,
                    'tipe_transaksi' => 'Pelayanan Medis'
                ];
            });

        // 2. Ambil Pengeluaran (Dari Realisasi Anggaran PTP)
        $pengeluaran = RealisasiAnggaran::whereMonth('tanggal_realisasi', $this->bulan)
            ->whereYear('tanggal_realisasi', $this->tahun)
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_realisasi,
                    'jenis' => 'keluar',
                    'keterangan' => $item->uraian_pengeluaran . ' (' . ($item->kegiatan->nama_kegiatan ?? 'Kegiatan') . ')',
                    'jumlah' => $item->jumlah,
                    'ref_id' => $item->id,
                    'tipe_transaksi' => 'Belanja Operasional'
                ];
            });

        // 3. Gabung dan Urutkan
        $transaksi = $pemasukan->merge($pengeluaran)->sortByDesc('tanggal');

        // 4. Hitung Saldo
        $totalMasuk = $pemasukan->sum('jumlah');
        $totalKeluar = $pengeluaran->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return view('livewire.keuangan.buku-kas-umum', [
            'transaksi' => $transaksi,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldo' => $saldo
        ])->layout('components.layouts.admin', ['title' => 'Buku Kas Umum (Keuangan)']);
    }
}
