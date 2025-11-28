<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Penting untuk debugging

class VoteController extends Controller
{
  /**
   * Menyimpan suara hasil voting.
   */
  public function store(Candidate $candidate): RedirectResponse
  {
    $userId = Auth::id();

    try {
      // START TRANSACTION
      DB::transaction(function () use ($userId, $candidate) {

        // 1. PENTING: Gunakan lockForUpdate()
        // Ini akan "mengunci" baris data user ini di database.
        // Jika user klik 2x cepat, request kedua akan dipaksa antre
        // sampai request pertama selesai. Ini mencegah Double Voting.
        $user = User::where('id', $userId)->lockForUpdate()->first();

        // 2. Cek status di dalam transaksi (Data paling valid)
        if ($user->has_voted) {
          // Lempar exception agar transaksi dibatalkan (Rollback)
          throw new \Exception('Anda sudah memilih sebelumnya.');
        }

        // 3. Simpan Suara
        Vote::create([
          'user_id' => $user->id,
          'candidate_id' => $candidate->id,
        ]);

        // 4. Update Status User
        $user->has_voted = true;
        $user->save();
      });

      // Jika transaksi berhasil tanpa error
      return redirect()->route('dashboard')
        ->with('success', 'Terima kasih! Suara Anda untuk Paslon No. ' . $candidate->nomor_urut . ' berhasil direkam.');
    } catch (\Exception $e) {
      // Jika error karena user sudah memilih (pesan dari throw new Exception di atas)
      if ($e->getMessage() === 'Anda sudah memilih sebelumnya.') {
        return redirect()->route('dashboard')
          ->with('error', 'Peringatan: Anda sudah menggunakan hak pilih Anda.');
      }

      // Jika error teknis lain (Database mati, dll)
      // Kita catat di Log file (storage/logs/laravel.log) agar developer bisa cek
      Log::error("Gagal menyimpan vote user ID {$userId}: " . $e->getMessage());

      return redirect()->route('dashboard')
        ->with('error', 'Terjadi kesalahan sistem saat menyimpan suara. Silakan hubungi panitia.');
    }
  }
}
