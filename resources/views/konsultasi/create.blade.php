<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Curhat Rahasia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">Tuliskan masalah atau ceritamu di sini dengan leluasa. Informasi ini akan dijaga kerahasiaannya dan hanya dapat dilihat oleh Guru BK.</p>
                    
                    <form action="{{ route('konsultasi.store') }}" method="POST">
                        @csrf
                        
                        <div class="max-w-full">
                            <x-input-label for="isi_curhat" value="Pesan Anda" class="sr-only" />
                            <textarea id="isi_curhat" name="isi_curhat" rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600" required placeholder="Ketik ceritamu di sini..."></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('isi_curhat')" />
                        </div>
                        
                        <div class="mt-8 flex items-center justify-between">
                           <a href="{{ route('dashboard') }}" class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-200">
                               Batal
                           </a>
                           <x-primary-button type="submit" class="px-8 py-3 text-sm font-semibold">
                               Kirim Curhat
                           </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
