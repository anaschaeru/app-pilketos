<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter; // Untuk keamanan (Throttling)
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
  /**
   * 1. Tampilkan Form Login
   */
  public function index()
  {
    return view('auth.login');
  }

  /**
   * 2. Proses Login
   */
  public function authenticate(Request $request)
  {
    // A. Validasi Input
    $credentials = $request->validate([
      'nisn'     => ['required', 'numeric'],
      'password' => ['required', 'string'],
    ]);

    // B. Cek Rate Limiter (Keamanan Anti-Brute Force)
    // Gunakan IP address sebagai kunci identitas
    $throttleKey = Str::lower($request->input('nisn')) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
      $seconds = RateLimiter::availableIn($throttleKey);
      throw ValidationException::withMessages([
        'nisn' => trans('auth.throttle', ['seconds' => $seconds]),
      ]);
    }

    // C. Cek "Remember Me" (Checkbox di form login, name='remember')
    $remember = $request->boolean('remember');

    // D. Coba Login
    if (Auth::attempt($credentials, $remember)) {
      // Login Sukses: Bersihkan hitungan gagal
      RateLimiter::clear($throttleKey);

      $request->session()->regenerate();

      // --- LOGIKA PENGALIHAN (REDIRECT) ---
      $user = Auth::user();

      // Jika Admin, ke dashboard admin
      if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
      }

      // Jika Siswa, ke halaman voting
      return redirect()->intended('/');
    }

    // E. Jika Gagal Login: Catat kegagalan (Hit Rate Limiter)
    RateLimiter::hit($throttleKey);

    // Kembalikan error
    return back()->withErrors([
      'nisn' => 'NISN atau password yang Anda masukkan salah.',
    ])->onlyInput('nisn');
  }

  /**
   * 3. Proses Logout
   */
  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login'); // Atau redirect ke route('login')
  }
}
