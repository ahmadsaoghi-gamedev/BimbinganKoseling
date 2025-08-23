@extends('layouts.app')

@section('title', 'Detail Konsultasi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-red-700 to-red-800 rounded-xl shadow-lg mb-8">
            <div class="px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            💬 Detail Konsultasi
                        </h1>
                        <p class="text-red-100 text-lg">
                            Lihat dan kelola konsultasi dengan detail lengkap
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('konsultasi.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Konsultasi Details -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            Konsultasi #{{ $konsultasi->id }}
                        </h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $konsultasi->user->name ?? 'N/A' }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $konsultasi->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $konsultasi->case_status == 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ $konsultasi->case_status == 'open' ? 'Terbuka' : 'Selesai' }}
                        </span>
                    </div>
                </div>

                <!-- Konsultasi Content -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Isi Konsultasi:</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $konsultasi->isi_curhat }}
                    </p>
                </div>

                <!-- Attachment if exists -->
                @if($konsultasi->attachment)
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Lampiran:</h3>
                    <a href="{{ Storage::url($konsultasi->attachment) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Lihat Lampiran
                    </a>
                </div>
                @endif
            </div>

            <!-- Conversations Section -->
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Riwayat Percakapan</h3>
                
                @if($konsultasi->conversations->count() > 0)
                <div class="space-y-4">
                    @foreach($konsultasi->conversations as $conversation)
                    <div class="flex {{ $conversation->sender_type == 'gurubk' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md {{ $conversation->sender_type == 'gurubk' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-900' }} rounded-lg px-4 py-2 shadow-sm">
                            <div class="flex items-center mb-1">
                                <span class="text-xs font-medium opacity-75">
                                    {{ $conversation->sender_type == 'gurubk' ? 'Guru BK' : 'Siswa' }}
                                </span>
                                <span class="text-xs opacity-75 ml-2">
                                    {{ $conversation->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="text-sm">{{ $conversation->message }}</p>
                            
                            @if($conversation->attachment)
                            <div class="mt-2">
                                <a href="{{ Storage::url($conversation->attachment) }}" 
                                   target="_blank"
                                   class="text-xs underline hover:no-underline">
                                    📎 Lampiran
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500">Belum ada percakapan</p>
                </div>
                @endif

                <!-- Reply Form -->
                @if(auth()->user()->hasRole('gurubk') || (auth()->user()->hasRole('siswa') && $konsultasi->id_siswa == auth()->user()->id))
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Balasan</h3>
                    <form action="{{ route('konsultasi.balas') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="konsultasi_id" value="{{ $konsultasi->id }}">
                        
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Pesan
                            </label>
                            <textarea name="message" id="message" rows="4" 
                                      class="w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                      placeholder="Tulis balasan Anda..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                                Lampiran (Opsional)
                            </label>
                            <input type="file" name="attachment" id="attachment" 
                                   class="w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF, DOC, DOCX (Max: 2MB)</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                                Kirim Balasan
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
