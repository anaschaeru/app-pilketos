<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  // 1. Tampilkan Form Login
  public function index()
  {
    return view('auth.login');
  }

  // 2. Proses Login
  public function authenticate(Request $request)
  {
    // 1. Validasi Input
    $credentials = $request->validate([
      'nisn' => ['required', 'numeric'],
      'password' => ['required'],
    ]);

    // 2. Coba Login
    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      // --- LOGIKA PENGALIHAN (REDIRECT) ---

      // Ambil data user yang sedang login
      $user = Auth::user();

      // Jika dia Admin, lempar ke halaman kelola kandidat
      if ($user->role === 'admin') {
        // return redirect()->route('admin.candidates.index');
        return redirect()->route('admin.dashboard');
      }

      // Jika Siswa (atau lainnya), lempar ke halaman voting utama
      return redirect()->intended('/');
    }

    // 3. Jika Gagal Login
    return back()->withErrors([
      'nisn' => 'NISN atau password salah.',
    ])->onlyInput('nisn');
  }

  // 3. Proses Logout
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
  }
}
