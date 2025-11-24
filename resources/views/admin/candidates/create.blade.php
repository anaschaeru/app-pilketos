@extends('layouts.app')

@section('content')
  <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Tambah Kandidat Baru</h2>

    <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Urut</label>
          <input type="number" name="nomor_urut" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2">Nama Paslon</label>
          <input type="text" name="nama_paslon" class="w-full border rounded px-3 py-2" required>
        </div>
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Visi</label>
        <textarea name="visi" class="rich-editor"></textarea>
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Misi</label>
        <textarea name="misi" class="rich-editor"></textarea>
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2 text-blue-600">Program Unggulan</label>
        <textarea name="program_unggulan" class="rich-editor"></textarea>
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Foto Kandidat</label>
        <input type="file" name="foto"
          class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700">
      </div>

      <div class="flex justify-end gap-2">
        <a href="{{ route('admin.candidates.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Kandidat</button>
      </div>
    </form>
  </div>
@endsection
@push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>

  <script>
    tinymce.init({
      selector: '.rich-editor',
      height: 300,
      menubar: false,
      statusbar: false,

      // 2. PENTING: Konfigurasi agar TinyMCE tahu lokasi file CSS/Skin di CDN
      base_url: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2',
      suffix: '.min',

      plugins: 'lists link',
      toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter',

      // 3. Mencegah banner promosi muncul
      promotion: false,

      setup: function(editor) {
        editor.on('change', function() {
          editor.save();
        });
      }
    });
  </script>
@endpush
