<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BpjsService
{
    // Simulasi Base URL & Credentials
    protected $baseUrl = 'https://dvlp.bpjs-kesehatan.go.id/v1/pcare';
    protected $consId = '12345';
    protected $secret = 'secret';

    /**
     * Simulasi Cek Peserta (GET /peserta/{nokartu})
     */
    public function cekPeserta($noKartu)
    {
        // Simulasi Logika Bisnis
        // 00xxx -> Aktif
        // 11xxx -> Penunggakan
        // 99xxx -> Tidak Aktif
        
        if (str_starts_with($noKartu, '00')) {
            return [
                'code' => 200,
                'response' => [
                    'nama' => 'PESERTA BPJS AKTIF',
                    'statusPeserta' => ['kode' => '0', 'keterangan' => 'AKTIF'],
                    'jenisPeserta' => ['kode' => '1', 'keterangan' => 'PBI'],
                    'providerPelayanan' => ['kode' => '0114B001', 'nama' => 'PUSKESMAS JAGAKARSA']
                ]
            ];
        } elseif (str_starts_with($noKartu, '11')) {
            return [
                'code' => 200,
                'response' => [
                    'nama' => 'PESERTA MENUNGGAK',
                    'statusPeserta' => ['kode' => '1', 'keterangan' => 'DENDA'],
                    'providerPelayanan' => ['kode' => '0114B001', 'nama' => 'PUSKESMAS JAGAKARSA']
                ]
            ];
        }

        return ['code' => 404, 'message' => 'Peserta tidak ditemukan'];
    }

    /**
     * Simulasi Pendaftaran Kunjungan (POST /kunjungan)
     */
    public function daftarKunjungan($data)
    {
        // Log payload untuk audit trail
        Log::info('BPJS Bridging: Daftar Kunjungan', $data);

        return [
            'code' => 201,
            'message' => 'Kunjungan berhasil didaftarkan ke P-Care',
            'response' => ['noKunjungan' => '0114B001' . date('dmY') . rand(1000,9999)]
        ];
    }

    /**
     * Simulasi Input Tindakan/Diagnosa (POST /tindakan)
     */
    public function inputTindakan($noKunjungan, $data)
    {
        Log::info("BPJS Bridging: Input Tindakan untuk {$noKunjungan}", $data);

        return [
            'code' => 201,
            'message' => 'Data medis berhasil dikirim ke P-Care'
        ];
    }
}