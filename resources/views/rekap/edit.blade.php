@extends('layouts.app')

@section('title', 'Balas Bimbingan')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Balas Bimbingan
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $bimbingan->siswa->nama ?? 'N/A' }} - {{ $bimbingan->jenis_bimbingan }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('rekap.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Case Status Display -->
                <div class="mb-6">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Kasus:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bimbingan->case_status_badge_class ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $bimbingan->case_status_text ?? 'Terbuka' }}
                        </span>
                        @if($bimbingan->isResolved())
                        <span class="text-sm text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle mr-1"></i>
                            Terselesaikan pada {{ $bimbingan->resolution_date ? $bimbingan->resolution_date->format('d M Y') : 'N/A' }}
                        </span>
                        @endif
                    </div>
                </div>

                <form action="{{ route('rekap.update', $bimbingan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Case Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="id_siswa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Siswa
                            </label>
                            <input type="text" id="id_siswa" name="id_siswa" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly value="{{ $bimbingan->siswa->nama ?? 'N/A' }}" />
                        </div>

                        <div>
                            <label for="jenis_bimbingan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Bimbingan
                            </label>
                            <input type="text" id="jenis_bimbingan" name="jenis_bimbingan" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly value="{{ $bimbingan->jenis_bimbingan }}" />
                        </div>

                        <div>
                            <label for="tgl_bimbingan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Bimbingan
                            </label>
                            <input type="date" id="tgl_bimbingan" name="tgl_bimbingan" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ $bimbingan->tgl_bimbingan }}" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Klik untuk memilih tanggal bimbingan
                            </p>
                        </div>

                        <div>
                            <label for="case_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Kasus
                            </label>
                            <select name="case_status" id="case_status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="open" {{ $bimbingan->case_status === 'open' ? 'selected' : '' }}>Terbuka</option>
                                <option value="in_progress" {{ $bimbingan->case_status === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                                <option value="resolved" {{ $bimbingan->case_status === 'resolved' ? 'selected' : '' }}>Terselesaikan</option>
                                <option value="closed" {{ $bimbingan->case_status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterangan
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" readonly>{{ $bimbingan->keterangan }}</textarea>
                    </div>

                    <!-- Balasan -->
                    <div>
                        <label for="balasan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Balasan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="balasan" name="balasan" rows="4" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Berikan balasan untuk bimbingan ini...">{{ $bimbingan->balasan }}</textarea>
                        @error('balasan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Resolution Fields (shown when resolving) -->
                    <div id="resolution-fields" class="space-y-4" style="display: none;">
                        <div>
                            <label for="resolution_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Solusi
                            </label>
                            <select name="resolution_type" id="resolution_type" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih jenis solusi</option>
                                <option value="counseling" {{ $bimbingan->resolution_type === 'counseling' ? 'selected' : '' }}>Konseling</option>
                                <option value="disciplinary_action" {{ $bimbingan->resolution_type === 'disciplinary_action' ? 'selected' : '' }}>Tindakan Disiplin</option>
                                <option value="mediation" {{ $bimbingan->resolution_type === 'mediation' ? 'selected' : '' }}>Mediasi</option>
                                <option value="referral" {{ $bimbingan->resolution_type === 'referral' ? 'selected' : '' }}>Rujukan</option>
                                <option value="other" {{ $bimbingan->resolution_type === 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label for="final_resolution" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Solusi Akhir
                            </label>
                            <textarea name="final_resolution" id="final_resolution" rows="4" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Jelaskan solusi akhir yang telah diterapkan...">{{ $bimbingan->final_resolution }}</textarea>
                        </div>

                        <div>
                            <label for="resolution_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan Tambahan
                            </label>
                            <textarea name="resolution_notes" id="resolution_notes" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Catatan tambahan...">{{ $bimbingan->resolution_notes }}</textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const caseStatusSelect = document.getElementById('case_status');
    const resolutionFields = document.getElementById('resolution-fields');
    
    function toggleResolutionFields() {
        const selectedStatus = caseStatusSelect.value;
        if (selectedStatus === 'resolved' || selectedStatus === 'closed') {
            resolutionFields.style.display = 'block';
        } else {
            resolutionFields.style.display = 'none';
        }
    }
    
    // Initial check
    toggleResolutionFields();
    
    // Listen for changes
    caseStatusSelect.addEventListener('change', toggleResolutionFields);
});
</script>
@endsection