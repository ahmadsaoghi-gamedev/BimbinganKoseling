<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Konsultasi Bimbingan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form  
                        action="{{ route('konsultasi.store') }}" method="POST">
                        @csrf

                        <a href="{{ route('konsultasi.index') }}">Kembali</a>

                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div class="max-w-xl">
                                <!-- Label untuk Select -->
                                <x-input-label for="id_siswa" value="Siswa" />

                                <!-- Select Input -->
                                <select id="id_siswa" name="id_siswa" class="mt-1 block w-full" required>
                                    <option value="" selected>Pilih Siswa</option>
                                    @foreach (\App\Models\Siswa::all() as $siswa)
                                    <option value="{{ $siswa->id }}">{{ $siswa->nama }}</option>
                                    @endforeach
                                </select>

                                <!-- Error Message -->
                                <x-input-error class="mt-2" :messages="$errors->get('id_siswa')" />
                            </div>


                            <div class="max-w-xl">
                                <x-input-label for="jenis_konsultasi" value="Jenis Konsultasi" />
                                <x-select-input id="jenis_konsultasi" name="jenis_konsultasi" class="mt-1 block w-full" required>
                                    <option value="" selected>Pilih Jenis Bimbingan</option>
                                    <option value="Sosial">Sosial</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Pribadi">Pribadi</option>
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('jenis_konsultasi')" />
                            </div>

                            <div class="max-w-xl">
                                <label for="tgl_konsultasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tanggal Konsultasi:
                                </label>
                                <input type="date" id="tgl_konsultasi" name="tgl_konsultasi" class="mt-1 block w-full rounded-md text-black" required />
                                <x-input-error class="mt-2" :messages="$errors->get('tgl_konsultasi')" />
                            </div>

                            <div class="max-w-xl">
                                <label for="topik" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Topik:
                                </label>
                                <input type="date" id="topik" name="topik" class="mt-1 block w-full rounded-md text-black" required />
                                <x-input-error class="mt-2" :messages="$errors->get('topik')" />
                            </div>

                        </div>
                        <button value="true" type="submit" name="save" class="btn btn-light align-right">Simpan
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>