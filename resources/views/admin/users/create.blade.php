@extends('layouts.app')

@section('content')
  <div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow-lg mt-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Siswa Baru</h2>

    <form action="{{ route('admin.users.store') }}" method="POST">
      @csrf
      <div class="mb-4">
        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
        <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-bold text-gray-700 mb-1">NISN (Login ID)</label>
        <input type="number" name="nisn" class="w-full border rounded px-3 py-2" required>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-bold text-gray-700 mb-1">Kelas</label>
        <input type="text" name="kelas" value="{{ old('kelas', $user->kelas ?? '') }}"
          class="w-full border rounded px-3 py-2" placeholder="Contoh: XII RPL 1" required>
      </div>
      <div class="mb-6">
        <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
        <input type="text" name="password" class="w-full border rounded px-3 py-2"
          placeholder="Default saran: samakan dengan NISN" required>
      </div>

      <div class="flex justify-end gap-2">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 rounded text-gray-700">Batal</a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
      </div>
    </form>
  </div>
@endsection
