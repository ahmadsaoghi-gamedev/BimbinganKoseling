<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-800 text-white py-4 -mx-4 -mt-6 mb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold">CURHAT RAHASIA</h1>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($curhats->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada curhat rahasia saat ini.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($curhats as $curhat)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                                            {{ $curhat->user->name ?? 'Anonim' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $curhat->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($curhat->status_baca == 'sudah dibaca')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Sudah Dibaca
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                Belum Dibaca
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4">
                                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $curhat->isi_curhat }}</p>
                                </div>

                                @if($curhat->attachment)
                                <div class="mb-4">
                                    <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-paperclip"></i>
                                        <span>Lampiran:</span>
                                        <a href="{{ asset('storage/' . $curhat->attachment) }}" 
                                           target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">
                                            {{ basename($curhat->attachment) }}
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="flex justify-end">
                                    @if($curhat->status_baca == 'belum dibaca')
                                    <form action="{{ route('gurubk.curhat.mark-read', $curhat->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                            Tandai Sudah Dibaca
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
