@extends('layouts.app')

@section('title', 'Quick Count - Admin Dashboard')

@section('content')
  <div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard & Quick Count</h1>
        <p class="text-gray-500">Pantau perolehan suara secara real-time.</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-sm text-gray-500" id="last-updated">Updated: Just now</span>
        <button onclick="window.location.reload()"
          class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-200 transition flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
            </path>
          </svg>
          Refresh Data
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4">
        <div class="bg-blue-100 p-4 rounded-full text-blue-600">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
          </svg>
        </div>
        <div>
          <p class="text-gray-500 text-sm font-medium">Total Pemilih (Siswa)</p>
          <h3 class="text-3xl font-bold text-gray-800">{{ $totalSiswa }}</h3>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4">
        <div class="bg-green-100 p-4 rounded-full text-green-600">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
            </path>
          </svg>
        </div>
        <div>
          <p class="text-gray-500 text-sm font-medium">Suara Masuk</p>
          <h3 class="text-3xl font-bold text-gray-800">{{ $totalSuara }}</h3>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center gap-4">
        <div class="bg-purple-100 p-4 rounded-full text-purple-600">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
          </svg>
        </div>
        <div>
          <p class="text-gray-500 text-sm font-medium">Partisipasi</p>
          <h3 class="text-3xl font-bold text-gray-800">{{ $partisipasi }}%</h3>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Grafik Perolehan Suara</h3>
        <div class="relative h-80 w-full">
          <canvas id="voteChart"></canvas>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Perolehan</h3>
        <div class="space-y-6">
          @foreach ($candidates as $candidate)
            @php
              $persen = $totalSuara > 0 ? round(($candidate->votes_count / $totalSuara) * 100, 1) : 0;
            @endphp
            <div>
              <div class="flex justify-between items-end mb-1">
                <span class="text-sm font-bold text-gray-700">
                  {{ $candidate->nomor_urut }}. {{ $candidate->nama_paslon }}
                </span>
                <span class="text-sm font-bold text-blue-600">{{ $candidate->votes_count }} Suara</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-1000"
                  style="width: {{ $persen }}%"></div>
              </div>
              <p class="text-xs text-gray-400 mt-1 text-right">{{ $persen }}%</p>
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
      type: 'bar', // Bisa ganti 'pie' atau 'doughnut'
      data: {
        labels: labels,
        datasets: [{
          label: 'Jumlah Suara',
          data: dataVotes,
          backgroundColor: [
            'rgba(59, 130, 246, 0.7)', // Biru
            'rgba(16, 185, 129, 0.7)', // Hijau
            'rgba(245, 158, 11, 0.7)', // Kuning
            'rgba(239, 68, 68, 0.7)', // Merah
          ],
          borderColor: [
            'rgb(59, 130, 246)',
            'rgb(16, 185, 129)',
            'rgb(245, 158, 11)',
            'rgb(239, 68, 68)',
          ],
          borderWidth: 2,
          borderRadius: 5,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            } // Agar angka di sumbu Y bulat (bukan desimal)
          }
        },
        plugins: {
          legend: {
            display: false
          } // Sembunyikan legenda karena sudah jelas
        }
      }
    });

    // 3. Auto Refresh Halaman setiap 30 Detik (Real-time sederhana)
    setTimeout(function() {
      window.location.reload(1);
    }, 30000);

    // Update waktu terakhir
    document.getElementById('last-updated').innerText = 'Updated: ' + new Date().toLocaleTimeString();
  </script>
@endpush
