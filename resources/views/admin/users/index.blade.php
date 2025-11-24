@extends('layouts.app')

@section('title', 'Kelola Siswa')

@section('content')
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-bold text-gray-800">Data Pemilih</h2>

      <div class="flex gap-2">
        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          Import Excel
        </button>

        <a href="{{ route('admin.users.create') }}"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          Tambah Manual
        </a>
      </div>
    </div>
    <div id="importModal"
      class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
      <div class="relative p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3 text-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Import Data Siswa</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500 mb-4">
              Pastikan file Excel (.xlsx) memiliki header kolom: <br>
              <code class="bg-gray-100 px-1 py-0.5 font-bold">nama</code> dan <code
                class="bg-gray-100 px-1 py-0.5 font-bold">nisn</code>
            </p>

            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="file" name="file" required
                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 mb-4" />

              <div class="flex justify-center gap-3 mt-4">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                  class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                  Batal
                </button>
                <button type="submit"
                  class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                  Upload
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
      <table class="min-w-full leading-normal">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">No</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">NISN</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Siswa</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kelas</th>
            <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status Memilih</th>
            <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach ($users as $index => $user)
            <tr class="hover:bg-gray-50">
              <td class="px-5 py-4 text-sm text-gray-600">{{ $users->firstItem() + $index }}</td>
              <td class="px-5 py-4 text-sm font-semibold text-gray-800">{{ $user->nisn }}</td>
              <td class="px-5 py-4 text-sm font-semibold text-gray-800">{{ $user->name }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $user->kelas ?? '-' }}</td>
              <td class="px-5 py-4 text-center">
                @if ($user->has_voted)
                  <span class="px-3 py-1 text-xs font-bold text-green-800 bg-green-100 rounded-full">Sudah Memilih</span>
                @else
                  <span class="px-3 py-1 text-xs font-bold text-gray-500 bg-gray-100 rounded-full">Belum</span>
                @endif
              </td>
              <td class="px-5 py-4 text-center flex justify-center gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-yellow-500 hover:text-yellow-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                  </svg>
                </a>

                @if ($user->has_voted)
                  <form action="{{ route('admin.users.reset', $user->id) }}" method="POST"
                    onsubmit="return confirm('Reset status pilih siswa ini?')">
                    @csrf
                    <button type="submit" class="text-blue-500 hover:text-blue-700" title="Reset Vote">
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
                  <button type="submit" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                      </path>
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $users->links() }}
    </div>
  </div>
@endsection
