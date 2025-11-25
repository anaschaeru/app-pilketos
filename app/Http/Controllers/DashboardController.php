<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Candidate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  //
  public function index()
  {
    // Ambil data kandidat
    $candidates = Candidate::orderBy('nomor_urut', 'asc')->get();

    // 2. PERBAIKAN: Ambil status voting dari database
    $setting = Setting::first();

    // Jika setting ada, ambil nilainya. Jika belum ada tabel setting, default false (mati)
    $isVotingActive = $setting ? $setting->voting_active : false;

    // 3. Kirim variabel 'isVotingActive' ke view
    return view('dashboard', compact('candidates', 'isVotingActive'));
  }
}
