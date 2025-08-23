@extends('layouts.app')

@section('title', 'Detail Kasus')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Detail Kasus
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ ucfirst($type) }} - {{ $case->siswa->name ?? $case->siswa->nama ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('case-resolution.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        @if(!$case->isResolved())
                        <a href="{{ route('case-resolution.resolve', [$type, $case->id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-check mr-2"></i>
                            Selesaikan Kasus
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Case Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Case Details -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Informasi Kasus
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Jenis:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ ucfirst($type) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $case->case_status_badge_class }}">
                                    {{ $case->case_status_text }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tanggal Dibuat:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $type === 'konsultasi' ? ($case->tgl_curhat ? $case->tgl_curhat->format('d M Y H:i') : 'N/A') : ($case->tgl_pengaduan ? $case->tgl_pengaduan->format('d M Y') : 'N/A') }}
                                </span>
                            </div>
                            @if($case->isResolved())
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tanggal Selesai:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $case->resolution_date ? $case->resolution_date->format('d M Y H:i') : 'N/A' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Diselesaikan Oleh:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $case->resolvedBy->name ?? 'N/A' }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-user mr-2 text-green-600"></i>
                            Informasi Siswa
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Nama:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $case->siswa->name ?? $case->siswa->nama ?? 'N/A' }}
                                </span>
                            </div>
                            @if($type === 'pengaduan')
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">NIS:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $case->nis ?? 'N/A' }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Case Content -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                        Isi {{ ucfirst($type) }}
                    </h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        @if($type === 'konsultasi')
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $case->isi_curhat }}</p>
                        </div>
                        @else
                        <div class="space-y-4">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Jenis Pengaduan:</span>
                                <p class="text-gray-900 dark:text-gray-100">{{ $case->jenis_pengaduan }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Laporan:</span>
                                <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $case->laporan_pengaduan }}</p>
                            </div>
                            @if($case->gambar)
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Bukti:</span>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $case->gambar) }}" alt="Bukti Pengaduan" class="max-w-md rounded-lg shadow-md">
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Resolution Information -->
                @if($case->isResolved())
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        Solusi Akhir
                    </h3>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-6">
                        <div class="space-y-4">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Jenis Solusi:</span>
                                <p class="text-gray-900 dark:text-gray-100">{{ $case->resolution_type_text }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Solusi Akhir:</span>
                                <div class="mt-2 prose dark:prose-invert max-w-none">
                                    <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $case->final_resolution }}</p>
                                </div>
                            </div>
                            @if($case->resolution_notes)
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 font-medium">Catatan Tambahan:</span>
                                <div class="mt-2 prose dark:prose-invert max-w-none">
                                    <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $case->resolution_notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Conversations (for Konsultasi) -->
                @if($type === 'konsultasi' && $case->conversations->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-comments mr-2 text-blue-600"></i>
                        Riwayat Percakapan
                    </h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="space-y-4">
                            @foreach($case->conversations as $conversation)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $conversation->sender->name ?? 'N/A' }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $conversation->created_at ? $conversation->created_at->format('d M Y H:i') : 'N/A' }}
                                    </span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $conversation->message }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Status Update Form (for non-resolved cases) -->
                @if(!$case->isResolved())
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-edit mr-2 text-orange-600"></i>
                        Update Status
                    </h3>
                    <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-lg p-6">
                        <form method="POST" action="{{ route('case-resolution.update-status', [$type, $case->id]) }}">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center space-x-4">
                                <select name="case_status" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="open" {{ $case->case_status === 'open' ? 'selected' : '' }}>Terbuka</option>
                                    <option value="in_progress" {{ $case->case_status === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                                    <option value="resolved" {{ $case->case_status === 'resolved' ? 'selected' : '' }}>Terselesaikan</option>
                                    <option value="closed" {{ $case->case_status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                                </select>
                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
