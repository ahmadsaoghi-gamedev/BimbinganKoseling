<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        @if(auth()->user()->hasRole('gurubk'))
            {{ __('Balas Bimbingan') }}
        @else
            {{ __('Bimbingan Konseling') }}  
        @endif  
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                <form action="{{ route('rekap.update', $bimbingan->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @if(auth()->user()->hasRole('gurubk'))
                        <a href="{{ route('rekap.index') }}">Kembali</a>

                        <div class="max-w-xl">
                            <x-input-label for="id_siswa" value="Nama Siswa" />
                            <x-text-input id="id_siswa" type="text" name="id_siswa" class="mt-1 block w-full" readonly value="{{ $bimbingan->siswa->nama }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('id_siswa')" />
                        </div>

                    @else
                        <input type="hidden" name="id_siswa" value="{{ auth()->user()->id - 1 }}">
                    @endif

                    <!-- Jenis Bimbingan -->
                    <div class="max-w-xl">
                            <x-input-label for="jenis_bimbingan" value="Jenis Bimbingan" />
                            <x-text-input id="jenis_bimbingan" type="text" name="jenis_bimbingan" class="mt-1 block w-full" readonly value="{{ $bimbingan->jenis_bimbingan }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('jenis_bimbingan')" />
                        </div>

                    <!-- Tanggal Bimbingan -->
                    <div class="max-w-xl">
                        <label for="tgl_bimbingan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Bimbingan:
                        </label>
                        <input type="date" id="tgl_bimbingan" name="tgl_bimbingan" class="mt-1 block w-full rounded-md text-black" value="{{ $bimbingan->tgl_bimbingan }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('tgl_bimbingan')" />
                    </div>

                    <!-- Keterangan -->
                    <div class="max-w-xl">
                        <x-input-label for="keterangan" value="Keterangan" />
                        <x-text-input id="keterangan" type="text" readonly name="keterangan" class="mt-1 block w-full" value="{{ $bimbingan->keterangan }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                    </div>

                    <div class="max-w-xl">
                        <x-input-label for="balasan" value="balasan" />
                        <x-text-input id="balasan" type="text" name="balasan" class="mt-1 block w-full" value="{{ $bimbingan->balasan }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('balasan')" />
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-light align-right">
                            Simpan
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>