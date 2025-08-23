@extends('layouts.app')

@section('title', 'Dashboard Sistem Pemanggilan')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Dashboard Sistem Pemanggilan
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Kelola surat pemanggilan orang tua/wali siswa
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('summons.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Pemanggilan
                        </a>
                        <button onclick="autoGenerateSummons()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-magic mr-2"></i>
                            Auto Generate
                        </button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <a href="{{ route('summons.index') }}" class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-800">
                                <i class="fas fa-envelope text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Pemanggilan</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $stats['total_summons'] ?? 0 }}</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('summons.index', ['status' => 'draft']) }}" class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg border border-yellow-200 dark:border-yellow-700 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-800">
                                <i class="fas fa-edit text-yellow-600 dark:text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Draft</p>
                                <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $stats['draft_summons'] ?? 0 }}</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('summons.index', ['status' => 'sent']) }}" class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg border border-purple-200 dark:border-purple-700 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-800">
                                <i class="fas fa-paper-plane text-purple-600 dark:text-purple-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Terkirim</p>
                                <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $stats['sent_summons'] ?? 0 }}</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('summons.index', ['status' => 'attended']) }}" class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-700 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-800">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Hadir</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $stats['attended_summons'] ?? 0 }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <a href="{{ route('summons.index') }}" class="bg-white dark:bg-gray-700 shadow rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Hari Ini</h4>
                            <span class="text-2xl font-bold text-blue-600">{{ $stats['today_summons'] ?? 0 }}</span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Pemanggilan yang dijadwalkan hari ini
                        </div>
                    </a>

                    <a href="{{ route('summons.index') }}" class="bg-white dark:bg-gray-700 shadow rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Minggu Ini</h4>
                            <span class="text-2xl font-bold text-green-600">{{ $stats['this_week_summons'] ?? 0 }}</span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Pemanggilan yang dijadwalkan minggu ini
                        </div>
                    </a>
                </div>

                <!-- Recent Summons -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        Pemanggilan Terbaru
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        @if($recentSummons->count() > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($recentSummons as $summon)
                            <li class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                                <i class="fas fa-envelope text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $summon->siswa->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($summon->subject, 50) }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $summon->created_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $summon->status_badge_class }}">
                                            {{ $summon->status_text }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $summon->type_badge_class }}">
                                            {{ $summon->type_text }}
                                        </span>
                                        <a href="{{ route('summons.show', $summon->id) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p>Belum ada pemanggilan</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Today's Summons -->
                @if($todaySummons->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-calendar-day mr-2 text-green-600"></i>
                        Pemanggilan Hari Ini
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($todaySummons as $summon)
                            <li class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center">
                                                <i class="fas fa-calendar-check text-green-600 dark:text-green-400"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $summon->siswa->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $summon->scheduled_date_formatted }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                {{ $summon->recipient_name ?? 'Orang Tua/Wali' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $summon->status_badge_class }}">
                                            {{ $summon->status_text }}
                                        </span>
                                        <a href="{{ route('summons.show', $summon->id) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Pending Summons -->
                @if($pendingSummons->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-clock mr-2 text-yellow-600"></i>
                        Pemanggilan Pending
                    </h3>
                    <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($pendingSummons as $summon)
                            <li class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-800 flex items-center justify-center">
                                                <i class="fas fa-edit text-yellow-600 dark:text-yellow-400"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $summon->siswa->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($summon->subject, 50) }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $summon->created_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $summon->type_badge_class }}">
                                            {{ $summon->type_text }}
                                        </span>
                                        <a href="{{ route('summons.show', $summon->id) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('summons.edit', $summon->id) }}" 
                                           class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function autoGenerateSummons() {
    if (confirm('Apakah Anda yakin ingin generate pemanggilan otomatis untuk siswa dengan pelanggaran ≥70 poin?')) {
        fetch('{{ route("summons.auto-generate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Gagal generate pemanggilan otomatis: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat generate pemanggilan otomatis');
        });
    }
}
</script>
@endsection
