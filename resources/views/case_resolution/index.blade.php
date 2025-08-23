@extends('layouts.app')

@section('title', 'Daftar Kasus')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Daftar Kasus
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Tracking lengkap semua kasus konseling dan bimbingan
            </p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('case-resolution.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari</label>
                    <input type="text" name="search" id="search" value="{{ $search }}" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Cari nama siswa atau isi kasus...">
                </div>

                <!-- Case Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kasus</label>
                    <select name="type" id="type" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <option value="all" {{ $caseType === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                        <option value="konsultasi" {{ $caseType === 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                        <option value="cek_masalah" {{ $caseType === 'cek_masalah' ? 'selected' : '' }}>Cek Masalah</option>
                        <option value="pengaduan" {{ $caseType === 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Terbuka</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>Terselesaikan</option>
                        <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Cases List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Daftar Kasus ({{ $cases->count() }} kasus)
                </h3>
            </div>

            @if($cases->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($cases as $case)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Case Header -->
                            <div class="flex items-center space-x-3 mb-3">
                                <!-- Case Type Icon -->
                                <div class="flex-shrink-0">
                                    @if($case->case_type === 'konsultasi')
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        </div>
                                    @elseif($case->case_type === 'cek_masalah')
                                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                    @elseif($case->case_type === 'pengaduan')
                                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Case Info -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            @if($case->case_type === 'konsultasi')
                                                {{ $case->user->name ?? 'N/A' }}
                                            @elseif($case->case_type === 'cek_masalah')
                                                {{ $case->siswa->nama ?? 'N/A' }}
                                            @elseif($case->case_type === 'pengaduan')
                                                {{ $case->laporan_pengaduan ?? 'N/A' }}
                                            @endif
                                        </h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($case->case_type === 'konsultasi')
                                                {{ $case->case_status === 'open' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($case->case_status === 'in_progress' ? 'bg-orange-100 text-orange-800' : 
                                                   ($case->case_status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}
                                            @elseif($case->case_type === 'cek_masalah')
                                                {{ $case->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($case->status === 'follow_up' ? 'bg-orange-100 text-orange-800' : 
                                                   ($case->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}
                                            @elseif($case->case_type === 'pengaduan')
                                                {{ $case->case_status === 'open' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($case->case_status === 'in_progress' ? 'bg-orange-100 text-orange-800' : 
                                                   ($case->case_status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}
                                            @endif">
                                            @if($case->case_type === 'konsultasi')
                                                {{ $case->case_status === 'open' ? 'Terbuka' : 
                                                   ($case->case_status === 'in_progress' ? 'Dalam Proses' : 
                                                   ($case->case_status === 'resolved' ? 'Terselesaikan' : 'Ditutup')) }}
                                            @elseif($case->case_type === 'cek_masalah')
                                                {{ $case->status === 'pending' ? 'Menunggu Review' : 
                                                   ($case->status === 'follow_up' ? 'Tindak Lanjut' : 
                                                   ($case->status === 'completed' ? 'Selesai' : 'Unknown')) }}
                                            @elseif($case->case_type === 'pengaduan')
                                                {{ $case->case_status === 'open' ? 'Terbuka' : 
                                                   ($case->case_status === 'in_progress' ? 'Dalam Proses' : 
                                                   ($case->case_status === 'resolved' ? 'Terselesaikan' : 'Ditutup')) }}
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Case Description -->
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        @if($case->case_type === 'konsultasi')
                                            {{ Str::limit($case->isi_curhat, 150) }}
                                        @elseif($case->case_type === 'cek_masalah')
                                            {{ Str::limit($case->deskripsi_tambahan, 150) }}
                                        @elseif($case->case_type === 'pengaduan')
                                            {{ Str::limit($case->laporan_pengaduan, 150) }}
                                        @endif
                                    </p>

                                    <!-- Case Details -->
                                    <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                        <span>
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $case->created_at->format('d M Y H:i') }}
                                        </span>
                                        @if($case->case_type === 'cek_masalah')
                                        <span>
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            {{ ucfirst($case->tingkat_urgensi) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('case-resolution.show', [$case->id, $case->case_type]) }}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </a>

                            @if(auth()->user()->hasRole('gurubk'))
                            <a href="{{ route('case-resolution.show', [$case->id, $case->case_type]) }}?action=update" 
                               class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Update
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada kasus</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada kasus yang ditemukan dengan filter yang dipilih.
                </p>
                <div class="mt-6">
                    <a href="{{ route('case-resolution.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset Filter
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
