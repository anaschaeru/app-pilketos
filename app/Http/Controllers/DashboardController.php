<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // Mengambil semua data kandidat diurutkan berdasarkan nomor urut
        $candidates = Candidate::orderBy('nomor_urut', 'asc')->get();

        // Mengirim data $candidates ke view 'dashboard'
        return view('dashboard', compact('candidates'));
    }
}
