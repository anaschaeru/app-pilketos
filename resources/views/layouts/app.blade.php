<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'E-Voting OSIS SMKN 4 Tangerang')</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    /* Kembalikan style list standar untuk konten TinyMCE */
    .prose ul {
      list-style-type: disc;
      padding-left: 1.5rem;
      margin-bottom: 0.5rem;
    }

    .prose ol {
      list-style-type: decimal;
      padding-left: 1.5rem;
      margin-bottom: 0.5rem;
    }

    .prose p {
      margin-bottom: 0.5rem;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

  <nav
    class="sticky top-0 z-50 bg-blue-900/95 backdrop-blur-md border-b border-blue-700/50 shadow-xl transition-all duration-300">
    <div class="container mx-auto px-6 py-3">
      <div class="flex justify-between items-center">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
          <div
            class="relative w-10 h-10 flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform duration-200">
            <span class="text-white font-black text-xl">4</span>
            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-blue-900">
            </div>
          </div>

          <div class="flex flex-col">
            <span
              class="text-lg font-bold text-white tracking-tight leading-none group-hover:text-blue-200 transition-colors">E-VOTING</span>
            <span class="text-[10px] font-medium text-blue-300 tracking-[0.2em] uppercase">SMKN 4
              Tangerang</span>
          </div>
        </a>

        <div class="flex items-center gap-4">
          @auth
            @if (Auth::user()->role === 'admin')
              <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'text-white font-bold' : 'text-blue-200' }} hover:text-white text-sm font-medium transition-colors hidden md:block">
                Hitung Cepat
              </a>
              <a href="{{ route('admin.candidates.index') }}"
                class="text-blue-200 hover:text-white text-sm font-medium transition-colors hidden md:block">
                Kelola Kandidat
              </a>
              <a href="{{ route('admin.users.index') }}"
                class="text-blue-200 hover:text-white text-sm font-medium transition-colors hidden md:block">
                Data Pemilih
              </a>
            @endif
            <div
              class="hidden md:flex items-center gap-3 bg-blue-800/50 py-1.5 px-4 rounded-full border border-blue-700/50">
              <div
                class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shadow-inner">
                {{ substr(Auth::user()->name, 0, 1) }}
              </div>
              <div class="text-sm">
                <p class="text-gray-100 font-semibold leading-none">{{ Auth::user()->name }}</p>
                <p class="text-blue-300 text-[10px] uppercase tracking-wide mt-0.5">{{ Auth::user()->role }}
                </p>
              </div>
            </div>


            <form action="{{ route('logout') }}" method="POST" class="inline">
              @csrf
              <button type="submit"
                class="group relative flex items-center justify-center w-10 h-10 rounded-full bg-red-500/10 hover:bg-red-600 text-red-400 hover:text-white transition-all duration-200"
                title="Logout">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                  </path>
                </svg>
              </button>
            </form>
          @endauth

          @guest
            <a href="{{ route('login') }}"
              class="relative inline-flex items-center justify-center px-6 py-2 overflow-hidden font-medium text-blue-600 transition duration-300 ease-out border-2 border-white rounded-full shadow-md group">
              <span
                class="absolute inset-0 flex items-center justify-center w-full h-full text-white duration-300 -translate-x-full bg-blue-600 group-hover:translate-x-0 ease">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                  </path>
                </svg>
              </span>
              <span
                class="absolute flex items-center justify-center w-full h-full text-white transition-all duration-300 transform group-hover:translate-x-full ease">Login</span>
              <span class="relative invisible">Login</span>
            </a>
          @endguest
        </div>
      </div>
    </div>
  </nav>

  <main class="flex-grow container mx-auto px-6 py-8">
    @yield('content')
  </main>

  <footer class="bg-gray-800 text-gray-400 text-center py-6 mt-auto">
    <p>&copy; {{ date('Y') }} SMKN 4 Tangerang. E-Voting System.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Notifikasi Sukses (Hijau)
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false,
        // background: '#f0fdf4', // Hijau muda
        color: '#000000',
      });
    @endif

    // Notifikasi Error (Merah)
    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
        showConfirmButton: true,
        confirmButtonColor: '#d33',
      });
    @endif
  </script>

  @stack('scripts')

</body>

</html>
