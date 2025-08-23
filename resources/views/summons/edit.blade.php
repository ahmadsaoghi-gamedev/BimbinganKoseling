@extends('layouts.app')

@section('title', 'Edit Pemanggilan')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Edit Pemanggilan
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Ubah informasi surat pemanggilan
                        </p>
                    </div>
                    <a href="{{ route('summons.show', $summon->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>

                @if($summon->isSent())
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                Pemanggilan Sudah Dikirim
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <p>Pemanggilan ini sudah dikirim pada {{ $summon->sent_date_formatted }}. Perubahan hanya akan mempengaruhi data internal.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('summons.update', $summon->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Student Selection (Read-only if already sent) -->
                    <div>
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Siswa <span class="text-red-500">*</span>
                        </label>
                        <select name="siswa_id" id="siswa_id" required
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                {{ $summon->isSent() ? 'disabled' : '' }}>
                            <option value="">Pilih siswa...</option>
                            @foreach($siswas as $s)
                            <option value="{{ $s->id }}" {{ $summon->siswa_id == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }} - {{ $s->kelas }} - {{ $s->jurusan }}
                            </option>
                            @endforeach
                        </select>
                        @if($summon->isSent())
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Siswa tidak dapat diubah setelah pemanggilan dikirim
                        </p>
                        @endif
                    </div>

                    <!-- Type Selection -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jenis Pemanggilan <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Pilih jenis...</option>
                            <option value="letter" {{ $summon->type == 'letter' ? 'selected' : '' }}>Surat</option>
                            <option value="whatsapp" {{ $summon->type == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="email" {{ $summon->type == 'email' ? 'selected' : '' }}>Email</option>
                        </select>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Subjek <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" id="subject" required
                               value="{{ old('subject', $summon->subject) }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                               placeholder="Masukkan subjek pemanggilan">
                    </div>

                    <!-- Scheduled Date -->
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jadwal Pemanggilan
                        </label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                               value="{{ old('scheduled_at', $summon->scheduled_at ? $summon->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Kosongkan jika belum dijadwalkan
                        </p>
                    </div>

                    <!-- Recipient Name -->
                    <div>
                        <label for="recipient_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Penerima
                        </label>
                        <input type="text" name="recipient_name" id="recipient_name"
                               value="{{ old('recipient_name', $summon->recipient_name) }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                               placeholder="Nama orang tua/wali">
                    </div>

                    <!-- Recipient Contact -->
                    <div>
                        <label for="recipient_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kontak Penerima
                        </label>
                        <input type="text" name="recipient_contact" id="recipient_contact"
                               value="{{ old('recipient_contact', $summon->recipient_contact) }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                               placeholder="Nomor telepon atau email">
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Isi Pemanggilan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" id="content" rows="12" required
                                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Masukkan isi pemanggilan...">{{ old('content', $summon->content) }}</textarea>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Tambahan
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Catatan internal (opsional)">{{ old('notes', $summon->notes) }}</textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('summons.show', $summon->id) }}"
                           class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
