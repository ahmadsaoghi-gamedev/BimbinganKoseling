@extends('layouts.app')

@section('title', 'Dashboard Solusi Kasus')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Dashboard Solusi Kasus
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Tracking dan pengelolaan solusi akhir untuk curhatan dan pengaduan
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('case-resolution.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-list mr-2"></i>
                            Lihat Semua Kasus
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-800">
                                <i class="fas fa-folder-open text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Kasus</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $stats['total_cases'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg border border-yellow-200 dark:border-yellow-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-800">
                                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Kasus Terbuka</p>
                                <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ ($stats['open_konsultasi'] ?? 0) + ($stats['open_pengaduan'] ?? 0) + ($stats['open_rekap'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-800">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Terselesaikan</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ ($stats['resolved_konsultasi'] ?? 0) + ($stats['resolved_pengaduan'] ?? 0) + ($stats['resolved_rekap'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-800">
                                <i class="fas fa-calendar-check text-purple-600 dark:text-purple-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Bulan Ini</p>
                                <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $stats['resolved_this_month'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Case Type Distribution -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Distribusi Jenis Kasus
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Konsultasi</h4>
                                <span class="text-2xl font-bold text-blue-600">{{ $caseTypeDistribution['konsultasi'] ?? 0 }}</span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Terbuka</span>
                                    <span class="font-medium">{{ $stats['open_konsultasi'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Sedang Diproses</span>
                                    <span class="font-medium">{{ $stats['in_progress_konsultasi'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Terselesaikan</span>
                                    <span class="font-medium">{{ $stats['resolved_konsultasi'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                                                 <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6">
                             <div class="flex items-center justify-between mb-4">
                                 <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pengaduan</h4>
                                 <span class="text-2xl font-bold text-red-600">{{ $caseTypeDistribution['pengaduan'] ?? 0 }}</span>
                             </div>
                             <div class="space-y-2">
                                 <div class="flex justify-between text-sm">
                                     <span class="text-gray-600 dark:text-gray-400">Terbuka</span>
                                     <span class="font-medium">{{ $stats['open_pengaduan'] ?? 0 }}</span>
                                 </div>
                                 <div class="flex justify-between text-sm">
                                     <span class="text-gray-600 dark:text-gray-400">Sedang Diproses</span>
                                     <span class="font-medium">{{ $stats['in_progress_pengaduan'] ?? 0 }}</span>
                                 </div>
                                 <div class="flex justify-between text-sm">
                                     <span class="text-gray-600 dark:text-gray-400">Terselesaikan</span>
                                     <span class="font-medium">{{ $stats['resolved_pengaduan'] ?? 0 }}</span>
                                 </div>
                             </div>
                         </div>

                         <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6">
                             <div class="flex items-center justify-between mb-4">
                                 <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Rekap Bimbingan</h4>
                                 <span class="text-2xl font-bold text-green-600">{{ $caseTypeDistribution['rekap'] ?? 0 }}</span>
                             </div>
                             <div class="space-y-2">
                                 <div class="flex justify-between text-sm">
                                     <span class="text-gray-600 dark:text-gray-400">Terbuka</span>
                                     <span class="font-medium">{{ $stats['open_rekap'] ?? 0 }}</span>
                                 </div>
                                 <div class="flex justify-between text-sm">
                                     <span class="text-gray-600 dark:text-gray-400">Sedang Diproses</span>
                                     <span class="font-medium">{{ $stats['in_progress_rekap'] ?? 0 }}</span>
                                 </div>
                                 <div class="flex justify-between text-sm">
                                     <span class="text-gray-600 dark:text-gray-400">Terselesaikan</span>
                                     <span class="font-medium">{{ $stats['resolved_rekap'] ?? 0 }}</span>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>

                                 <!-- Recent Cases Needing Attention -->
                 <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Recent Konsultasi -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-comments mr-2 text-blue-600"></i>
                            Konsultasi Terbaru
                        </h3>
                        <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($recentKonsultasi as $konsultasi)
                                <li class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $konsultasi->siswa->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ Str::limit($konsultasi->isi_curhat, 50) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $konsultasi->case_status_badge_class }}">
                                                {{ $konsultasi->case_status_text }}
                                            </span>
                                            <a href="{{ route('case-resolution.show', ['konsultasi', $konsultasi->id]) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada konsultasi yang perlu perhatian
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Pengaduan -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                            Pengaduan Terbaru
                        </h3>
                        <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($recentPengaduan as $pengaduan)
                                <li class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-800 flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $pengaduan->siswa->nama ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $pengaduan->jenis_pengaduan }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pengaduan->case_status_badge_class }}">
                                                {{ $pengaduan->case_status_text }}
                                            </span>
                                            <a href="{{ route('case-resolution.show', ['pengaduan', $pengaduan->id]) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada pengaduan yang perlu perhatian
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Rekap -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-clipboard-list mr-2 text-green-600"></i>
                            Rekap Bimbingan Terbaru
                        </h3>
                        <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($recentRekap as $rekap)
                                <li class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center">
                                                    <i class="fas fa-clipboard-list text-green-600 dark:text-green-400"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $rekap->siswa->nama ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ Str::limit($rekap->keterangan, 50) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rekap->case_status_badge_class }}">
                                                {{ $rekap->case_status_text }}
                                            </span>
                                            <a href="{{ route('rekap.edit', $rekap->id) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada rekap bimbingan yang perlu perhatian
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Resolved This Month -->
                @if($resolvedThisMonth->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-trophy mr-2 text-green-600"></i>
                        Kasus Terselesaikan Bulan Ini
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($resolvedThisMonth->take(6) as $case)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-600 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center">
                                            <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ $case->siswa->name ?? $case->siswa->nama ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $case->resolution_date ? $case->resolution_date->format('d M Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
