@extends('layouts.app')

@section('title', 'Kelola Siswa')

@section('content')
  <div class="container mx-auto px-4 py-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
      <div class="text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-800">Data Pemilih</h2>
        <p class="text-gray-500 text-sm mt-1">Total: {{ $users->total() }} Siswa</p>
      </div>

      <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
          class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl shadow flex items-center justify-center gap-2 transition active:scale-95">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          Import Excel
        </button>

        <a href="{{ route('admin.users.create') }}"
          class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl shadow flex items-center justify-center gap-2 transition active:scale-95">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          Tambah Manual
        </a>
      </div>
    </div>

    <div id="importModal"
      class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center px-4">
      <div class="relative p-6 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Import Data Siswa</h3>
          <p class="text-sm text-gray-500 mt-2">
            Upload file Excel (.xlsx) dengan kolom header: <br>
            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">nama</span>,
            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">nisn</span>,
            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">kelas</span>
          </p>

          <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="mt-6">
            @csrf
            <label class="block">
              <span class="sr-only">Choose file</span>
              <input type="file" name="file" required
                class="block w-full text-sm text-slate-500
                  file:mr-4 file:py-2.5 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-blue-50 file:text-blue-700
                  hover:file:bg-blue-100 transition
                " />
            </label>

            <div class="flex justify-center gap-3 mt-8">
              <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                Batal
              </button>
              <button type="submit"
                class="flex-1 px-4 py-2.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-600/30">
                Upload Data
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div
      class="bg-white md:rounded-2xl md:shadow-xl md:border md:border-gray-100 overflow-hidden bg-transparent shadow-none border-none">

      <table class="min-w-full leading-normal">
        <thead class="hidden md:table-header-group bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NISN</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>

        <tbody class="block md:table-row-group space-y-4 md:space-y-0">
          @foreach ($users as $index => $user)
            <tr
              class="block md:table-row bg-white rounded-xl shadow-md md:shadow-none border border-gray-100 md:border-b hover:bg-blue-50/30 transition-colors">

              <td
                class="flex justify-between md:table-cell px-6 py-3 md:py-4 text-sm text-gray-600 border-b md:border-none last:border-none">
                <span class="font-bold md:hidden text-gray-400 uppercase text-xs">No</span>
                <span>{{ $users->firstItem() + $index }}</span>
              </td>

              <td
                class="flex justify-between md:table-cell px-6 py-3 md:py-4 text-sm font-mono text-blue-600 font-bold border-b md:border-none last:border-none">
                <span class="font-bold md:hidden text-gray-400 uppercase text-xs">NISN</span>
                <span>{{ $user->nisn }}</span>
              </td>

              <td
                class="flex justify-between md:table-cell px-6 py-3 md:py-4 text-sm font-bold text-gray-800 border-b md:border-none last:border-none">
                <span class="font-bold md:hidden text-gray-400 uppercase text-xs">Nama</span>
                <span>{{ $user->name }}</span>
              </td>

              <td
                class="flex justify-between md:table-cell px-6 py-3 md:py-4 text-sm text-gray-600 border-b md:border-none last:border-none">
                <span class="font-bold md:hidden text-gray-400 uppercase text-xs">Kelas</span>
                <span>{{ $user->kelas ?? '-' }}</span>
              </td>

              <td
                class="flex justify-between items-center md:table-cell px-6 py-3 md:py-4 text-center border-b md:border-none last:border-none">
                <span class="font-bold md:hidden text-gray-400 uppercase text-xs">Status</span>
                <div class="flex justify-end md:justify-center w-full">
                  @if ($user->has_voted)
                    <span
                      class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Sudah
                    </span>
                  @else
                    <span
                      class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold text-gray-500 bg-gray-100 rounded-full">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      Belum
                    </span>
                  @endif
                </div>
              </td>

              <td
                class="flex justify-between items-center md:table-cell px-6 py-4 text-center md:py-4 bg-gray-50 md:bg-transparent rounded-b-xl md:rounded-none">
                <span class="font-bold md:hidden text-gray-400 uppercase text-xs">Aksi</span>
                <div class="flex justify-end md:justify-center gap-3 w-full">
                  <a href="{{ route('admin.users.edit', $user->id) }}"
                    class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white border border-yellow-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                      </path>
                    </svg>
                  </a>

                  @if ($user->has_voted)
                    <form action="{{ route('admin.users.reset', $user->id) }}" method="POST"
                      onsubmit="return confirm('Reset status pilih siswa ini?')">
                      @csrf
                      <button type="submit"
                        class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white border border-blue-200 transition"
                        title="Reset Vote">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                          </path>
                        </svg>
                      </button>
                    </form>
                  @endif

                  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                    onsubmit="return confirm('Hapus siswa ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                      class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-500 hover:text-white border border-red-200 transition">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                      </svg>
                    </button>
                  </form>
                </div>
              </td>

            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      {{ $users->links() }}
    </div>
  </div>
@endsection
