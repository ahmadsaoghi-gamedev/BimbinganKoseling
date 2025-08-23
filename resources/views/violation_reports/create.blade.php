@extends('layouts.app')

@section('title', 'Buat Rekap Pelanggaran Baru')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Buat Rekap Pelanggaran Baru
                    </h2>
                    <a href="{{ route('violation-reports.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                </div>

                <form action="{{ route('violation-reports.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Selection -->
                        <div>
                            <label for="siswa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Siswa <span class="text-red-500">*</span>
                            </label>
                            <select name="siswa_id" id="siswa_id" required 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Siswa</option>
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nama }} ({{ $siswa->nis }}) - {{ $siswa->kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Guru BK Selection -->
                        <div>
                            <label for="gurubk_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Guru BK <span class="text-red-500">*</span>
                            </label>
                            <select name="gurubk_id" id="gurubk_id" required 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Guru BK</option>
                                @foreach($guruBks as $guruBk)
                                    <option value="{{ $guruBk->id }}" {{ old('gurubk_id') == $guruBk->id ? 'selected' : '' }}>
                                        {{ $guruBk->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gurubk_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Violation Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori Pelanggaran <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" required 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Kategori</option>
                                @foreach(App\Models\ViolationReport::CATEGORIES as $key => $value)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Violation Date -->
                        <div>
                            <label for="violation_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Pelanggaran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="violation_date" id="violation_date" 
                                   value="{{ old('violation_date', date('Y-m-d')) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('violation_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Points Before -->
                        <div>
                            <label for="points_before" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Poin Sebelumnya
                            </label>
                            <input type="number" name="points_before" id="points_before" 
                                   value="{{ old('points_before', 0) }}" min="0" max="100"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('points_before')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Points After -->
                        <div>
                            <label for="points_after" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Poin Setelah <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="points_after" id="points_after" 
                                   value="{{ old('points_after', 0) }}" min="0" max="100" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('points_after')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                @foreach(App\Models\ViolationReport::STATUSES as $key => $value)
                                    <option value="{{ $key }}" {{ old('status', 'pending') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Violation Description -->
                    <div class="mt-6">
                        <label for="violation_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi Pelanggaran <span class="text-red-500">*</span>
                        </label>
                        <textarea name="violation_description" id="violation_description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan detail pelanggaran yang dilakukan...">{{ old('violation_description') }}</textarea>
                        @error('violation_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sanctions -->
                    <div class="mt-6">
                        <label for="sanctions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Sanksi
                        </label>
                        <textarea name="sanctions" id="sanctions" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Sanksi yang diberikan...">{{ old('sanctions') }}</textarea>
                        @error('sanctions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prevention Actions -->
                    <div class="mt-6">
                        <label for="prevention_actions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tindakan Pencegahan
                        </label>
                        <textarea name="prevention_actions" id="prevention_actions" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Tindakan pencegahan yang akan dilakukan...">{{ old('prevention_actions') }}</textarea>
                        @error('prevention_actions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Special Notes -->
                    <div class="mt-6">
                        <label for="special_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Khusus
                        </label>
                        <textarea name="special_notes" id="special_notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Catatan khusus atau informasi tambahan...">{{ old('special_notes') }}</textarea>
                        @error('special_notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notification Checkboxes -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="parent_notification_sent" id="parent_notification_sent" 
                                   value="1" {{ old('parent_notification_sent') ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="parent_notification_sent" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Notifikasi Orang Tua Terkirim
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="summon_letter_sent" id="summon_letter_sent" 
                                   value="1" {{ old('summon_letter_sent') ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="summon_letter_sent" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Surat Pemanggilan Terkirim
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Buat Rekap Pelanggaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
