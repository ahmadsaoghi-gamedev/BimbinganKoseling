@extends('layouts.app')

@section('title', 'Tambah Data Rekap Bimbingan')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        @if(auth()->user()->hasRole('gurubk'))
                            Tambah Data Rekap Bimbingan
                        @else
                            Buat Bimbingan Konseling
                        @endif
                    </h2>
                    @if(auth()->user()->hasRole('gurubk'))
                    <a href="{{ route('rekap.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                    @else
                    <a href="{{ route('dashboard') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                    @endif
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Form -->
                <form action="{{ route('rekap.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Siswa (only for Guru BK) -->
                        @if(auth()->user()->hasRole('gurubk'))
                        <div class="md:col-span-2">
                            <label for="id_siswa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Siswa <span class="text-red-500">*</span>
                            </label>
                            <select id="id_siswa" 
                                    name="id_siswa" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih Siswa</option>
                                @foreach (\App\Models\Siswa::all() as $siswa)
                                <option value="{{ $siswa->id }}" {{ old('id_siswa') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }} ({{ $siswa->nis }}) - {{ $siswa->kelas }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_siswa')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @else
                        <!-- For students, auto-select their own ID -->
                        @php 
                            $siswa = \App\Models\Siswa::where('id_user', auth()->user()->id)->first();
                        @endphp
                        <input type="hidden" name="id_siswa" value="{{ $siswa ? $siswa->id : '' }}">
                        @endif

                        <!-- Jenis Bimbingan -->
                        <div>
                            <label for="jenis_bimbingan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Bimbingan <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_bimbingan" 
                                    name="jenis_bimbingan" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih Jenis Bimbingan</option>
                                <option value="Sosial" {{ old('jenis_bimbingan') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                                <option value="Akademik" {{ old('jenis_bimbingan') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                <option value="Pribadi" {{ old('jenis_bimbingan') == 'Pribadi' ? 'selected' : '' }}>Pribadi</option>
                                <option value="Karir" {{ old('jenis_bimbingan') == 'Karir' ? 'selected' : '' }}>Karir</option>
                            </select>
                            @error('jenis_bimbingan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Bimbingan -->
                        <div>
                            <label for="tgl_bimbingan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Bimbingan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="tgl_bimbingan" 
                                   name="tgl_bimbingan" 
                                   value="{{ old('tgl_bimbingan', date('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                   required>
                            @error('tgl_bimbingan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mt-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterangan / Masalah yang Dihadapi <span class="text-red-500">*</span>
                        </label>
                        <textarea id="keterangan" 
                                  name="keterangan" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan masalah atau kendala yang dihadapi..."
                                  required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-3">
                        @if(auth()->user()->hasRole('gurubk'))
                        <a href="{{ route('rekap.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        @else
                        <a href="{{ route('dashboard') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        @endif
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Simpan Data Bimbingan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection