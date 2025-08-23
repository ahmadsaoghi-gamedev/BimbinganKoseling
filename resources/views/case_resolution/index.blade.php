@extends('layouts.app')

@section('title', 'Daftar Kasus')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Daftar Kasus
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Kelola semua kasus konsultasi dan pengaduan
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('case-resolution.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Dashboard
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('case-resolution.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-48">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jenis Kasus
                            </label>
                            <select name="type" id="type" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="konsultasi" {{ $type === 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                                <option value="pengaduan" {{ $type === 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                                <option value="rekap" {{ $type === 'rekap' ? 'selected' : '' }}>Rekap Bimbingan</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status
                            </label>
                            <select name="status" id="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Terbuka</option>
                                <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                                <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>Terselesaikan</option>
                                <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-search mr-2"></i>
                                Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Konsultasi Section -->
                @if($type === 'all' || $type === 'konsultasi')
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-comments mr-2 text-blue-600"></i>
                        Konsultasi
                    </h3>
                    @if($konsultasi->count() > 0)
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($konsultasi as $item)
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
                                                {{ $item->siswa->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($item->isi_curhat, 100) }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $item->tgl_curhat ? $item->tgl_curhat->format('d M Y H:i') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->case_status_badge_class }}">
                                            {{ $item->case_status_text }}
                                        </span>
                                        @if($item->isResolved())
                                        <span class="text-xs text-green-600 dark:text-green-400">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $item->resolution_date ? $item->resolution_date->format('d M Y') : 'N/A' }}
                                        </span>
                                        @endif
                                        <div class="flex space-x-2">
                                            <a href="{{ route('case-resolution.show', ['konsultasi', $item->id]) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$item->isResolved())
                                            <a href="{{ route('case-resolution.resolve', ['konsultasi', $item->id]) }}" 
                                               class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300"
                                               title="Selesaikan Kasus">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @if($konsultasi->hasPages())
                        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-600">
                            {{ $konsultasi->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p>Tidak ada data konsultasi</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Pengaduan Section -->
                @if($type === 'all' || $type === 'pengaduan')
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                        Pengaduan
                    </h3>
                    @if($pengaduan->count() > 0)
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($pengaduan as $item)
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
                                                {{ $item->siswa->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $item->jenis_pengaduan }} - {{ Str::limit($item->laporan_pengaduan, 100) }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $item->tgl_pengaduan ? $item->tgl_pengaduan->format('d M Y') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->case_status_badge_class }}">
                                            {{ $item->case_status_text }}
                                        </span>
                                        @if($item->isResolved())
                                        <span class="text-xs text-green-600 dark:text-green-400">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $item->resolution_date ? $item->resolution_date->format('d M Y') : 'N/A' }}
                                        </span>
                                        @endif
                                        <div class="flex space-x-2">
                                            <a href="{{ route('case-resolution.show', ['pengaduan', $item->id]) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$item->isResolved())
                                            <a href="{{ route('case-resolution.resolve', ['pengaduan', $item->id]) }}" 
                                               class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300"
                                               title="Selesaikan Kasus">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @if($pengaduan->hasPages())
                        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-600">
                            {{ $pengaduan->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                        <p>Tidak ada data pengaduan</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Rekap Section -->
                @if($type === 'all' || $type === 'rekap')
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-clipboard-list mr-2 text-green-600"></i>
                        Rekap Bimbingan
                    </h3>
                    @if($rekap->count() > 0)
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($rekap as $item)
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
                                                {{ $item->siswa->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $item->jenis_bimbingan }} - {{ Str::limit($item->keterangan, 100) }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $item->tgl_bimbingan ? $item->tgl_bimbingan->format('d M Y') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->case_status_badge_class }}">
                                            {{ $item->case_status_text }}
                                        </span>
                                        @if($item->isResolved())
                                        <span class="text-xs text-green-600 dark:text-green-400">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $item->resolution_date ? $item->resolution_date->format('d M Y') : 'N/A' }}
                                        </span>
                                        @endif
                                        <div class="flex space-x-2">
                                            <a href="{{ route('rekap.edit', $item->id) }}" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$item->isResolved())
                                            <a href="{{ route('rekap.edit', $item->id) }}" 
                                               class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300"
                                               title="Selesaikan Kasus">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @if($rekap->hasPages())
                        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-600">
                            {{ $rekap->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                        <p>Tidak ada data rekap bimbingan</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
