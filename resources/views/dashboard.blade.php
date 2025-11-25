@extends('layouts.app')

@section('title', 'E-Voting SMKN 4 Tangerang')

@section('content')
    <script src="//unpkg.com/alpinejs" defer></script>

    <div class="relative bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl shadow-lg overflow-hidden mb-8 text-white">
        <div class="absolute inset-0 bg-black opacity-10 pattern-dots"></div>
        <div class="relative z-10 px-6 py-8 md:py-10 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight mb-1">
                    Pemilihan Ketua OSIS
                </h1>
                <p class="text-blue-100 text-sm md:text-lg font-light">
                    Masa Bakti 2025/2026 - SMKN 4 Tangerang
                </p>
                <div
                    class="mt-3 inline-block bg-blue-800/50 px-3 py-1 rounded-full text-xs font-medium border border-blue-500">
                    👋 Halo, {{ Auth::user()->name }}!
                </div>
            </div>
            <div class="hidden md:block opacity-80 transform rotate-12">
                <svg class="w-20 h-20 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd"
                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    @if (Auth::user()->has_voted)
        <div class="max-w-3xl mx-auto mb-8 animate-fade-in-down">
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm flex items-center gap-4">
                <div class="bg-green-500 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-green-800">Terima Kasih!</h3>
                    <p class="text-green-700 text-sm">Suara Anda telah direkam. Pilihan dikunci.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-12">

        @foreach ($candidates as $candidate)
            <div x-data="{ activeTab: 'visi' }"
                class="group relative bg-white rounded-2xl shadow hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden flex flex-col {{ Auth::user()->has_voted ? 'opacity-60 grayscale' : '' }}">

                <div class="relative h-100 overflow-hidden bg-gray-100">
                    @if ($candidate->foto)
                        <img src="{{ asset('storage/' . $candidate->foto) }}" alt="{{ $candidate->nama_paslon }}"
                            class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <span class="text-sm font-medium">No Image</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent"></div>

                    <div class="absolute bottom-0 left-0 w-full p-4 text-white">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-blue-600 w-10 h-10 flex items-center justify-center rounded-full font-bold text-lg shadow border-2 border-white">
                                {{ $candidate->nomor_urut }}
                            </div>
                            <h2 class="text-xl font-bold leading-tight shadow-black drop-shadow-md">
                                {{ $candidate->nama_paslon }}
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="flex-grow flex flex-col">

                    <div class="flex border-b border-gray-100 bg-gray-50/50">
                        <button @click="activeTab = 'visi'"
                            :class="activeTab === 'visi' ? 'text-blue-600 border-b-2 border-blue-600 bg-white' :
                                'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-3 text-sm font-bold uppercase tracking-wide transition-colors focus:outline-none">
                            Visi
                        </button>
                        <button @click="activeTab = 'misi'"
                            :class="activeTab === 'misi' ? 'text-purple-600 border-b-2 border-purple-600 bg-white' :
                                'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-3 text-sm font-bold uppercase tracking-wide transition-colors focus:outline-none">
                            Misi
                        </button>
                        <button @click="activeTab = 'program'"
                            :class="activeTab === 'program' ? 'text-amber-600 border-b-2 border-amber-600 bg-white' :
                                'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-3 text-sm font-bold uppercase tracking-wide transition-colors focus:outline-none">
                            Program
                        </button>
                    </div>

                    <div class="p-5 h-48 overflow-y-auto bg-white custom-scrollbar">

                        <div x-show="activeTab === 'visi'" x-transition.opacity>
                            <div class="flex gap-2 mb-2">
                                <span
                                    class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded">VISI</span>
                            </div>
                            <div class="text-gray-700 text-sm leading-relaxed italic prose max-w-none">
                                {!! $candidate->visi !!}
                            </div>
                        </div>

                        <div x-show="activeTab === 'misi'" x-transition.opacity style="display: none;">
                            <div class="flex gap-2 mb-2">
                                <span
                                    class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded">MISI</span>
                            </div>
                            <div class="text-gray-700 text-sm leading-relaxed prose max-w-none">
                                {!! $candidate->misi !!}
                            </div>
                        </div>

                        <div x-show="activeTab === 'program'" x-transition.opacity style="display: none;">
                            <div class="flex gap-2 mb-2">
                                <span
                                    class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded">UNGGULAN</span>
                            </div>
                            <div class="bg-amber-50 border border-amber-100 rounded-lg p-3">
                                <div class="text-gray-800 text-sm font-medium prose max-w-none">
                                    {!! $candidate->program_unggulan !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-100">
                    @if (Auth::user()->has_voted)
                        <button disabled
                            class="w-full py-3 bg-gray-300 text-gray-500 font-bold rounded-lg text-sm cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            SUDAH MEMILIH
                        </button>
                    @elseif (!$isVotingActive)
                        <button disabled
                            class="w-full py-3 bg-red-100 text-red-500 font-bold rounded-lg text-sm cursor-not-allowed flex items-center justify-center gap-2 border border-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            VOTING BELUM DIBUKA / DITUTUP
                        </button>
                    @else
                        <form id="vote-form-{{ $candidate->id }}" action="{{ route('vote.store', $candidate->id) }}"
                            method="POST">
                            @csrf
                            <button type="button"
                                onclick="confirmVote({{ $candidate->id }}, '{{ $candidate->nomor_urut }}', '{{ $candidate->nama_paslon }}')"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                PILIH KANDIDAT
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        @endforeach

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            bg-gray-100;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function confirmVote(id, nomor, nama) {
            Swal.fire({
                title: 'Pilih No. ' + nomor + '?',
                html: "Anda akan memilih <b>" + nama + "</b>.<br>Pilihan tidak dapat diubah setelah dikirim.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#d1d5db',
                cancelButtonText: '<span class="text-gray-700">Batal</span>',
                confirmButtonText: 'Ya, Saya Yakin!',
                reverseButtons: true,
                focusConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('vote-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
