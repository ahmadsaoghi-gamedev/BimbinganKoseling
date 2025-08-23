@extends('layouts.app')

@section('title', 'Detail Laporan Curhat')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Detail Laporan Curhat
                    </h2>
                    <div class="flex space-x-3">
                        @hasrole('gurubk|admin')
                        <a href="{{ route('curhat-reports.edit', $curhatReport) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        @endhasrole
                        <a href="{{ route('curhat-reports.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        {{ $curhatReport->status_progress == 'selesai' ? 'bg-green-100 text-green-800' : 
                           ($curhatReport->status_progress == 'dalam_proses' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $curhatReport->status_progress ?? 'pending')) }}
                    </span>
                </div>

                <!-- Main Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Siswa Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Informasi Siswa</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Nama:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->siswa->nama ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">NIS:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->siswa->nis ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Kelas:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->siswa->kelas ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Jurusan:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->siswa->jurusan ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Report Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Informasi Laporan</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Kategori Masalah:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ ucfirst($curhatReport->kategori_masalah ?? 'N/A') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Tingkat Urgensi:</span>
                                <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $curhatReport->tingkat_urgensi == 'tinggi' ? 'bg-red-100 text-red-800' : 
                                       ($curhatReport->tingkat_urgensi == 'sedang' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($curhatReport->tingkat_urgensi ?? 'N/A') }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Tanggal Curhat:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->tanggal_curhat ? $curhatReport->tanggal_curhat->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Jumlah Sesi:</span>
                                <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->jumlah_sesi ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analysis and Actions -->
                <div class="space-y-6">
                    <!-- Analisis Masalah -->
                    @if($curhatReport->analisis_masalah)
                    <div>
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Analisis Masalah</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-gray-900 dark:text-white">{{ $curhatReport->analisis_masalah }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Tindakan yang Dilakukan -->
                    @if($curhatReport->tindakan_yang_dilakukan)
                    <div>
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Tindakan yang Dilakukan</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-gray-900 dark:text-white">{{ $curhatReport->tindakan_yang_dilakukan }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Hasil Tindakan -->
                    @if($curhatReport->hasil_tindakan)
                    <div>
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Hasil Tindakan</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-gray-900 dark:text-white">{{ $curhatReport->hasil_tindakan }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Rekomendasi Lanjutan -->
                    @if($curhatReport->rekomendasi_lanjutan)
                    <div>
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Rekomendasi Lanjutan</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-gray-900 dark:text-white">{{ $curhatReport->rekomendasi_lanjutan }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Catatan Khusus -->
                    @if($curhatReport->catatan_khusus)
                    <div>
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Catatan Khusus</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-gray-900 dark:text-white">{{ $curhatReport->catatan_khusus }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Timestamps -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Timeline</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Dibuat:</span>
                            <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($curhatReport->tanggal_analisis)
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Dianalisis:</span>
                            <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->tanggal_analisis->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        @if($curhatReport->tanggal_selesai)
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Selesai:</span>
                            <span class="ml-2 text-gray-900 dark:text-white">{{ $curhatReport->tanggal_selesai->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                @hasrole('gurubk|admin')
                <div class="mt-8 flex justify-end space-x-3">
                    <form action="{{ route('curhat-reports.destroy', $curhatReport) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                </div>
                @endhasrole
            </div>
        </div>
    </div>
</div>
@endsection
