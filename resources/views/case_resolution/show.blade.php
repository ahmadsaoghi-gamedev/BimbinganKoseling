@extends('layouts.app')

@section('title', 'Detail Kasus')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Detail Kasus {{ ucfirst($type) }}
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Informasi lengkap dan progress penyelesaian kasus
                    </p>
                </div>
                <a href="{{ route('case-resolution.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Case Information -->
            <div class="lg:col-span-2">
                <!-- Basic Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Kasus</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Siswa</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($type === 'konsultasi')
                                    {{ $case->user->name ?? 'N/A' }}
                                @elseif($type === 'cek_masalah')
                                    {{ $case->siswa->nama ?? 'N/A' }}
                                @elseif($type === 'pengaduan')
                                    {{ $case->siswa->nama ?? 'N/A' }}
                                @elseif($type === 'rekap')
                                    {{ $case->siswa->nama ?? 'N/A' }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $case->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status Kasus</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($type === 'konsultasi')
                                        {{ $case->case_status === 'open' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($case->case_status === 'in_progress' ? 'bg-orange-100 text-orange-800' : 
                                           ($case->case_status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}
                                    @elseif($type === 'cek_masalah')
                                        {{ $case->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($case->status === 'follow_up' ? 'bg-orange-100 text-orange-800' : 
                                           ($case->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}
                                    @elseif($type === 'pengaduan')
                                        {{ $case->case_status === 'open' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($case->case_status === 'in_progress' ? 'bg-orange-100 text-orange-800' : 
                                           ($case->case_status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}
                                    @endif">
                                    @if($type === 'konsultasi')
                                        {{ $case->case_status === 'open' ? 'Terbuka' : 
                                           ($case->case_status === 'in_progress' ? 'Dalam Proses' : 
                                           ($case->case_status === 'resolved' ? 'Terselesaikan' : 'Ditutup')) }}
                                    @elseif($type === 'cek_masalah')
                                        {{ $case->status === 'pending' ? 'Menunggu Review' : 
                                           ($case->status === 'follow_up' ? 'Tindak Lanjut' : 
                                           ($case->status === 'completed' ? 'Selesai' : 'Unknown')) }}
                                    @elseif($type === 'pengaduan')
                                        {{ $case->case_status === 'open' ? 'Terbuka' : 
                                           ($case->case_status === 'in_progress' ? 'Dalam Proses' : 
                                           ($case->case_status === 'resolved' ? 'Terselesaikan' : 'Ditutup')) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($type === 'cek_masalah')
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tingkat Urgensi</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $case->tingkat_urgensi === 'tinggi' ? 'bg-red-100 text-red-800' : 
                                       ($case->tingkat_urgensi === 'sedang' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($case->tingkat_urgensi) }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Case Content -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Isi Kasus</h3>
                    
                    <div class="prose prose-sm max-w-none dark:prose-invert">
                        @if($type === 'konsultasi')
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $case->isi_curhat }}</p>
                        @elseif($type === 'cek_masalah')
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $case->deskripsi_tambahan ?? 'Tidak ada deskripsi tambahan' }}</p>
                        @elseif($type === 'pengaduan')
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium">Jenis Pengaduan:</span> {{ $case->jenis_pengaduan }}
                                </div>
                                <div>
                                    <span class="font-medium">Laporan:</span>
                                    <p class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $case->laporan_pengaduan }}</p>
                                </div>
                            </div>
                        @elseif($type === 'rekap')
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium">Jenis Bimbingan:</span> {{ $case->jenis_bimbingan }}
                                </div>
                                <div>
                                    <span class="font-medium">Keterangan:</span>
                                    <p class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $case->keterangan ?? 'Tidak ada keterangan' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Conversation History (for konsultasi) -->
                @if($type === 'konsultasi' && isset($case->conversations) && $case->conversations->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Riwayat Percakapan</h3>
                    
                    <div class="space-y-4">
                        @foreach($case->conversations as $conversation)
                        <div class="flex {{ $conversation->sender_type === 'gurubk' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $conversation->sender_type === 'gurubk' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                <p class="text-sm">{{ $conversation->message }}</p>
                                <p class="text-xs mt-1 {{ $conversation->sender_type === 'gurubk' ? 'text-blue-100' : 'text-gray-500' }}">
                                    {{ $conversation->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Resolution Information -->
                @if(($type === 'konsultasi' && $case->final_resolution) || 
                    ($type === 'cek_masalah' && $case->catatan_guru) || 
                    ($type === 'pengaduan' && $case->final_resolution) || 
                    ($type === 'rekap' && $case->final_resolution))
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hasil Akhir</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Solusi Akhir</label>
                            <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                                    @if($type === 'konsultasi')
                                        {{ $case->final_resolution }}
                                    @elseif($type === 'cek_masalah')
                                        {{ $case->catatan_guru }}
                                    @elseif($type === 'pengaduan')
                                        {{ $case->final_resolution }}
                                    @elseif($type === 'rekap')
                                        {{ $case->final_resolution }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if(($type === 'konsultasi' && $case->resolution_type) || 
                            ($type === 'pengaduan' && $case->resolution_type) || 
                            ($type === 'rekap' && $case->resolution_type))
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Resolusi</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($type === 'konsultasi')
                                    {{ $case->resolution_type === 'counseling' ? 'Konseling' : 
                                       ($case->resolution_type === 'disciplinary_action' ? 'Tindakan Disiplin' : 
                                       ($case->resolution_type === 'mediation' ? 'Mediasi' : 
                                       ($case->resolution_type === 'referral' ? 'Rujukan' : 'Lainnya'))) }}
                                @elseif($type === 'pengaduan')
                                    {{ $case->resolution_type === 'investigation' ? 'Investigasi' : 
                                       ($case->resolution_type === 'disciplinary_action' ? 'Tindakan Disiplin' : 
                                       ($case->resolution_type === 'mediation' ? 'Mediasi' : 
                                       ($case->resolution_type === 'referral' ? 'Rujukan' : 'Lainnya'))) }}
                                @elseif($type === 'rekap')
                                    {{ $case->resolution_type === 'counseling' ? 'Konseling' : 
                                       ($case->resolution_type === 'disciplinary_action' ? 'Tindakan Disiplin' : 
                                       ($case->resolution_type === 'mediation' ? 'Mediasi' : 
                                       ($case->resolution_type === 'referral' ? 'Rujukan' : 'Lainnya'))) }}
                                @endif
                            </p>
                        </div>
                        @endif

                        @if(($type === 'konsultasi' && $case->resolution_notes) || 
                            ($type === 'cek_masalah' && $case->tindak_lanjut) || 
                            ($type === 'pengaduan' && $case->resolution_notes) || 
                            ($type === 'rekap' && $case->resolution_notes))
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Tambahan</label>
                            <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                                    @if($type === 'konsultasi')
                                        {{ $case->resolution_notes }}
                                    @elseif($type === 'cek_masalah')
                                        {{ $case->tindak_lanjut }}
                                    @elseif($type === 'pengaduan')
                                        {{ $case->resolution_notes }}
                                    @elseif($type === 'rekap')
                                        {{ $case->resolution_notes }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif

                        @if(($type === 'konsultasi' && $case->resolution_date) || 
                            ($type === 'cek_masalah' && $case->tanggal_review) || 
                            ($type === 'pengaduan' && $case->resolution_date) || 
                            ($type === 'rekap' && $case->resolution_date))
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Penyelesaian</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($type === 'konsultasi')
                                    {{ $case->resolution_date->format('d M Y H:i') }}
                                @elseif($type === 'cek_masalah')
                                    {{ $case->tanggal_review ? $case->tanggal_review->format('d M Y H:i') : 'N/A' }}
                                @elseif($type === 'pengaduan')
                                    {{ $case->resolution_date->format('d M Y H:i') }}
                                @elseif($type === 'rekap')
                                    {{ $case->resolution_date->format('d M Y H:i') }}
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Update Resolution Form (Guru BK Only) -->
            @if(auth()->user()->hasRole('gurubk') && request()->get('action') === 'update')
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Resolusi</h3>
                    
                    <form action="{{ route('case-resolution.update', [$case->id, $type]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Case Status -->
                        <div class="mb-4">
                            <label for="case_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Kasus
                            </label>
                            <select name="case_status" id="case_status" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="open" {{ ($type === 'konsultasi' ? $case->case_status : ($type === 'cek_masalah' ? 'open' : $case->case_status)) === 'open' ? 'selected' : '' }}>Terbuka</option>
                                <option value="in_progress" {{ ($type === 'konsultasi' ? $case->case_status : ($type === 'cek_masalah' ? 'in_progress' : $case->case_status)) === 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                                <option value="resolved" {{ ($type === 'konsultasi' ? $case->case_status : ($type === 'cek_masalah' ? 'resolved' : $case->case_status)) === 'resolved' ? 'selected' : '' }}>Terselesaikan</option>
                                <option value="closed" {{ ($type === 'konsultasi' ? $case->case_status : ($type === 'cek_masalah' ? 'closed' : $case->case_status)) === 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                        </div>

                        <!-- Final Resolution -->
                        <div class="mb-4">
                            <label for="final_resolution" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Solusi Akhir
                            </label>
                            <textarea name="final_resolution" id="final_resolution" rows="4" required
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Jelaskan solusi akhir untuk kasus ini...">{{ $type === 'konsultasi' ? $case->final_resolution : ($type === 'cek_masalah' ? $case->catatan_guru : ($type === 'pengaduan' ? $case->final_resolution : $case->final_resolution)) }}</textarea>
                        </div>

                        <!-- Resolution Type -->
                        @if($type !== 'cek_masalah')
                        <div class="mb-4">
                            <label for="resolution_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Resolusi
                            </label>
                            <select name="resolution_type" id="resolution_type" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                @if($type === 'konsultasi' || $type === 'rekap')
                                    <option value="counseling" {{ $case->resolution_type === 'counseling' ? 'selected' : '' }}>Konseling</option>
                                    <option value="disciplinary_action" {{ $case->resolution_type === 'disciplinary_action' ? 'selected' : '' }}>Tindakan Disiplin</option>
                                    <option value="mediation" {{ $case->resolution_type === 'mediation' ? 'selected' : '' }}>Mediasi</option>
                                    <option value="referral" {{ $case->resolution_type === 'referral' ? 'selected' : '' }}>Rujukan</option>
                                    <option value="other" {{ $case->resolution_type === 'other' ? 'selected' : '' }}>Lainnya</option>
                                @elseif($type === 'pengaduan')
                                    <option value="investigation" {{ $case->resolution_type === 'investigation' ? 'selected' : '' }}>Investigasi</option>
                                    <option value="disciplinary_action" {{ $case->resolution_type === 'disciplinary_action' ? 'selected' : '' }}>Tindakan Disiplin</option>
                                    <option value="mediation" {{ $case->resolution_type === 'mediation' ? 'selected' : '' }}>Mediasi</option>
                                    <option value="referral" {{ $case->resolution_type === 'referral' ? 'selected' : '' }}>Rujukan</option>
                                    <option value="other" {{ $case->resolution_type === 'other' ? 'selected' : '' }}>Lainnya</option>
                                @endif
                            </select>
                        </div>
                        @endif

                        <!-- Resolution Notes -->
                        <div class="mb-6">
                            <label for="resolution_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan Tambahan
                            </label>
                            <textarea name="resolution_notes" id="resolution_notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Catatan tambahan atau tindak lanjut...">{{ $type === 'konsultasi' ? $case->resolution_notes : ($type === 'cek_masalah' ? $case->tindak_lanjut : ($type === 'pengaduan' ? $case->resolution_notes : $case->resolution_notes)) }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Update Resolusi
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- Action Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi</h3>
                    
                    <div class="space-y-3">
                        @if(auth()->user()->hasRole('gurubk'))
                        <a href="{{ route('case-resolution.show', [$case->id, $type]) }}?action=update" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Update Resolusi
                        </a>
                        @endif

                        <a href="{{ route('case-resolution.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Lihat Semua Kasus
                        </a>

                        <a href="{{ route('case-resolution.dashboard') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection