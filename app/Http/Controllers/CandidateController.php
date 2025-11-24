<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
  //
  // TAMPILKAN SEMUA KANDIDAT (READ)
  public function index()
  {
    $candidates = Candidate::orderBy('nomor_urut', 'asc')->get();
    return view('admin.candidates.index', compact('candidates'));
  }

  // TAMPILKAN FORM TAMBAH (CREATE - VIEW)
  public function create()
  {
    return view('admin.candidates.create');
  }

  // PROSES SIMPAN DATA (CREATE - LOGIC)
  public function store(Request $request)
  {
    $request->validate([
      'nomor_urut' => 'required|integer|unique:candidates,nomor_urut',
      'nama_paslon' => 'required|string',
      'visi' => 'required',
      'misi' => 'required',
      'program_unggulan' => 'required', // Field baru kita
      'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $request->all();

    // Upload Foto jika ada
    if ($request->hasFile('foto')) {
      $data['foto'] = $request->file('foto')->store('candidates', 'public');
    }

    Candidate::create($data);

    return redirect()->route('admin.candidates.index')->with('success', 'Kandidat berhasil ditambahkan!');
  }

  // TAMPILKAN FORM EDIT (VIEW)
  public function edit(Candidate $candidate)
  {
    return view('admin.candidates.edit', compact('candidate'));
  }

  // PROSES UPDATE DATA (LOGIC)
  public function update(Request $request, Candidate $candidate)
  {
    // 1. Validasi Input
    $request->validate([
      // 'unique:candidates,nomor_urut,'.$candidate->id artinya:
      // Cek unik, TAPI abaikan (pengecualian) untuk ID kandidat ini sendiri.
      'nomor_urut' => 'required|integer|unique:candidates,nomor_urut,' . $candidate->id,
      'nama_paslon' => 'required|string|max:255',
      'visi' => 'required',
      'misi' => 'required',
      'program_unggulan' => 'required',
      'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto bersifat opsional saat edit
    ]);

    // 2. Ambil semua data input
    $data = $request->except(['foto']); // Kita proses foto secara manual

    // 3. Cek apakah user mengupload foto baru?
    if ($request->hasFile('foto')) {

      // Hapus foto lama dari storage jika ada
      if ($candidate->foto && Storage::disk('public')->exists($candidate->foto)) {
        Storage::disk('public')->delete($candidate->foto);
      }

      // Upload foto baru
      $data['foto'] = $request->file('foto')->store('candidates', 'public');
    }

    // 4. Update data di database
    $candidate->update($data);

    // 5. Redirect kembali ke halaman index
    return redirect()->route('admin.candidates.index')->with('success', 'Data kandidat berhasil diperbarui!');
  }

  // HAPUS DATA (DELETE)
  public function destroy(Candidate $candidate)
  {
    if ($candidate->foto) {
      Storage::disk('public')->delete($candidate->foto);
    }
    $candidate->delete();
    return redirect()->route('admin.candidates.index')->with('success', 'Kandidat dihapus!');
  }
}
