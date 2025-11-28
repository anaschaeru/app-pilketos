<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vote; // Import Model Vote
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk Transaction
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Untuk mencatat error
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
  /**
   * TAMPILKAN DAFTAR USER
   */
  public function index(Request $request)
  {
    $query = User::where('role', 'siswa');

    // Pencarian Cerdas
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('name', 'LIKE', '%' . $search . '%')
          ->orWhere('nisn', 'LIKE', '%' . $search . '%')
          ->orWhere('kelas', 'LIKE', '%' . $search . '%');
      });
    }

    // withQueryString() agar filter pencarian tidak hilang saat pindah halaman
    $users = $query->latest()->paginate(10)->withQueryString();

    return view('admin.users.index', compact('users'));
  }

  /**
   * FORM TAMBAH USER
   */
  public function create()
  {
    return view('admin.users.create');
  }

  /**
   * SIMPAN USER BARU
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'     => 'required|string|max:255',
      'nisn'     => 'required|numeric|unique:users,nisn',
      'kelas'    => 'required|string',
      'password' => 'required|min:6',
    ]);

    User::create([
      'name'      => $request->name,
      'nisn'      => $request->nisn,
      'kelas'     => $request->kelas,
      'password'  => Hash::make($request->password),
      'role'      => 'siswa',
      'has_voted' => false,
    ]);

    return redirect()->route('admin.users.index')
      ->with('success', 'Siswa berhasil ditambahkan!');
  }

  /**
   * FORM EDIT USER
   */
  public function edit(User $user)
  {
    return view('admin.users.edit', compact('user'));
  }

  /**
   * UPDATE USER
   */
  public function update(Request $request, User $user)
  {
    $request->validate([
      'name'     => 'required|string|max:255',
      'nisn'     => 'required|numeric|unique:users,nisn,' . $user->id,
      'kelas'    => 'required|string',
      'password' => 'nullable|min:6',
    ]);

    $data = [
      'name'  => $request->name,
      'nisn'  => $request->nisn,
      'kelas' => $request->kelas,
    ];

    // Hanya update password jika diisi
    if ($request->filled('password')) {
      $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('admin.users.index')
      ->with('success', 'Data siswa berhasil diperbarui!');
  }

  /**
   * HAPUS USER (Dengan Transaction)
   */
  public function destroy(User $user)
  {
    // Gunakan Transaction agar jika user dihapus, data vote-nya juga bersih
    DB::transaction(function () use ($user) {
      // Hapus data vote terkait (jika ada) untuk mencegah data sampah
      Vote::where('user_id', $user->id)->delete();

      // Hapus usernya
      $user->delete();
    });

    return redirect()->route('admin.users.index')
      ->with('success', 'Data siswa berhasil dihapus!');
  }

  /**
   * RESET STATUS MEMILIH
   */
  public function resetVote(User $user)
  {
    // Gunakan Transaction untuk konsistensi data
    DB::transaction(function () use ($user) {
      // 1. Reset status di tabel user
      $user->update(['has_voted' => false]);

      // 2. Hapus data di tabel votes
      Vote::where('user_id', $user->id)->delete();
    });

    return back()->with('success', 'Status memilih siswa berhasil di-reset!');
  }

  /**
   * IMPORT EXCEL
   */
  public function import(Request $request)
  {
    // Optimasi PHP untuk proses berat
    ini_set('max_execution_time', 300);
    ini_set('memory_limit', '-1');

    $request->validate([
      'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
      // Proses Import
      Excel::import(new UsersImport, $request->file('file'));

      return redirect()->route('admin.users.index')
        ->with('success', 'Data siswa berhasil diimpor!');
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
      // Tangkap error validasi spesifik dari Excel
      $failures = $e->failures();
      $messages = "";
      foreach ($failures as $failure) {
        $messages .= "Baris " . $failure->row() . ": " . implode(', ', $failure->errors()) . ". ";
      }
      return back()->with('error', 'Gagal validasi Excel: ' . $messages);
    } catch (\Exception $e) {
      // Log error asli ke file (storage/logs/laravel.log) untuk developer
      Log::error('Import Error: ' . $e->getMessage());

      // Tampilkan pesan umum + sedikit detail ke user
      return back()->with('error', 'Terjadi kesalahan saat impor. Pastikan format file benar. Error: ' . $e->getMessage());
    }
  }
}
