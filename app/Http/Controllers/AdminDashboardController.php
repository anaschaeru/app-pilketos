<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vote;
use App\Models\Setting;
use App\Models\Candidate;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
  /**
   * Tampilkan Dashboard Admin & Quick Count
   */
  public function index()
  {
    // 1. Ambil Status Voting (Lebih Efisien)
    // Menggunakan value() mengambil langsung nilai kolom, bukan object model.
    // '?? false' memastikan jika tabel kosong, dianggap voting mati.
    $isVotingActive = Setting::value('voting_active') ?? false;

    // 2. Ambil Total Siswa (DPT)
    $totalSiswa = User::where('role', 'siswa')->count();

    // 3. Ambil Total Suara Masuk
    $totalSuara = Vote::count();

    // 4. Hitung Persentase Partisipasi (Cegah Division by Zero)
    $partisipasi = $totalSiswa > 0
      ? round(($totalSuara / $totalSiswa) * 100)
      : 0;

    // 5. Ambil Data Kandidat & Suara
    $candidates = Candidate::withCount('votes')
      ->orderBy('nomor_urut', 'asc')
      ->get();

    // 6. Siapkan Data Chart
    // pluck() otomatis membuat Collection, kita bisa langsung kirim ke view
    $chartLabels = $candidates->pluck('nama_paslon');
    $chartData   = $candidates->pluck('votes_count');

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

  /**
   * Mengubah Status Voting (ON/OFF)
   */
  public function toggleVoting()
  {
    // PERBAIKAN PENTING: Gunakan firstOrCreate
    // Ini mencegah error jika tabel 'settings' masih kosong.
    // Jika kosong, dia akan membuat baris baru dengan default 'voting_active' => false
    $setting = Setting::firstOrCreate(
      [], // Kondisi pencarian (kosong = ambil baris pertama apapun)
      ['voting_active' => false] // Nilai default jika baru dibuat
    );

    // Update status (toggle true/false)
    $setting->update([
      'voting_active' => !$setting->voting_active
    ]);

    $status = $setting->voting_active ? 'DIBUKA (ON)' : 'DITUTUP (OFF)';

    return back()->with('success', 'Status Voting berhasil diubah menjadi: ' . $status);
  }
}
