<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center mx-auto">
        </h2>
</x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           
            <div class="bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-600 shadow-2xl rounded-lg overflow-hidden mb-10">
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
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

<x-modal name="pilihan" focusable maxWidth="xl">
    <div class="p-6">
        <!-- Judul Modal -->
        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">
            {{ __('Pengaduan & Bimbingan KOnseling') }}
        </h2>

        <a href="{{ route('pengaduan.index') }}" class="px-4 py-2 mr-5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring focus:ring-blue-300">
            Pengaduan
        </a>
        <a href="{{ route('rekap.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring focus:ring-blue-300">
            Bimbingan Konseling
        </a>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>

        
        </div>
    </div>
</x-modal>