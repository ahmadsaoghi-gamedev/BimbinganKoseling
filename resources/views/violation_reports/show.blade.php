@extends('layouts.app')

@section('title', 'Detail Rekap Pelanggaran')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Detail Rekap Pelanggaran
                    </h2>
                    <div class="flex space-x-2">
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk') || auth()->user()->hasRole('kesiswaan'))
                        <a href="{{ route('violation-reports.edit', $violationReport) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        @endif
                        <a href="{{ route('violation-reports.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $violationReport->status_badge_class }}">
                        {{ $violationReport->status_text }}
                    </span>
                </div>

                <!-- Main Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Student Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informasi Siswa</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Siswa:</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $violationReport->siswa->nama ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIS:</label>
                                <p class="text-gray-900 dark:text-white">{{ $violationReport->siswa->nis ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas:</label>
                                <p class="text-gray-900 dark:text-white">{{ $violationReport->siswa->kelas ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Violation Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informasi Pelanggaran</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori:</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $violationReport->category_text }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poin Sebelumnya:</label>
                                <p class="text-gray-900 dark:text-white">{{ $violationReport->points_before ?? 0 }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poin Setelah:</label>
                                <p class="text-gray-900 dark:text-white font-bold text-red-600">{{ $violationReport->points_after ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Violation Details -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Detail Pelanggaran</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi Pelanggaran:</label>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $violationReport->violation_description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sanksi:</label>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $violationReport->sanctions ?? 'Tidak ada sanksi' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tindakan Pencegahan:</label>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $violationReport->prevention_actions ?? 'Tidak ada tindakan pencegahan' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Khusus:</label>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $violationReport->special_notes ?? 'Tidak ada catatan khusus' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Notification Status -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Status Notifikasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $violationReport->parent_notification_sent ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $violationReport->parent_notification_sent ? '✓ Notifikasi Orang Tua Terkirim' : '✗ Notifikasi Orang Tua Belum Terkirim' }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $violationReport->summon_letter_sent ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $violationReport->summon_letter_sent ? '✓ Surat Pemanggilan Terkirim' : '✗ Surat Pemanggilan Belum Terkirim' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informasi Waktu</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pelanggaran:</label>
                            <p class="text-gray-900 dark:text-white">{{ $violationReport->violation_date ? $violationReport->violation_date->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dibuat pada:</label>
                            <p class="text-gray-900 dark:text-white">{{ $violationReport->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Terakhir diperbarui:</label>
                            <p class="text-gray-900 dark:text-white">{{ $violationReport->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dibuat oleh:</label>
                            <p class="text-gray-900 dark:text-white">{{ $violationReport->guruBk->nama ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Student Statistics -->
                @if(isset($studentStats))
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg mt-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Statistik Siswa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $studentStats['total_violations'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Total Pelanggaran</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $studentStats['total_points'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Total Poin</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $studentStats['this_month'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Pelanggaran Bulan Ini</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
