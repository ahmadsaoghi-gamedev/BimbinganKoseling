@extends('layouts.app')

@section('title', 'Buat Pemanggilan Baru')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Buat Pemanggilan Baru
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Buat surat pemanggilan untuk orang tua/wali siswa
                        </p>
                    </div>
                    <a href="{{ route('summons.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>

                <form action="{{ route('summons.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Student Selection -->
                    <div>
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih Siswa <span class="text-red-500">*</span>
                        </label>
                        <select name="siswa_id" id="siswa_id" required
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                onchange="loadStudentData()">
                            <option value="">Pilih siswa...</option>
                            @foreach($siswas as $s)
                            <option value="{{ $s->id }}" {{ $siswa && $siswa->id == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }} - {{ $s->kelas }} - {{ $s->jurusan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Template Selection -->
                    <div>
                        <label for="template_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih Template
                        </label>
                        <select name="template_type" id="template_type"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                onchange="generateTemplate()">
                            <option value="">Pilih template...</option>
                            @foreach($templates as $key => $template)
                            <option value="{{ $key }}">{{ $template['name'] }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Template akan otomatis mengisi konten berdasarkan data siswa
                        </p>
                    </div>

                    <!-- Type Selection -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jenis Pemanggilan <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Pilih jenis...</option>
                            <option value="letter">Surat</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="email">Email</option>
                        </select>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Subjek <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" id="subject" required
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                               placeholder="Masukkan subjek pemanggilan">
                    </div>

                    <!-- Scheduled Date -->
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jadwal Pemanggilan
                        </label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at"
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
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                               placeholder="Nama orang tua/wali">
                    </div>

                    <!-- Recipient Contact -->
                    <div>
                        <label for="recipient_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kontak Penerima
                        </label>
                        <input type="text" name="recipient_contact" id="recipient_contact"
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
                                  placeholder="Masukkan isi pemanggilan..."></textarea>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Tambahan
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Catatan internal (opsional)"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('summons.index') }}"
                           class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pemanggilan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadStudentData() {
    const siswaId = document.getElementById('siswa_id').value;
    if (siswaId) {
        // Update recipient fields based on selected student
        fetch(`/api/siswa/${siswaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const siswa = data.data;
                    document.getElementById('recipient_name').value = siswa.nama_ortu || 'Orang Tua/Wali';
                    document.getElementById('recipient_contact').value = siswa.no_tlp_ortu || siswa.no_tlp || '';
                }
            })
            .catch(error => {
                console.error('Error loading student data:', error);
            });
    }
}

function generateTemplate() {
    const siswaId = document.getElementById('siswa_id').value;
    const templateType = document.getElementById('template_type').value;
    const scheduledAt = document.getElementById('scheduled_at').value;

    if (!siswaId || !templateType) {
        return;
    }

    // Show loading state
    const contentTextarea = document.getElementById('content');
    contentTextarea.value = 'Generating template...';
    contentTextarea.disabled = true;

    fetch('{{ route("summons.generate-template") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            siswa_id: siswaId,
            template_type: templateType,
            scheduled_at: scheduledAt
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            contentTextarea.value = '';
        } else {
            // Fill the form with template data
            document.getElementById('subject').value = data.subject || '';
            document.getElementById('content').value = data.content || '';
            document.getElementById('recipient_name').value = data.recipient_name || '';
            document.getElementById('recipient_contact').value = data.recipient_contact || '';
            
            // Set type based on template
            if (data.template_used) {
                const typeMap = {
                    'violation_summon_letter': 'letter',
                    'violation_whatsapp_message': 'whatsapp',
                    'violation_email_template': 'email'
                };
                document.getElementById('type').value = typeMap[data.template_used] || '';
            }
        }
    })
    .catch(error => {
        console.error('Error generating template:', error);
        alert('Terjadi kesalahan saat generate template');
    })
    .finally(() => {
        contentTextarea.disabled = false;
    });
}

// Auto-load student data if pre-selected
document.addEventListener('DOMContentLoaded', function() {
    const siswaId = document.getElementById('siswa_id').value;
    if (siswaId) {
        loadStudentData();
    }
});
</script>
@endsection
