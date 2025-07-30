<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Pelanggaran   
 Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form  
                        action="{{ route('pelanggaran.store') }}" method="POST">
                        @csrf

                        <a href="{{ route('pelanggaran.index') }}">Kembali</a>

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
                                <x-input-label for="jenis_pelanggaran" value="Jenis Pelanggaran" />
                                <x-select-input id="jenis_pelanggaran" name="jenis_pelanggaran" class="mt-1 block w-full" required>
                                    <option value="" selected>Pilih Jenis Pelanggaran</option>
                                    <option value="Kenakalan Remaja">Kenakalan Remaja</option>
                                    <option value="Bullying">Bullying</option>
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('jenis_pelanggaran')" />
                            </div>

                            <div class="max-w-xl">
                                <label for="point_pelanggaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Point Pelanggaran:
                                </label>
                                <input type="number" id="point_pelanggaran" name="point_pelanggaran" class="mt-1 block w-full rounded-md text-black" required />
                                <x-input-error class="mt-2" :messages="$errors->get('point_pelanggaran')" />
                            </div>
                        </div>
                        <button value="true" type="submit" name="save" class="btn btn-light align-right">Simpan
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>