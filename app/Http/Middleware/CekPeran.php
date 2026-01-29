<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekPeran
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$peranDizinkan): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/masuk');
        }

        // Admin selalu punya akses penuh (Superuser)
        if ($user->peran === 'admin') {
            return $next($request);
        }

        // Cek apakah peran user ada di daftar yang diizinkan
        if (in_array($user->peran, $peranDizinkan)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
