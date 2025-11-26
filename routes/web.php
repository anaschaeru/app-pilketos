<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminDashboardController; // Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE UNTUK TAMU (BELUM LOGIN)
// ==========================================
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::get('/foo', function () {
  Artisan::call('storage:link');
  return 'Symlink Berhasil Dibuat!';
});


// ==========================================
// 2. ROUTE UTAMA APLIKASI (SUDAH LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {

  // --- Dashboard Siswa & Logout ---
  Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
  Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

  // --- Proses Voting ---
  Route::post('/vote/{candidate}', [VoteController::class, 'store'])
    ->name('vote.store')
    ->middleware('voting.open');

  // Redirect aman jika siswa akses link vote via GET (URL Bar)
  Route::get('/vote/{candidate}', function () {
    return redirect()->route('dashboard');
  });

  // ==========================================
  // 3. ADMIN ROUTES (Khusus Role Admin)
  // ==========================================
  Route::prefix('admin')
    ->name('admin.')
    ->middleware(IsAdmin::class) // Middleware buatan kita
    ->group(function () {

      // A. Dashboard Admin (Quick Count)
      Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

      // B. Kelola Kandidat (CRUD)
      Route::resource('candidates', CandidateController::class);

      // C. Kelola User (Siswa)
      // PENTING: Route custom ditaruh DI ATAS Route::resource

      // 1. Import Excel
      Route::post('/users/import', [UserController::class, 'import'])->name('users.import');

      // 2. Reset Vote
      Route::post('/users/{user}/reset', [UserController::class, 'resetVote'])->name('users.reset');

      // 3. CRUD Standar (Resource ditaruh paling bawah di blok user)
      Route::resource('users', UserController::class);

      Route::post('/toggle-voting', [AdminDashboardController::class, 'toggleVoting'])->name('voting.toggle');
    });
});
