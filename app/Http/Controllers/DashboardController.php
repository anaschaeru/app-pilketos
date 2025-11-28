<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
  /**
   * Menampilkan Dashboard Siswa
   */
  public function index()
  {
    // 1. Ambil data kandidat
    // Kita urutkan berdasarkan nomor urut agar rapi
    $candidates = Candidate::orderBy('nomor_urut', 'asc')->get();

    // 2. Ambil status voting (OPTIMASI)
    // Menggunakan value('nama_kolom') lebih hemat memori daripada first()
    // karena ia langsung mengambil nilai kolomnya saja, bukan seluruh objek model.
    // Operator '?? false' memastikan jika tabel kosong, nilai defaultnya false (Voting Tutup).
    $isVotingActive = Cache::remember('voting_status', 60, function () {
      return Setting::value('voting_active') ?? false;
    });

    // 3. Kirim ke View
    return view('dashboard', compact('candidates', 'isVotingActive'));
  }
}
