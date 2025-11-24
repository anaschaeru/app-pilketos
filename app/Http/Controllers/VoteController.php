<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use App\Models\User; // Penting: Import Model User
use Illuminate\Http\RedirectResponse; // Penting: Import untuk tipe return
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    /**
     * Menyimpan suara hasil voting.
     */
    public function store(Candidate $candidate): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Cek apakah user sudah memilih
        if ($user->has_voted) {
            // Kita arahkan ke dashboard, bukan back() untuk menghindari loop jika user refresh halaman error
            return redirect()->route('dashboard')
                ->with('error', 'Anda sudah menggunakan hak pilih anda sebelumnya!');
        }

        try {
            // Gunakan Database Transaction agar data konsisten (Atomic)
            DB::transaction(function () use ($user, $candidate) {
                // 2. Simpan suara ke tabel votes
                Vote::create([
                    'user_id' => $user->id,
                    'candidate_id' => $candidate->id,
                ]);

                // 3. Tandai user sudah memilih
                $user->has_voted = true;
                $user->save();
            });

            // Jika sukses
            return redirect()->route('dashboard')
                ->with('success', 'Terima kasih! Suara Anda untuk Paslon No. ' . $candidate->nomor_urut . ' berhasil direkam.');
        } catch (\Exception $e) {
            // Jika ada error sistem (misal database down)
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan suara. Silakan coba lagi.');
        }
    }
}
