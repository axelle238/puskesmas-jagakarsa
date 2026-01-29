<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Simulasi Kirim Pesan WA
     */
    public static function kirimPesan($nomor, $pesan)
    {
        // Validasi nomor (harus 62xxx)
        if (substr($nomor, 0, 1) == '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        // Simulasi hit API vendor (misal: Twilio, Watzap, dll)
        // Http::post('https://api.whatsapp-vendor.com/send', [...]);

        Log::info("WA SENT to {$nomor}: {$pesan}");

        return true;
    }
}
