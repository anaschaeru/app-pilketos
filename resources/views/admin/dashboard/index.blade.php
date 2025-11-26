@extends('layouts.app')

@section('title', 'Quick Count - Admin Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">

            <div class="w-full md:w-auto">
                <h1 class="text-3xl font-bold text-gray-800 leading-tight">Dashboard & Quick Count</h1>
                <p class="text-gray-500 mb-2">Pantau perolehan suara secara real-time.</p>
                <div class="inline-flex items-center gap-2 bg-gray-100 px-3 py-1 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-xs font-mono text-gray-600" id="last-updated">Updating...</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <form id="toggle-voting-form" action="{{ route('admin.voting.toggle') }}" method="POST"
                    class="w-full sm:w-auto">
                    @csrf
                    <button type="button" onclick="confirmToggleVoting({{ $isVotingActive ? 'true' : 'false' }})"
                        class="w-full sm:w-auto flex items-center justify-center gap-3 px-5 py-3 rounded-xl border transition-all shadow-sm
        {{ $isVotingActive ? 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100' : 'bg-red-50 border-red-200 text-red-700 hover:bg-red-100' }}">

                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $isVotingActive ? 'bg-green-500' : 'bg-red-500 hidden' }}"></span>
                            <span
                                class="relative inline-flex rounded-full h-3 w-3 {{ $isVotingActive ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        </span>

                        <span class="font-bold text-sm uppercase">
                            {{ $isVotingActive ? 'Voting Dibuka (ON)' : 'Voting Ditutup (OFF)' }}
                        </span>
                    </button>
                </form>

                <button onclick="window.location.reload()"
                    class="w-full sm:w-auto bg-white border border-gray-200 text-gray-600 px-5 py-3 rounded-xl text-sm font-bold hover:bg-gray-50 hover:text-blue-600 transition flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">

            <div
                class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4 hover:shadow-xl transition-shadow">
                <div class="bg-blue-100 p-4 rounded-2xl text-blue-600 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pemilih</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-800">{{ $totalSiswa }}</h3>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4 hover:shadow-xl transition-shadow">
                <div class="bg-green-100 p-4 rounded-2xl text-green-600 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Suara Masuk</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-800">{{ $totalSuara }}</h3>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4 hover:shadow-xl transition-shadow sm:col-span-2 md:col-span-1">
                <div class="bg-purple-100 p-4 rounded-2xl text-purple-600 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Partisipasi</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-800">{{ $partisipasi }}%</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
                    Grafik Perolehan Suara
                </h3>
                <div class="relative h-64 md:h-80 w-full">
                    <canvas id="voteChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-green-600 rounded-full"></span>
                    Detail Kandidat
                </h3>
                <div class="space-y-6 overflow-y-auto max-h-[400px] custom-scrollbar pr-2">
                    @foreach ($candidates as $candidate)
                        @php
                            $persen = $totalSuara > 0 ? round(($candidate->votes_count / $totalSuara) * 100, 1) : 0;
                        @endphp
                        <div class="group">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">
                                    {{ $candidate->nomor_urut }}. {{ explode('&', $candidate->nama_paslon)[0] }}
                                </span>
                                <span
                                    class="text-sm font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ $candidate->votes_count }}
                                    Suara</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-1000 ease-out relative"
                                    style="width: {{ $persen }}%">
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1 text-right font-mono">{{ $persen }}%</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Siapkan Data dari PHP ke JS
        const labels = {!! json_encode($chartLabels) !!};
        const dataVotes = {!! json_encode($chartData) !!};

        // 2. Konfigurasi Chart
        const ctx = document.getElementById('voteChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Suara',
                    data: dataVotes,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)', // Blue
                        'rgba(16, 185, 129, 0.8)', // Green
                        'rgba(249, 115, 22, 0.8)', // Orange
                        'rgba(239, 68, 68, 0.8)', // Red
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(249, 115, 22)',
                        'rgb(239, 68, 68)',
                    ],
                    borderWidth: 0,
                    borderRadius: 8, // Rounded bar corners
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif",
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 14,
                            family: "'Inter', sans-serif"
                        },
                        bodyFont: {
                            size: 13,
                            family: "'Inter', sans-serif"
                        }
                    }
                }
            }
        });

        // 3. Auto Refresh setiap 30 Detik
        setTimeout(function() {
            window.location.reload(1);
        }, 30000);

        // Update waktu terakhir
        document.getElementById('last-updated').innerText = new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    </script>
    <script>
        function confirmToggleVoting(isActive) {
            // Tentukan pesan dan warna berdasarkan status saat ini
            let action = isActive ? 'MENUTUP' : 'MEMBUKA';
            let textMessage = isActive ?
                "Siswa tidak akan bisa memilih lagi jika voting ditutup." :
                "Siswa akan dapat segera melakukan pemilihan.";
            let confirmColor = isActive ? '#d33' : '#10b981'; // Merah jika mau tutup, Hijau jika mau buka
            let iconType = isActive ? 'warning' : 'question';

            Swal.fire({
                title: 'Yakin ingin ' + action + ' Voting?',
                text: textMessage,
                icon: iconType,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Lakukan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form secara manual jika user klik YA
                    document.getElementById('toggle-voting-form').submit();
                }
            })
        }
    </script>
@endpush
