<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login E-Voting SMKN 4 Tangerang</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Pattern Background Teknis */
        .bg-tech-pattern {
            background-color: #0f172a;
            /* Slate 900 */
            background-image: linear-gradient(#1e293b 1px, transparent 1px), linear-gradient(90deg, #1e293b 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Efek Glow Halus di belakang Card */
        .glow-effect {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, rgba(37, 99, 235, 0) 70%);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            filter: blur(40px);
        }
    </style>
</head>

<body class="bg-tech-pattern h-screen flex items-center justify-center relative overflow-hidden">

    <div class="glow-effect"></div>

    <div
        class="relative z-10 bg-white/95 backdrop-blur-sm p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-md border border-white/20 transform transition-all hover:scale-[1.01] duration-500">

        <div class="text-center mb-8">
            <div class="relative w-20 h-20 mx-auto mb-4 group">
                <div
                    class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-cyan-400 rounded-full opacity-75 blur animate-pulse">
                </div>
                <div
                    class="relative w-full h-full bg-white rounded-full flex items-center justify-center shadow-lg border-2 border-blue-50">
                    <span class="text-3xl font-black text-blue-900">4</span>
                </div>
                <div
                    class="absolute bottom-0 right-0 bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-full font-bold border-2 border-white">
                    SMK
                </div>
            </div>

            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Selamat Datang</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Portal E-Voting OSIS SMKN 4 Tangerang</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r mb-6 animate-fade-in-down">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">Login Gagal</p>
                        <p class="text-xs text-red-600 mt-0.5">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2 ml-1" for="nisn">
                    NISN Siswa
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input type="number" name="nisn" id="nisn"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 ease-in-out shadow-sm"
                        placeholder="Contoh: 0056xxx" required autofocus>
                </div>
            </div>

            <div>
                <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2 ml-1" for="password">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <input type="password" name="password" id="password"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 ease-in-out shadow-sm"
                        placeholder="••••••••" required>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-700 to-blue-900 hover:from-blue-800 hover:to-blue-950 text-white font-bold py-3.5 rounded-xl shadow-lg transform transition hover:-translate-y-0.5 hover:shadow-2xl focus:ring-4 focus:ring-blue-300 active:scale-95 duration-200 flex items-center justify-center gap-2">
                    <span>MASUK SEKARANG</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-400">
                &copy; {{ date('Y') }} Tim IT SMKN 4 Tangerang.<br>
                <span class="text-slate-300">Secure E-Voting System v1.0</span>
            </p>
        </div>
    </div>

</body>

</html>
