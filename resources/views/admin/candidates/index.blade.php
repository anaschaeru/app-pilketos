@extends('layouts.app')

@section('title', 'Kelola Kandidat - Admin Dashboard')

@section('content')
  <div class="container mx-auto px-4 py-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
      <div>
        <h2 class="text-3xl font-bold text-gray-800">Daftar Kandidat</h2>
        <p class="text-gray-500 mt-1">Kelola data calon ketua & wakil ketua OSIS.</p>
      </div>
      <a href="{{ route('admin.candidates.create') }}"
        class="group flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-1">
        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Kandidat
      </a>
    </div>

    @if (session('success'))
      <div
        class="hidden flex items-center bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm mb-6 animate-fade-in-down">
      </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
          <thead>
            <tr class="bg-gray-50 text-left border-b border-gray-200">
              <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-20">No</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Foto</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-48">Nama Paslon</th>

              <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[300px]">Visi & Misi
              </th>
              <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[200px]">Program
                Unggulan</th>

              <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse ($candidates as $candidate)
              <tr class="hover:bg-blue-50/30 transition-colors duration-200 group align-top">

                <td class="px-6 py-5 whitespace-nowrap">
                  <div class="flex items-center justify-center">
                    <span
                      class="h-10 w-10 rounded-full bg-blue-100 text-blue-800 font-bold flex items-center justify-center text-lg border-2 border-blue-200 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-colors">
                      {{ $candidate->nomor_urut }}
                    </span>
                  </div>
                </td>

                <td class="px-6 py-5 whitespace-nowrap">
                  <div class="flex-shrink-0 h-16 w-16 relative">
                    @if ($candidate->foto)
                      <img
                        class="h-16 w-16 rounded-xl object-cover shadow-md border border-gray-200 transform group-hover:scale-110 transition-transform duration-300"
                        src="{{ asset('storage/' . $candidate->foto) }}" alt="{{ $candidate->nama_paslon }}">
                    @else
                      <div
                        class="h-16 w-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-medium border border-gray-200">
                        No Image
                      </div>
                    @endif
                  </div>
                </td>

                <td class="px-6 py-5">
                  <p class="text-gray-900 font-bold text-lg leading-tight">{{ $candidate->nama_paslon }}</p>
                </td>

                <td class="px-6 py-5">
                  <div class="space-y-4">
                    <div>
                      <span
                        class="inline-block bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide mb-1">Visi</span>
                      <p class="text-sm text-gray-700 italic">"{{ Str::limit(strip_tags($candidate->visi), 100) }}"</p>
                    </div>

                    <div>
                      <span
                        class="inline-block bg-purple-100 text-purple-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide mb-1">Misi</span>
                      <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit(strip_tags($candidate->misi), 100) }}
                      </p>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-5">
                  <div class="bg-amber-50 border border-amber-100 rounded-lg p-3">
                    <p class="text-sm text-gray-700 font-medium">
                      {{ Str::limit(strip_tags($candidate->program_unggulan), 100) }}</p>
                  </div>
                </td>

                <td class="px-6 py-5 text-center whitespace-nowrap">
                  <div class="flex flex-col gap-2 items-center">
                    <a href="{{ route('admin.candidates.edit', $candidate->id) }}"
                      class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white border border-yellow-200 transition-all duration-200 w-10 h-10 flex items-center justify-center"
                      title="Edit Data">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                      </svg>
                    </a>

                    <button type="button" onclick="confirmDelete({{ $candidate->id }}, '{{ $candidate->nama_paslon }}')"
                      class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white border border-red-200 transition-all duration-200 w-10 h-10 flex items-center justify-center"
                      title="Hapus Data">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                      </svg>
                    </button>

                    <form id="delete-form-{{ $candidate->id }}"
                      action="{{ route('admin.candidates.destroy', $candidate->id) }}" method="POST"
                      style="display: none;">
                      @csrf
                      @method('DELETE')
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center justify-center">
                    <div class="bg-gray-100 p-4 rounded-full mb-4">
                      <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                      </svg>
                    </div>
                    <h3 class="text-gray-500 font-medium text-lg">Belum ada kandidat terdaftar</h3>
                    <p class="text-gray-400 text-sm mb-4">Mulai dengan menambahkan calon ketua OSIS baru.</p>
                    <a href="{{ route('admin.candidates.create') }}"
                      class="text-blue-600 font-semibold hover:underline">Tambah Sekarang &rarr;</a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-6 text-right text-sm text-gray-400">
      Total Kandidat: <span class="font-bold text-gray-600">{{ $candidates->count() }}</span>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function confirmDelete(id, nama) {
      Swal.fire({
        title: 'Hapus Kandidat?',
        text: "Anda akan menghapus data paslon: " + nama + ". Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + id).submit();
        }
      })
    }
  </script>
@endpush
