<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVotingOpen
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $setting = \App\Models\Setting::first();

    // Jika setting tidak ada atau statusnya false (mati)
    if (!$setting || !$setting->voting_active) {
      return redirect()->route('dashboard')->with('error', 'Maaf, sesi pemungutan suara sedang ditutup.');
    }

    return $next($request);
  }
}
