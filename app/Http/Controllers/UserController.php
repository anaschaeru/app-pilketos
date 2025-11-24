<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
  // TAMPILKAN DAFTAR USER
  public function index()
  {
    // Kita ambil user yang role-nya 'siswa' saja, urutkan terbaru
    // Menggunakan paginate(10) agar halaman tidak berat jika siswanya ribuan
    $users = User::where('role', 'siswa')->latest()->paginate(10);
    return view('admin.users.index', compact('users'));
  }

  // FORM TAMBAH USER
  public function create()
  {
    return view('admin.users.create');
  }

  // SIMPAN USER BARU
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'nisn' => 'required|numeric|unique:users,nisn',
      'kelas' => 'required|string',
      'password' => 'required|min:6',
    ]);

    User::create([
      'name' => $request->name,
      'nisn' => $request->nisn,
      'kelas' => $request->kelas,
      'password' => Hash::make($request->password),
      'role' => 'siswa', // Default role siswa
      'has_voted' => false,
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Siswa berhasil ditambahkan!');
  }

  // FORM EDIT USER
  public function edit(User $user)
  {
    return view('admin.users.edit', compact('user'));
  }

  // UPDATE USER
  public function update(Request $request, User $user)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'nisn' => 'required|numeric|unique:users,nisn,' . $user->id,
      'kelas' => 'required|string',
      'password' => 'nullable|min:6', // Password boleh kosong jika tidak ingin diganti
    ]);

    $data = [
      'name' => $request->name,
      'nisn' => $request->nisn,
      'kelas' => $request->kelas,
    ];

    // Cek apakah admin mengisi password baru?
    if ($request->filled('password')) {
      $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('admin.users.index')->with('success', 'Data siswa berhasil diperbarui!');
  }

  // HAPUS USER
  public function destroy(User $user)
  {
    $user->delete();
    return redirect()->route('admin.users.index')->with('success', 'Data siswa dihapus!');
  }

  // FITUR RESET STATUS MEMILIH (Opsional, berguna jika ada kesalahan teknis)
  public function resetVote(User $user)
  {
    $user->update(['has_voted' => false]);
    // Hapus juga data di tabel votes (butuh import model Vote)
    \App\Models\Vote::where('user_id', $user->id)->delete();

    return back()->with('success', 'Status memilih siswa berhasil di-reset!');
  }

  // FUNGSI IMPORT EXCEL
  public function import(Request $request)
  {
    // 0. OPTIMASI PHP: Naikkan batas waktu & memori sementara
    // Ini wajib untuk data > 2000 baris agar tidak error "Time Limit Exceeded"
    ini_set('max_execution_time', 300); // Set batas waktu jadi 300 detik (5 menit)
    ini_set('memory_limit', '-1');      // Gunakan memori seperlunya (unlimited)

    // 1. Validasi file harus Excel
    $request->validate([
      'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
      // 2. Proses Import
      // Pastikan UsersImport kamu sudah menggunakan 'ToCollection' dan 'User::insert'
      // seperti yang kita bahas sebelumnya agar insert-nya ngebut.
      Excel::import(new UsersImport, $request->file('file'));

      return redirect()->route('admin.users.index')->with('success', 'Data siswa berhasil diimpor dari Excel!');
    } catch (\Exception $e) {
      // Jika gagal (misal format salah atau NISN duplikat)
      return redirect()->back()->with('error', 'Gagal impor: ' . $e->getMessage());
    }
  }
}
