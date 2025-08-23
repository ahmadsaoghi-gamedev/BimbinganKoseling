@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Message -->
        @php
            $userRole = 'admin';
            if(auth()->user()->hasRole('siswa')) $userRole = 'siswa';
            elseif(auth()->user()->hasRole('gurubk')) $userRole = 'gurubk';
            elseif(auth()->user()->hasRole('orangtua')) $userRole = 'orangtua';
        @endphp
        
        <x-welcome-message :userRole="$userRole" :userName="auth()->user()->name" />

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students Card -->
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Siswa</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Siswa::count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Active Cases Card -->
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-200 dark:bg-red-800/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kasus Aktif</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Konsultasi::where('case_status', 'open')->count() + \App\Models\Pengaduan::where('status', 'open')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Today's Appointments Card -->
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-300 dark:bg-red-700/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-800 dark:text-red-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal Hari Ini</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Rekap::whereDate('tgl_bimbingan', today())->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Student Progress Card (for students) -->
            @if(auth()->user()->hasRole('siswa'))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Progress BK
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->siswa ? \App\Models\Konsultasi::where('id_siswa', auth()->user()->siswa->id)->count() : 0 }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Navigation Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            <!-- Curhat Rahasia Card -->
            @if(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('gurubk'))
            <x-user-friendly-card 
                title="Curhat Rahasia" 
                description="Tempat aman untuk curhat dengan Guru BK"
                :href="route('curhat-reports.index')"
                :userRole="$userRole"
            />
            @endif

            <!-- Konsultasi Card -->
            @if(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('gurubk'))
            <x-user-friendly-card 
                title="Konsultasi" 
                description="Konsultasi umum dengan Guru BK"
                :href="route('konsultasi.index')"
                :userRole="$userRole"
            />
            @endif

            <!-- Pengaduan Card -->
            @if(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('gurubk'))
            <x-user-friendly-card 
                title="Pengaduan" 
                description="Laporkan masalah atau pelanggaran"
                :href="route('pengaduan.index')"
                :userRole="$userRole"
            />
            @endif

            <!-- Daftar Cek Masalah Card -->
            @if(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('gurubk'))
            <x-user-friendly-card 
                title="Cek Masalah" 
                description="Identifikasi masalah siswa"
                :href="route('gurubk.daftar-cek-masalah')"
                :userRole="$userRole"
            />
            @endif

            <!-- Case Resolution Card for Guru BK and Admin -->
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
            <x-user-friendly-card 
                title="Solusi Kasus" 
                description="Kelola solusi akhir curhatan & pengaduan"
                :href="route('case-resolution.dashboard')"
                :userRole="$userRole"
            />
            @endif

            @if(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
            <x-user-friendly-card 
                title="Rekap Bimbingan" 
                description="Mengelola Rekap Bimbingan"
                :href="route('rekap.index')"
                :userRole="$userRole"
            />
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk') || auth()->user()->hasRole('kesiswaan'))
            <x-user-friendly-card 
                title="Sistem Pemanggilan" 
                description="Kelola surat pemanggilan orang tua"
                :href="route('summons.dashboard')"
                :userRole="$userRole"
            />
            @endif

            @if(auth()->user()->hasRole('gurubk'))
            <x-user-friendly-card 
                title="Bimbingan Lanjutan" 
                description="Tindak lanjut kasus bimbingan"
                :href="route('gurubk.bimbingan-lanjutan')"
                :userRole="$userRole"
            />
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
            <x-user-friendly-card 
                title="Data Siswa" 
                description="Kelola data siswa"
                :href="route('siswa.index')"
                :userRole="$userRole"
            />
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('siswa'))
            <x-user-friendly-card 
                title="Data Guru BK" 
                description="Lihat data guru bimbingan konseling"
                :href="route('guru_bk.index')"
                :userRole="$userRole"
            />
            @endif

            @if(auth()->user()->hasRole('kesiswaan') || auth()->user()->hasRole('gurubk') || auth()->user()->hasRole('kepsek') || auth()->user()->hasRole('kajur') || auth()->user()->hasRole('orangtua') || auth()->user()->hasRole('siswa'))
            <x-user-friendly-card 
                title="Data Pelanggaran" 
                description="Lihat data pelanggaran siswa"
                :href="route('pelanggaran.index')"
                :userRole="$userRole"
            />
            @endif
        </div>

        <!-- Student-specific sections -->
        @if(auth()->user()->hasRole('siswa'))
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                💭 Bagaimana perasaanmu hari ini?
            </h3>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                <x-mood-indicator mood="neutral" :interactive="true" />
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                📈 Progress Bimbingan Konseling
            </h3>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm space-y-4">
                @php
                    $totalSessions = auth()->user()->siswa ? \App\Models\Konsultasi::where('id_siswa', auth()->user()->siswa->id)->count() : 0;
                    $completedSessions = auth()->user()->siswa ? \App\Models\Konsultasi::where('id_siswa', auth()->user()->siswa->id)->where('case_status', 'closed')->count() : 0;
                @endphp
                
                <x-friendly-progress 
                    :current="$completedSessions" 
                    :total="$totalSessions" 
                    label="Sesi Konseling Selesai"
                    color="blue"
                />
                
                @php
                    $totalCurhat = auth()->user()->siswa ? \App\Models\CurhatConversation::where('siswa_id', auth()->user()->siswa->id)->count() : 0;
                    $resolvedCurhat = auth()->user()->siswa ? \App\Models\CurhatConversation::where('siswa_id', auth()->user()->siswa->id)->where('status', 'resolved')->count() : 0;
                @endphp
                
                <x-friendly-progress 
                    :current="$resolvedCurhat" 
                    :total="$totalCurhat" 
                    label="Curhat Rahasia Terselesaikan"
                    color="green"
                />
            </div>
        </div>
        @endif

        <!-- Guru BK specific sections -->
        @if(auth()->user()->hasRole('gurubk'))
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                📊 Ringkasan Hari Ini
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Curhat Baru</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ \App\Models\CurhatConversation::whereDate('created_at', today())->count() }}
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
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kasus Selesai</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Konsultasi::whereDate('updated_at', today())->where('case_status', 'closed')->count() }}
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
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal Hari Ini</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Rekap::whereDate('tgl_bimbingan', today())->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
