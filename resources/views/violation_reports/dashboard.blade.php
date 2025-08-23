@extends('layouts.app')

@section('title', 'Dashboard Rekap Pelanggaran')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Dashboard Rekap Pelanggaran
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Ringkasan dan statistik pelanggaran siswa
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('violation-reports.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-list mr-2"></i>
                            Lihat Semua
                        </a>
                        <a href="{{ route('violation-reports.export') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-download mr-2"></i>
                            Export
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-800">
                                <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Laporan</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $stats['total_reports'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg border border-yellow-200 dark:border-yellow-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-800">
                                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Menunggu</p>
                                <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $stats['pending_reports'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 dark:bg-red-900/20 p-6 rounded-lg border border-red-200 dark:border-red-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 dark:bg-red-800">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-red-600 dark:text-red-400">Urgent</p>
                                <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ $stats['high_points_violations'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-800">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Selesai</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $stats['completed_reports'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Violations -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-history mr-2"></i>
                        Pelanggaran Terbaru
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse($recentViolations as $violation)
                            <li class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600 dark:text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $violation->siswa->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $violation->violation_description ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $violation->status_badge_class }}">
                                            {{ $violation->status_text }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $violation->points_after ?? 0 }} poin
                                        </span>
                                        <a href="{{ route('violation-reports.show', $violation->id) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada pelanggaran terbaru
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Needs Attention -->
                @if($needsAttention->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-exclamation-circle mr-2 text-yellow-600"></i>
                        Perlu Perhatian
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($needsAttention as $violation)
                            <li class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $violation->siswa->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $violation->violation_description ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $violation->status_text }}
                                        </span>
                                        <a href="{{ route('violation-reports.edit', $violation->id) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Needs Parent Notification -->
                @if($needsParentNotification->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-bell mr-2 text-red-600"></i>
                        Perlu Notifikasi Orang Tua
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($needsParentNotification as $violation)
                            <li class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $violation->siswa->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Total poin: {{ $violation->points_after ?? 0 }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Perlu Notifikasi
                                        </span>
                                        <button class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Category Distribution -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Distribusi Kategori Pelanggaran
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($categoryStats as $category)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-600 rounded-lg">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ ucfirst($category->category ?? 'N/A') }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $category->total ?? 0 }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Violating Students -->
                @if($topViolatingStudents->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-trophy mr-2"></i>
                        Siswa dengan Pelanggaran Tertinggi
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                @foreach($topViolatingStudents as $index => $student)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100 mr-3">
                                            #{{ $index + 1 }}
                                        </span>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $student->siswa->nama ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $student->violation_count ?? 0 }} pelanggaran
                                        </span>
                                        <span class="text-sm font-medium text-red-600 dark:text-red-400">
                                            {{ $student->total_points ?? 0 }} poin
                                        </span>
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
