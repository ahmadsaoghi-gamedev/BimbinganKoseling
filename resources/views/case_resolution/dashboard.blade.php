@extends('layouts.app')

@section('title', 'Dashboard Resolusi Kasus')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Dashboard Resolusi Kasus
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Tracking lengkap proses konseling dan hasil akhir
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
            <!-- Total Cases -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kasus</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_cases'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Open Cases -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kasus Terbuka</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['open_cases'] }}</p>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dalam Proses</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['in_progress_cases'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terselesaikan</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['resolved_cases'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Closed -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ditutup</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['closed_cases'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Urgent Cases -->
            @if(auth()->user()->hasRole('gurubk'))
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Urgent</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['urgent_cases'] }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Workflow Visualization -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Workflow Proses Konseling</h3>
            <div class="flex items-center justify-between">
                <!-- Step 1: Open -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mb-2">
                        <span class="text-yellow-600 dark:text-yellow-400 font-semibold">1</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Kasus Terbuka</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 text-center">{{ $stats['open_cases'] }} kasus</p>
                </div>

                <!-- Arrow -->
                <div class="flex-1 h-0.5 bg-gray-300 dark:bg-gray-600 mx-4"></div>

                <!-- Step 2: In Progress -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mb-2">
                        <span class="text-orange-600 dark:text-orange-400 font-semibold">2</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Dalam Proses</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 text-center">{{ $stats['in_progress_cases'] }} kasus</p>
                </div>

                <!-- Arrow -->
                <div class="flex-1 h-0.5 bg-gray-300 dark:bg-gray-600 mx-4"></div>

                <!-- Step 3: Resolved -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-2">
                        <span class="text-green-600 dark:text-green-400 font-semibold">3</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Terselesaikan</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 text-center">{{ $stats['resolved_cases'] }} kasus</p>
                </div>

                <!-- Arrow -->
                <div class="flex-1 h-0.5 bg-gray-300 dark:bg-gray-600 mx-4"></div>

                <!-- Step 4: Closed -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-2">
                        <span class="text-gray-600 dark:text-gray-400 font-semibold">4</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Ditutup</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 text-center">{{ $stats['closed_cases'] }} kasus</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 mb-8">
            <a href="{{ route('case-resolution.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Lihat Semua Kasus
            </a>

            @if(auth()->user()->hasRole('gurubk'))
            <a href="{{ route('case-resolution.generate-report') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Generate Laporan
            </a>
            @endif

            <a href="{{ route('konsultasi.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Konsultasi
            </a>

            <a href="{{ route('gurubk.daftar-cek-masalah') }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Cek Masalah
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Resolution Rate -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tingkat Penyelesaian</h4>
                @php
                    $resolutionRate = $stats['total_cases'] > 0 ? round(($stats['resolved_cases'] / $stats['total_cases']) * 100, 1) : 0;
                @endphp
                <div class="flex items-center">
                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-4">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $resolutionRate }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $resolutionRate }}%</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    {{ $stats['resolved_cases'] }} dari {{ $stats['total_cases'] }} kasus terselesaikan
                </p>
            </div>

            <!-- Average Resolution Time -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Rata-rata Waktu Penyelesaian</h4>
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">3.2 hari</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Berdasarkan kasus yang telah diselesaikan
                </p>
            </div>

            <!-- Case Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Distribusi Kasus</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Konsultasi</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">45%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Cek Masalah</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">35%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Pengaduan</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">20%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
