@extends('layouts.app')

@section('title', 'Detail Pemanggilan')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Detail Pemanggilan
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Informasi lengkap surat pemanggilan
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('summons.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        @if($summon->isDraft())
                        <a href="{{ route('summons.edit', $summon->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Status and Type Badges -->
                <div class="flex space-x-4 mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $summon->status_badge_class }}">
                        <i class="fas fa-circle mr-2"></i>
                        {{ $summon->status_text }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $summon->type_badge_class }}">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $summon->type_text }}
                    </span>
                </div>

                <!-- Student Information -->
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">
                        <i class="fas fa-user mr-2"></i>
                        Informasi Siswa
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Nama Siswa</p>
                            <p class="text-blue-900 dark:text-blue-100">{{ $summon->siswa->nama ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-700 dark:text-blue-300">NIS</p>
                            <p class="text-blue-900 dark:text-blue-100">{{ $summon->siswa->nis ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Kelas</p>
                            <p class="text-blue-900 dark:text-blue-100">{{ $summon->siswa->kelas ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Jurusan</p>
                            <p class="text-blue-900 dark:text-blue-100">{{ $summon->siswa->jurusan ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summon Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Dasar
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Subjek</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $summon->subject }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Dibuat Oleh</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $summon->createdBy->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Dibuat</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $summon->created_at->format('d M Y H:i') }}</p>
                            </div>
                            @if($summon->scheduled_at)
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jadwal Pemanggilan</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $summon->scheduled_date_formatted }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recipient Information -->
                    <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-user-friends mr-2"></i>
                            Informasi Penerima
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Penerima</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $summon->recipient_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kontak</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $summon->recipient_contact ?? 'N/A' }}</p>
                            </div>
                            @if($summon->sent_at)
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Dikirim</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $summon->sent_date_formatted }}</p>
                            </div>
                            @endif
                            @if($summon->attended_at)
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Hadir</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $summon->attended_date_formatted }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-file-text mr-2"></i>
                        Isi Pemanggilan
                    </h3>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <pre class="whitespace-pre-wrap text-gray-900 dark:text-gray-100 font-sans">{{ $summon->content }}</pre>
                    </div>
                </div>

                <!-- Notes -->
                @if($summon->notes)
                <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Catatan Tambahan
                    </h3>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                        <p class="text-gray-900 dark:text-gray-100">{{ $summon->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-center space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    @if($summon->isDraft())
                    <form action="{{ route('summons.send', $summon->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                onclick="return confirm('Apakah Anda yakin ingin mengirim pemanggilan ini?')">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Pemanggilan
                        </button>
                    </form>
                    @endif

                    @if($summon->isSent() && !$summon->isAttended() && !$summon->isCancelled())
                    <form action="{{ route('summons.mark-attended', $summon->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                onclick="return confirm('Tandai sebagai hadir?')">
                            <i class="fas fa-check mr-2"></i>
                            Tandai Hadir
                        </button>
                    </form>

                    <form action="{{ route('summons.mark-not-attended', $summon->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                onclick="return confirm('Tandai sebagai tidak hadir?')">
                            <i class="fas fa-times mr-2"></i>
                            Tandai Tidak Hadir
                        </button>
                    </form>
                    @endif

                    @if(!$summon->isAttended() && !$summon->isCancelled())
                    <form action="{{ route('summons.cancel', $summon->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pemanggilan ini?')">
                            <i class="fas fa-ban mr-2"></i>
                            Batalkan
                        </button>
                    </form>
                    @endif
                </div>

                <!-- Metadata -->
                @if($summon->metadata)
                <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-database mr-2"></i>
                        Metadata
                    </h3>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <pre class="text-sm text-gray-900 dark:text-gray-100">{{ json_encode($summon->metadata, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
