<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting; // Wajib di-import

class CheckVotingStatus
{
  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response
  {
    $setting = Setting::first();

    // Cek jika status voting tidak aktif
    if (!$setting || !$setting->is_voting_active) {

      // Redirect pemilih ke dashboard atau halaman khusus
      return redirect()
        ->route('dashboard') // Ganti dengan route yang sesuai untuk pemilih
        ->with('error', 'Pemungutan suara saat ini sedang tidak dibuka.');
    }

    return $next($request);
  }
}
