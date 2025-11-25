<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vote;
use App\Models\Setting;
use App\Models\Candidate;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
  public function index()
  {
    $setting = Setting::first();
    $isVotingActive = $setting ? $setting->voting_active : false;
    // 1. Ambil Total Siswa (DPT)
    $totalSiswa = User::where('role', 'siswa')->count();

    // 2. Ambil Total Suara Masuk
    $totalSuara = Vote::count();

    // 3. Hitung Persentase Partisipasi
    // Gunakan logika if untuk mencegah error "Division by Zero" jika belum ada data
    $partisipasi = $totalSiswa > 0 ? round(($totalSuara / $totalSiswa) * 100) : 0;

    // 4. Ambil Data Kandidat beserta Jumlah Suaranya
    // withCount('votes') adalah fitur ajaib Laravel untuk menghitung relasi otomatis
    $candidates = Candidate::withCount('votes')->orderBy('nomor_urut', 'asc')->get();

    // 5. Siapkan data untuk Chart.js (Array Nama & Array Suara)
    $chartLabels = $candidates->pluck('nama_paslon');
    $chartData = $candidates->pluck('votes_count');

    return view('admin.dashboard.index', compact(
      'totalSiswa',
      'totalSuara',
      'partisipasi',
      'candidates',
      'chartLabels',
      'chartData',
      'isVotingActive'
    ));
  }

  public function toggleVoting()
  {
    $setting = Setting::first();

    // Balik statusnya (Jika true jadi false, jika false jadi true)
    $setting->update([
      'voting_active' => !$setting->voting_active
    ]);

    $status = $setting->voting_active ? 'DIBUKA (ON)' : 'DITUTUP (OFF)';
    return back()->with('success', 'Status Voting berhasil diubah menjadi: ' . $status);
  }
}
