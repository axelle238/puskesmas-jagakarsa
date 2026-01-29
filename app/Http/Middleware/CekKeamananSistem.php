<?php

namespace App\Http\Middleware;

use App\Models\LogKeamanan;
use App\Models\PengaturanKeamanan;
use App\Models\WhitelistIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekKeamananSistem
{
    /**
     * Middleware untuk memvalidasi akses berdasarkan konfigurasi keamanan global.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil Pengaturan
        $settings = PengaturanKeamanan::first();
        if (!$settings) {
            return $next($request); // Jika belum di-setup, loloskan
        }

        // 2. Cek Whitelist IP (Hanya untuk Admin area, bukan publik)
        if ($settings->batasi_ip_login_admin && $request->is('admin*') && !$this->isIpAllowed($request->ip())) {
            
            // Catat Log Blokir
            LogKeamanan::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'event' => 'Akses Ditolak (IP Blokir)',
                'detail' => ['url' => $request->fullUrl()]
            ]);

            abort(403, 'Akses dari IP Address ini tidak diizinkan oleh Administrator.');
        }

        return $next($request);
    }

    private function isIpAllowed($ip)
    {
        // Selalu izinkan localhost
        if (in_array($ip, ['127.0.0.1', '::1'])) return true;

        return WhitelistIp::where('ip_address', $ip)->where('aktif', true)->exists();
    }
}
