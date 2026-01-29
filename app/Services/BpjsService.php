<?php

namespace App\Services;

class BpjsService
{
    /**
     * Simulasi Pengecekan Status Peserta BPJS
     * 
     * @param string $noKartu
     * @return array
     */
    public static function cekStatusPeserta($noKartu)
    {
        // Simulasi delay request API
        sleep(1);

        // Simulasi logika response
        // Jika nomor kartu diawali '00', dianggap aktif
        // Jika diawali '99', dianggap tidak aktif
        // Sisanya dianggap tidak ditemukan

        if (empty($noKartu)) {
            return [
                'status' => 'error',
                'message' => 'Nomor kartu tidak boleh kosong.'
            ];
        }

        if (str_starts_with($noKartu, '00')) {
            return [
                'status' => 'success',
                'data' => [
                    'nama' => 'PESERTA SIMULASI AKTIF',
                    'status_peserta' => 'AKTIF',
                    'kelas' => 'KELAS 1',
                    'faskes_tk1' => 'PUSKESMAS JAGAKARSA'
                ]
            ];
        } elseif (str_starts_with($noKartu, '99')) {
            return [
                'status' => 'success',
                'data' => [
                    'nama' => 'PESERTA SIMULASI NON-AKTIF',
                    'status_peserta' => 'TIDAK AKTIF',
                    'kelas' => 'KELAS 2',
                    'faskes_tk1' => 'KLINIK LAIN'
                ]
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Data peserta tidak ditemukan dalam database BPJS.'
            ];
        }
    }
}
