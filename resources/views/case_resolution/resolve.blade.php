@extends('layouts.app')

@section('title', 'Selesaikan Kasus')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Selesaikan Kasus
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ ucfirst($type) }} - {{ $case->siswa->name ?? $case->siswa->nama ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('case-resolution.show', [$type, $case->id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Case Summary -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Ringkasan Kasus
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Jenis:</span>
                            <p class="text-gray-900 dark:text-gray-100">{{ ucfirst($type) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Siswa:</span>
                            <p class="text-gray-900 dark:text-gray-100">{{ $case->siswa->name ?? $case->siswa->nama ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Tanggal Dibuat:</span>
                            <p class="text-gray-900 dark:text-gray-100">
                                {{ $type === 'konsultasi' ? ($case->tgl_curhat ? $case->tgl_curhat->format('d M Y H:i') : 'N/A') : ($case->tgl_pengaduan ? $case->tgl_pengaduan->format('d M Y') : 'N/A') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Status Saat Ini:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $case->case_status_badge_class }}">
                                {{ $case->case_status_text }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">Isi {{ ucfirst($type) }}:</span>
                        <div class="mt-2 p-3 bg-white dark:bg-gray-700 rounded border">
                            @if($type === 'konsultasi')
                            <p class="text-gray-900 dark:text-gray-100 text-sm">{{ Str::limit($case->isi_curhat, 200) }}</p>
                            @else
                            <p class="text-gray-900 dark:text-gray-100 text-sm">{{ $case->jenis_pengaduan }} - {{ Str::limit($case->laporan_pengaduan, 200) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Resolution Form -->
                <form method="POST" action="{{ route('case-resolution.store-resolution', [$type, $case->id]) }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="resolution_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jenis Solusi <span class="text-red-500">*</span>
                        </label>
                        <select name="resolution_type" id="resolution_type" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Pilih jenis solusi</option>
                            @if($type === 'konsultasi')
                            <option value="counseling">Konseling</option>
                            <option value="disciplinary_action">Tindakan Disiplin</option>
                            <option value="mediation">Mediasi</option>
                            <option value="referral">Rujukan</option>
                            <option value="other">Lainnya</option>
                            @else
                            <option value="investigation">Investigasi</option>
                            <option value="disciplinary_action">Tindakan Disiplin</option>
                            <option value="mediation">Mediasi</option>
                            <option value="referral">Rujukan</option>
                            <option value="other">Lainnya</option>
                            @endif
                        </select>
                        @error('resolution_type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="final_resolution" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Solusi Akhir <span class="text-red-500">*</span>
                        </label>
                        <textarea name="final_resolution" id="final_resolution" rows="6" required 
                                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Jelaskan solusi akhir yang telah diterapkan untuk menyelesaikan kasus ini...">{{ old('final_resolution') }}</textarea>
                        @error('final_resolution')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Minimal 10 karakter. Jelaskan secara detail solusi yang telah diterapkan.
                        </p>
                    </div>

                    <div>
                        <label for="resolution_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Tambahan
                        </label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="4" 
                                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Catatan tambahan atau informasi penting lainnya...">{{ old('resolution_notes') }}</textarea>
                        @error('resolution_notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Opsional. Catatan tambahan yang mungkin berguna untuk referensi masa depan.
                        </p>
                    </div>

                    <div>
                        <label for="case_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status Akhir <span class="text-red-500">*</span>
                        </label>
                        <select name="case_status" id="case_status" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Pilih status akhir</option>
                            <option value="resolved">Terselesaikan</option>
                            <option value="closed">Ditutup</option>
                        </select>
                        @error('case_status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <strong>Terselesaikan:</strong> Kasus telah diselesaikan dengan solusi yang tepat.<br>
                            <strong>Ditutup:</strong> Kasus ditutup tanpa solusi yang memuaskan.
                        </p>
                    </div>

                    <!-- Resolution Guidelines -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Panduan Penyelesaian
                        </h4>
                        <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                            <li>• Pastikan solusi yang diberikan sesuai dengan masalah yang dihadapi</li>
                            <li>• Jelaskan langkah-langkah yang telah diambil untuk menyelesaikan kasus</li>
                            <li>• Jika diperlukan, sertakan tindak lanjut atau monitoring yang akan dilakukan</li>
                            <li>• Gunakan bahasa yang jelas dan mudah dipahami</li>
                        </ul>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('case-resolution.show', [$type, $case->id]) }}"
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Selesaikan Kasus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
