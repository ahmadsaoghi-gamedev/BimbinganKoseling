@extends('layouts.app')

@section('title', 'Bimbingan Konseling')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Message -->
        @php
            $userRole = 'admin';
            if(auth()->user()->hasRole('siswa')) $userRole = 'siswa';
            elseif(auth()->user()->hasRole('gurubk')) $userRole = 'gurubk';
        @endphp

        @if(auth()->user()->hasRole('siswa'))
        <x-welcome-message 
            userRole="siswa" 
            :userName="auth()->user()->name"
        />
        @endif

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg mb-8">
            <div class="px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            💬 Bimbingan Konseling
                        </h1>
                        @if(auth()->user()->hasRole('siswa'))
                        <p class="text-blue-100 text-lg">
                            Tempat aman untuk konsultasi dengan Guru BK
                        </p>
                        @else
                        <p class="text-blue-100 text-lg">
                            Kelola konsultasi siswa dengan efisien
                        </p>
                        @endif
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

        <!-- Quick Actions -->
        @if(auth()->user()->hasRole('siswa'))
        <div class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-user-friendly-card 
                    title="Buat Konsultasi" 
                    description="Mulai konsultasi baru dengan Guru BK"
                    :href="route('konsultasi.create')"
                    userRole="siswa"
                />
                
                <x-user-friendly-card 
                    title="Curhat Rahasia" 
                    description="Curhat pribadi yang dijaga kerahasiaannya"
                    :href="route('curhat-reports.index')"
                    userRole="siswa"
                />
                
                <x-user-friendly-card 
                    title="Cek Masalah" 
                    description="Identifikasi masalah yang Anda hadapi"
                    :href="route('gurubk.daftar-cek-masalah')"
                    userRole="siswa"
                />
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        @if(auth()->user()->hasRole('gurubk'))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Konsultasi</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ \App\Models\Konsultasi::count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-200 dark:bg-red-800/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ \App\Models\Konsultasi::where('case_status', 'closed')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-300 dark:bg-red-700/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-800 dark:text-red-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ \App\Models\Konsultasi::where('case_status', 'open')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-400 dark:bg-red-600/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-900 dark:text-red-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Siswa Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ \App\Models\Konsultasi::distinct('id_siswa')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            @if(auth()->user()->hasRole('siswa'))
                                📋 Riwayat Konsultasi Saya
                            @else
                                📋 Daftar Konsultasi
                            @endif
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            @if(auth()->user()->hasRole('siswa'))
                                Lihat semua konsultasi yang telah Anda buat
                            @else
                                Kelola semua konsultasi siswa
                            @endif
                        </p>
                    </div>
                    
                    @if(auth()->user()->hasRole('siswa'))
                    <a href="{{ route('konsultasi.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Konsultasi
                    </a>
                    @endif
                </div>

                <!-- Filters -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                    <form method="GET" action="{{ route('konsultasi.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status
                            </label>
                            <select name="status" id="status"
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Terbuka</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Cari
                            </label>
                            <input type="text" name="search" id="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari judul atau isi..."
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('konsultasi.index') }}"
                               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Konsultasi List -->
                <div class="space-y-4">
                    @forelse($konsultasi as $item)
                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $item->judul }}
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $item->case_status == 'open' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }}">
                                        {{ $item->case_status == 'open' ? 'Terbuka' : 'Selesai' }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                    {{ Str::limit($item->isi, 200) }}
                                </p>
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $item->user->name ?? 'N/A' }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('konsultasi.show', $item->id) }}"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:text-red-300 dark:bg-red-900/30 dark:hover:bg-red-900/50">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            Belum ada konsultasi
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                            @if(auth()->user()->hasRole('siswa'))
                                Mulai konsultasi pertama Anda dengan Guru BK
                            @else
                                Belum ada konsultasi yang dibuat oleh siswa
                            @endif
                        </p>
                        @if(auth()->user()->hasRole('siswa'))
                        <a href="{{ route('konsultasi.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Konsultasi Pertama
                        </a>
                        @endif
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($konsultasi->hasPages())
                <div class="mt-6">
                    {{ $konsultasi->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
