@extends('layouts.app')

@section('title', 'Edit Kandidat')

@section('content')
  <div class="container mx-auto px-4 py-8">

    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between">
      <h2 class="text-2xl font-bold text-gray-800">Edit Data Paslon</h2>
      <a href="{{ route('admin.candidates.index') }}"
        class="text-gray-500 hover:text-gray-700 font-medium flex items-center gap-1">
        &larr; Kembali
      </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="p-8">

        <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Urut</label>
              <input type="number" name="nomor_urut" value="{{ old('nomor_urut', $candidate->nomor_urut) }}"
                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none transition duration-200"
                required>
              @error('nomor_urut')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
              @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-2">Nama Paslon (Ketua & Wakil)</label>
              <input type="text" name="nama_paslon" value="{{ old('nama_paslon', $candidate->nama_paslon) }}"
                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none transition duration-200"
                required>
              @error('nama_paslon')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Visi</label>
            <textarea name="visi" class="rich-editor">{{ old('visi', $candidate->visi) }}</textarea>
            @error('visi')
              <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
          </div>

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Misi</label>
            <textarea name="misi" class="rich-editor">{{ old('misi', $candidate->misi) }}</textarea>
            @error('misi')
              <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
          </div>

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2 text-blue-600">Program Unggulan</label>
            <textarea name="program_unggulan" class="rich-editor">{{ old('program_unggulan', $candidate->program_unggulan) }}</textarea>
            @error('program_unggulan')
              <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
          </div>

          <div class="mb-8 border-t pt-6">
            <label class="block text-gray-700 text-sm font-bold mb-4">Foto Kandidat</label>

            <div class="flex items-start gap-6">
              <div class="text-center flex-shrink-0">
                <p class="text-xs text-gray-500 mb-2">Foto Saat Ini</p>
                @if ($candidate->foto)
                  <img src="{{ asset('storage/' . $candidate->foto) }}"
                    class="w-32 h-32 object-cover rounded-xl border border-gray-200 shadow-sm">
                @else
                  <div
                    class="w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 text-xs border">
                    No Image</div>
                @endif
              </div>

              <div class="flex-grow">
                <input type="file" name="foto"
                  class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengubah foto.</p>
                @error('foto')
                  <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-3">
            <a href="{{ route('admin.candidates.index') }}"
              class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition">
              Batal
            </a>
            <button type="submit"
              class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-lg hover:bg-blue-700 hover:shadow-blue-500/30 transition transform hover:-translate-y-0.5">
              Simpan Perubahan
            </button>
          </div>

        </form>
      </div>
    </div>
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
