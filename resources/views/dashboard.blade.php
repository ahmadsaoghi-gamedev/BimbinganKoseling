<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center mx-auto">
        </h2>
</x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           
            <div class="bg-gradient-to-r from-red-700 via-red-800 to-red-900 shadow-2xl rounded-lg overflow-hidden mb-10">
                <div class="p-8 text-white">
                    <h1 class="text-3xl font-extrabold mb-3">Selamat Datang di Aplikasi Bimbingan Konseling SMKN NEGERI 1 CILAKU</h1>
                   
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('siswa.index') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-blue-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M12 12c3.86 0 7-3.14 7-7s-3.14-7-7-7-7 3.14-7 7 3.14 7 7 7z"></path>
                                    <path d="M4 22v-1c0-2.5 5-4 8-4s8 1.5 8 4v1"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400">Data Siswa </h3>
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
                            <p class="text-gray-700 dark:text-gray-300">Kelola data siswa</p>
                            @endif

                        </div>
                    </a>
                </div>
               
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('guru_bk.index') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-blue-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M12 12c3.86 0 7-3.14 7-7s-3.14-7-7-7-7 3.14-7 7 3.14 7 7 7z"></path>
                                    <path d="M4 22v-1c0-2.5 5-4 8-4s8 1.5 8 4v1"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400">Data Guru BK </h3>
                            @if(auth()->user()->hasRole('admin'))
                            <p class="text-gray-700 dark:text-gray-300">Kelola data guru bk</p>
                            @endif
                        </div>
                    </a>
                </div>

                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-teal-500 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('pelanggaran.index') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-green-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M8 7h8M8 12h8m-4 5h4m-5-6h2v5H9a3 3 0 1 1 0-6zm8-4v14"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">Data Pelanggaran</h3>
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
                            <p class="text-gray-700 dark:text-gray-300">Mengelola data pelanggaran</p>
                            @endif
                        </div>
                    </a>
                </div>

                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-400 to-pink-500 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('rekap.index') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-red-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">Rekap Bimbingan</h3>
                            <p class="text-gray-700 dark:text-gray-300">Mengelola Rekap Bimbingan</p>
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->hasRole('gurubk'))
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('gurubk.curhat') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-purple-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-purple-600 dark:text-purple-400">Daftar Curhat</h3>
                            <p class="text-gray-700 dark:text-gray-300">Lihat curhat rahasia siswa</p>
                        </div>
                    </a>
                </div>

                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('gurubk.bimbingan-lanjutan') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-orange-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-orange-600 dark:text-orange-400">Bimbingan Lanjutan</h3>
                            <p class="text-gray-700 dark:text-gray-300">Kelola bimbingan lanjutan siswa</p>
                        </div>
                    </a>
                </div>

                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('gurubk.daftar-cek-masalah') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-teal-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-teal-600 dark:text-teal-400">Daftar Cek Masalah</h3>
                            <p class="text-gray-700 dark:text-gray-300">Identifikasi masalah siswa</p>
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-teal-500 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a 
                        @if(auth()->user()->hasRole('siswa'))
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'pilihan'); $dispatch('pilihan')"
                            class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300"
                        @else
                            href="{{ route('pengaduan.index') }}"
                            class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300"
                        @endif
                    >
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-green-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M8 7h8M8 12h8m-4 5h4m-5-6h2v5H9a3 3 0 1 1 0-6zm8-4v14"></path>
                                </svg>
                            </div>
                            <center>
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
                            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">Pengaduan</h3>
                            @else
                            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">Pengaduan & Bimbingan Konseling</h3>
                            @endif
                            </center>
                            
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gurubk'))
                            <p class="text-gray-700 dark:text-gray-300">Mengelola data pengaduan</p>
                            @endif
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->hasRole('siswa'))
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-400 to-pink-500 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('rekap.index') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-red-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">Hasil Bimbingan Konseling</h3>
                        </div>
                    </a>
                </div>

                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-lg opacity-75 blur-lg group-hover:blur-none transition duration-500"></div>
                    <a href="{{ route('siswa.cek-masalah.create') }}" class="relative block w-full h-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center items-center h-full p-4">
                            <div class="mb-4 text-teal-500">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-teal-600 dark:text-teal-400">Isi Formulir Cek Masalah</h3>
                            <p class="text-gray-700 dark:text-gray-300 text-center">Identifikasi masalah yang Anda hadapi</p>
                        </div>
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

<x-modal name="pilihan" focusable maxWidth="4xl">
    <div class="p-8 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
        <!-- Header dengan gradient -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Pilih Layanan Konseling
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Silakan pilih layanan yang Anda butuhkan</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Pengaduan Card -->
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <a href="{{ route('pengaduan.index') }}" class="relative block p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl transform transition duration-300 hover:scale-105">
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Pengaduan</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Laporkan masalah atau keluhan yang Anda alami</p>
                    </div>
                </a>
            </div>

            <!-- Bimbingan Konseling Card -->
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <a href="{{ route('rekap.create') }}" class="relative block p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl transform transition duration-300 hover:scale-105">
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Bimbingan Konseling</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Konsultasi langsung dengan Guru BK</p>
                    </div>
                </a>
            </div>

            <!-- Curhat Rahasia Card -->
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <a href="{{ route('konsultasi.create') }}" class="relative block p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl transform transition duration-300 hover:scale-105">
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Curhat Rahasia</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 text-center">Ceritakan masalah pribadi dengan aman dan rahasia</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Footer dengan tombol tutup yang lebih menarik -->
        <div class="flex justify-center">
            <button x-on:click="$dispatch('close')" 
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Tutup
                </span>
            </button>
        </div>
    </div>
</x-modal>
