@extends('layouts.app')

@section('title', 'Tambah Laporan Curhat')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Tambah Laporan Curhat
                    </h2>
                    <a href="{{ route('curhat-reports.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
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
                <form action="{{ route('curhat-reports.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Konsultasi -->
                        <div class="md:col-span-2">
                            <label for="konsultasi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Konsultasi <span class="text-red-500">*</span>
                            </label>
                            <select id="konsultasi_id" 
                                    name="konsultasi_id" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih Konsultasi</option>
                                @foreach ($konsultasis as $konsultasi)
                                <option value="{{ $konsultasi->id }}" {{ old('konsultasi_id') == $konsultasi->id ? 'selected' : '' }}>
                                    {{ $konsultasi->user->name ?? 'N/A' }} - {{ Str::limit($konsultasi->isi_curhat, 50) }}
                                </option>
                                @endforeach
                            </select>
                            @error('konsultasi_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Siswa -->
                        <div>
                            <label for="siswa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Siswa <span class="text-red-500">*</span>
                            </label>
                            <select id="siswa_id" 
                                    name="siswa_id" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih Siswa</option>
                                @foreach ($siswas as $siswa)
                                <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }} ({{ $siswa->nis }})
                                </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Guru BK -->
                        <div>
                            <label for="guru_bk_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Guru BK <span class="text-red-500">*</span>
                            </label>
                            <select id="guru_bk_id" 
                                    name="guru_bk_id" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih Guru BK</option>
                                @foreach ($guruBks as $guruBk)
                                <option value="{{ $guruBk->id }}" {{ old('guru_bk_id') == $guruBk->id ? 'selected' : '' }}>
                                    {{ $guruBk->nama ?? 'N/A' }}
                                </option>
                                @endforeach
                            </select>
                            @error('guru_bk_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Masalah -->
                        <div>
                            <label for="kategori_masalah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori Masalah <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori_masalah" 
                                    name="kategori_masalah" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih Kategori</option>
                                <option value="akademik" {{ old('kategori_masalah') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                                <option value="sosial" {{ old('kategori_masalah') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                                <option value="pribadi" {{ old('kategori_masalah') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                                <option value="karir" {{ old('kategori_masalah') == 'karir' ? 'selected' : '' }}>Karir</option>
                            </select>
                            @error('kategori_masalah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tingkat Urgensi -->
                        <div>
                            <label for="tingkat_urgensi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tingkat Urgensi <span class="text-red-500">*</span>
                            </label>
                            <select id="tingkat_urgensi" 
                                    name="tingkat_urgensi" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih Urgensi</option>
                                <option value="rendah" {{ old('tingkat_urgensi') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="sedang" {{ old('tingkat_urgensi') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="tinggi" {{ old('tingkat_urgensi') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                            @error('tingkat_urgensi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Curhat -->
                        <div>
                            <label for="tanggal_curhat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Curhat <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="tanggal_curhat" 
                                   name="tanggal_curhat" 
                                   value="{{ old('tanggal_curhat', date('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                   required>
                            @error('tanggal_curhat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Sesi -->
                        <div>
                            <label for="jumlah_sesi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jumlah Sesi
                            </label>
                            <input type="number" 
                                   id="jumlah_sesi" 
                                   name="jumlah_sesi" 
                                   value="{{ old('jumlah_sesi', 1) }}"
                                   min="1"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('jumlah_sesi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Analisis Masalah -->
                    <div class="mt-6">
                        <label for="analisis_masalah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Analisis Masalah
                        </label>
                        <textarea id="analisis_masalah" 
                                  name="analisis_masalah" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Analisis masalah yang dihadapi siswa...">{{ old('analisis_masalah') }}</textarea>
                        @error('analisis_masalah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tindakan yang Dilakukan -->
                    <div class="mt-6">
                        <label for="tindakan_yang_dilakukan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tindakan yang Dilakukan
                        </label>
                        <textarea id="tindakan_yang_dilakukan" 
                                  name="tindakan_yang_dilakukan" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Tindakan yang telah dilakukan...">{{ old('tindakan_yang_dilakukan') }}</textarea>
                        @error('tindakan_yang_dilakukan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hasil Tindakan -->
                    <div class="mt-6">
                        <label for="hasil_tindakan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Hasil Tindakan
                        </label>
                        <textarea id="hasil_tindakan" 
                                  name="hasil_tindakan" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Hasil dari tindakan yang dilakukan...">{{ old('hasil_tindakan') }}</textarea>
                        @error('hasil_tindakan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rekomendasi Lanjutan -->
                    <div class="mt-6">
                        <label for="rekomendasi_lanjutan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rekomendasi Lanjutan
                        </label>
                        <textarea id="rekomendasi_lanjutan" 
                                  name="rekomendasi_lanjutan" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Rekomendasi untuk tindak lanjut...">{{ old('rekomendasi_lanjutan') }}</textarea>
                        @error('rekomendasi_lanjutan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Khusus -->
                    <div class="mt-6">
                        <label for="catatan_khusus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Khusus
                        </label>
                        <textarea id="catatan_khusus" 
                                  name="catatan_khusus" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Catatan khusus atau informasi tambahan...">{{ old('catatan_khusus') }}</textarea>
                        @error('catatan_khusus')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Perlu Tindak Lanjut -->
                    <div class="mt-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="perlu_tindak_lanjut" 
                                   value="1"
                                   {{ old('perlu_tindak_lanjut') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Perlu tindak lanjut</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('curhat-reports.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Simpan Laporan Curhat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
